<?php

namespace App\Http\Controllers;

use App\Models\PriceBook;
use App\Models\PriceRate;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class StatController extends Controller
{
    public function index(Request $request)
    {
        // Дата "вчера" в формате, как хранится в БД
        $yesterday = now()->subDay()->format('d.m.Y');

        // Было: select * from zchk_app.binance where date = ?
        $currlistold = PriceRate::where('date', $yesterday)->get();

        // Все записи fiat='rub', отсортированные по реальной дате (а не строке)
        $currusdt = PriceRate::where('fiat', 'rub')
            ->whereNotNull('price')
            ->orderByRaw("STR_TO_DATE(`date`, '%d.%m.%Y') ASC")
            ->get();

        // Портфель пользователя
        $portfolio = Auth::check()
            ? PriceBook::where('user_id', Auth::id())->get()
            : collect();

        // Группируем по дате и СОРТИРУЕМ ключи как даты
        $grouped = $currusdt->groupBy('date')
            ->sortKeysUsing(function (string $a, string $b) {
                $da = Carbon::createFromFormat('d.m.Y', $a);
                $db = Carbon::createFromFormat('d.m.Y', $b);

                return $da <=> $db;
            });

        $priceDates = $cbPrices = $p2pPrices = $spreads = [];
        foreach ($grouped as $date => $rows) {
            $priceDates[] = $date;

            // Цена ЦБ: сначала первая непустая за день, затем средняя, иначе 0
            $cb = $rows->firstWhere('cbankprice', '!=', null)->cbankprice
                ?? $rows->avg('cbankprice')
                ?? 0.0;
            $cb = round((float) $cb, 3);
            $cbPrices[] = $cb;

            // Средняя P2P за день
            $avgP2P = round((float) $rows->avg('price'), 3);
            $p2pPrices[] = $avgP2P;

            // Спред, %
            $spreads[] = ($cb + $avgP2P) > 0
                ? abs(($cb - $avgP2P) / (($cb + $avgP2P) / 2)) * 100
                : 0.0;
        }

        // Нижняя подсказка для шкалы Y
        $lineportf = ! empty($p2pPrices) ? min($p2pPrices) : 0.0;

        // Портфель: средняя цена покупки ($), текущая оценка (RUB) и P/L
        $avgBuyPrice = 0.0;
        $portfolioRub = null;
        $profitRub = null;
        $profitPct = null;

        $totalValue = 0.0; // сумма $ в портфеле
        $totalCost = 0.0; // вложено $ (сумма value*price)

        if ($portfolio->isNotEmpty()) {
            $totalValue = (float) $portfolio->sum('value');
            $totalCost = (float) $portfolio->sum(fn ($p) => $p->value * $p->price);

            if ($totalValue > 0) {
                $avgBuyPrice = $totalCost / $totalValue; // красная линия на графике
            }

            // Текущий курс RUB берём по последней дате fiat='rub'
            $currentRub = $this->currentRubRate();
            if ($currentRub !== null) {
                $portfolioRub = round($totalValue * $currentRub, 2);
                $profitRub = round($portfolioRub - $totalCost, 2);
                if ($totalCost > 0) {
                    $profitPct = round(($portfolioRub / $totalCost - 1) * 100, 2);
                }
            }
        }

        return view('p2p-app.stat', [
            'currlistold' => $currlistold,
            'currusdt' => $currusdt,
            'priceDates' => $priceDates,
            'cbPrices' => $cbPrices,
            'p2pPrices' => $p2pPrices,
            'spreads' => $spreads,
            'lineportf' => $lineportf,
            'avgBuyPrice' => $avgBuyPrice,
            'portfolioRub' => $portfolioRub,
            'profitRub' => $profitRub,
            'profitPct' => $profitPct,
            'totalValue' => $totalValue,
        ]);
    }

    /**
     * Текущий ориентир курса USD/RUB (последняя дата fiat='rub').
     * Приоритет: cbankprice, затем price. Сортировка по реальной дате.
     *
     * @return float|null
     */
    private function currentRubRate(): ?float
    {
        $lastRub = PriceRate::where('fiat', 'rub')
            ->orderByRaw("STR_TO_DATE(`date`, '%d.%m.%Y') ASC")
            ->get()
            ->last();

        if (! $lastRub) {
            return null;
        }

        $cb = (float) ($lastRub->cbankprice ?? 0);
        $p2p = (float) ($lastRub->price ?? 0);

        if ($cb > 0) {
            return $cb;
        }
        if ($p2p > 0) {
            return $p2p;
        }

        return null;
    }
}
