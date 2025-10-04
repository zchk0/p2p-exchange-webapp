<?php
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
    require_once('/var/www/zchk-app/vendor/autoload.php');

    use GuzzleHttp\Client;
    use GuzzleHttp\Psr7;
    use GuzzleHttp\Exception\RequestException;
    use Illuminate\Http\Request;

    //https://v1.cosmos.network/rpc/v0.41.4 для проверки запросов
    $cosmos_balance  = "https://node.atomscan.com/cosmos/bank/v1beta1/balances/cosmos164dpffm974ayj7j9r33t4yhv7a6wu59q6d2c6k";
    $cosmos_balance_stake     = "https://node.atomscan.com/cosmos/staking/v1beta1/delegations/cosmos164dpffm974ayj7j9r33t4yhv7a6wu59q6d2c6k";
    $cosmos_balance_reward    = "https://node.atomscan.com/cosmos/distribution/v1beta1/delegators/cosmos164dpffm974ayj7j9r33t4yhv7a6wu59q6d2c6k/rewards";
    
    //WAX
    $accountNameWAX = 'pqny.wam'; // Замените на имя вашего аккаунта
    $chainApiUrl = 'https://wax.api.eosnation.io'; // URL основного API WAX
    $clientWAX = new Client(['base_uri' => $chainApiUrl]);
    $payloadWAX = [
        'account' => $accountNameWAX,
        'code' => 'eosio.token',
        'symbol' => 'WAX'
    ];
    
    //ATOM
    $walletAddress = 'cosmos164dpffm974ayj7j9r33t4yhv7a6wu59q6d2c6k';
    //$nodeUrl = 'https://node.atomscan.com'; //бесплатная нода
    
    //платная нода 40 000 запросов в сутки https://account.getblock.io/
    $nodeUrl = 'https://go.getblock.io/3404e136742d4695bdecd89cc4eb68e9'; // JSON-RPC
    $nodeUrl = 'https://go.getblock.io/98b46c73f88a44a19a9b496a8a8d1b46'; // rest

    // еще платная нода 50 000 запросов в сутки
    // https://www.allthatnode.com/project.dsrv?seq=6eae2bde2e235301eaf811ddf7195961ffa57f4d

    //$client = new Client(['base_uri' => $nodeUrl]);
    $client = new Client(['verify' => false]);
    $logger;
    $ii = 0;
    $ft0 = true;
    
    //$ft0 = ($_SERVER['REQUEST_URI'] == "/info-zchk.php?t=0" || $_SERVER['REQUEST_URI'] == "/info-zchk.php?t=1)";
    
    if($ft0) {
        try {
                $ii = 1;
                
                // Получение свободного баланса
                $balanceResponse = $client->get($nodeUrl . "/cosmos/bank/v1beta1/balances/{$walletAddress}");
                $balanceData = json_decode($balanceResponse->getBody()->getContents());
                $freeBalance = 0;
                foreach($balanceData->balances as $atomTokens) {
                    $freeBalance = ($atomTokens->denom == 'uatom') ? $atomTokens->amount : 0;
                }
    
                // Получение информации о делегированных монетах
                $stakingResponse = $client->get($nodeUrl . "/cosmos/staking/v1beta1/delegations/{$walletAddress}");
                $stakingData = json_decode($stakingResponse->getBody()->getContents(), true);
                $delegatedBalance = 0;
                foreach ($stakingData['delegation_responses'] as $delegation) {
                    $delegatedBalance += $delegation['balance']['amount'];
                }
               
                //var_dump($stakingData);
                
                // Получение информации о наградах за стейкинг
                $stakingResponse = $client->get($nodeUrl . "/cosmos/distribution/v1beta1/delegators/{$walletAddress}/rewards");
                $stakingData = json_decode($stakingResponse->getBody()->getContents());
                $RewardBalance = $stakingData->total[0]->amount;
                
                // Конвертация в ATOM
                $freeBalanceAtom = round($freeBalance / 1000000, 4);
                $delegatedBalanceAtom = round($delegatedBalance / 1000000, 3);
                $RewardBalanceAtom = round($RewardBalance / 1000000, 4);
    
                //echo "Free balance: {$freeBalanceAtom} ATOM" . PHP_EOL;
                //echo "Delegated balance: {$delegatedBalanceAtom} ATOM" . PHP_EOL;
                //echo "Reward balance: {$RewardBalanceAtom} ATOM" . PHP_EOL;
                //echo "ИТОГОВЫЙ: " . $RewardBalanceAtom+$delegatedBalanceAtom+$freeBalanceAtom . " ATOM" . PHP_EOL;
    
                //WAX
                $responseWAX0 = $clientWAX->post('/v1/chain/get_account', ['json' => ['account_name' => $accountNameWAX]]);
                $accountDataWAX = json_decode($responseWAX0->getBody(), true);
                
                $freeBalanceWAX_ = round((!empty($accountDataWAX)) ? floatval($accountDataWAX['core_liquid_balance']) : '0.0000', 4);
                $stakeBalanceWAX_ = round($accountDataWAX['voter_info']['staked'] / 100000000, 4);
                
                //print_r($accountVpayShare);
                //$rewardsBalance = floatval($accountDataWAX['self_delegated_bandwidth']['total']);
            } catch (RequestException $e) {
                $response = json_decode($e->getResponse()->getBody());
                $logger->error($e->getResponse()->getBody());
                return (object)  array(
                    'status' => 'error',
                    'message' => $response->error->message
                );
            }
    }
    else {
        echo date("d.m.Y");
        echo " / фыр фыр фыр";
    }
?>


<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>info</title>
</head>
<body>
    <table class="table_sort">
    <thead>
        <tr>
            <th>#</th>
            <th>SUM</th>
            <th>FREE</th>
            <th>STAKE</th>
            <th>REWARD</th>
        </tr>
    </thead>
    <tbody id="table-logs">
    <?php if($ii == 1) { ?>
    <tr id="0"><td>ATOM</td><td id="sum-v"><?php echo round($RewardBalanceAtom + $delegatedBalanceAtom + $freeBalanceAtom, 2); ?></td><td id="free-v"><?php echo $freeBalanceAtom; ?></td><td id="stake-v"><?php echo $delegatedBalanceAtom; ?></td><td id="reward-v"><?php echo $RewardBalanceAtom; ?></td></tr>
    <tr id="1"><td>WAX</td><td id="sum-v"><?php echo $freeBalanceWAX_+$stakeBalanceWAX_; ?></td><td id="free-v"><?php echo $freeBalanceWAX_ ?? 0; ?></td><td id="stake-v"><?php echo $stakeBalanceWAX_ ?? 0; ?></td><td id="reward-v"></td></tr>
    <?php } ?>
    </tbody>
    </table>
</body>
</html>
    
    