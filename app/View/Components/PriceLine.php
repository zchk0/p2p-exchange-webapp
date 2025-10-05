<?php

namespace App\View\Components;

use Carbon\Carbon;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PriceLine extends Component
{
    public array $mclist = [];
    public string $date;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct() {}

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        [$this->mclist, $this->date] = $this->loadData();
        return view('components.price-line');
    }

    /**
     * Выгружает данные курсов из БД для компонента
     *
     * @return array
     * 
     */
    protected function loadData(): array
    {
        return Cache::remember('price-line:'.now()->format('Y-m-d'), now()->addMinutes(5), function () {
            $today = Carbon::today()->format('d.m.Y');
            $yday  = Carbon::yesterday()->format('d.m.Y');

            $rows = DB::select('select fiat, price, cbankprice from zchk_app.binance where date = ?', [$today]);
            $used = $today;
            if (empty($rows)) {
                $rows = DB::select('select fiat, price, cbankprice from zchk_app.binance where date = ?', [$yday]);
                $used = $yday;
            }
            $fiats = ['usd','eur','rub','kzt','try','uah'];
            $res = array_fill_keys($fiats, ['price'=>0.0,'cbprice'=>0.0]);

            $agg = [];
            foreach ($rows as $r) {
                $f = strtolower($r->fiat);
                $agg[$f]['sum'] = ($agg[$f]['sum'] ?? 0) + (float)$r->price;
                $agg[$f]['cnt'] = ($agg[$f]['cnt'] ?? 0) + 1;
                $agg[$f]['cb']  = (float)$r->cbankprice;
            }
            foreach ($agg as $f => $a) {
                $res[$f]['price']   = $a['cnt'] ? round($a['sum'] / $a['cnt'], 3) : 0.0;
                $res[$f]['cbprice'] = $a['cb'] ?? 0.0;
            }
            return [$res, $used];
        });
    }
}
