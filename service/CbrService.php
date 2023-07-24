<?php

namespace app\service;
use yii\db\Exception;

class CbrService
{
    public $client;

    public function __construct()
    {
        $wsdl = 'http://www.cbr.ru/DailyInfoWebServ/DailyInfo.asmx?WSDL';
        $opts = array(
            'ssl'   => array(
                'verify_peer'          => false
            ),
            'https' => array(
                'curl_verify_ssl_peer'  => false,
                'curl_verify_ssl_host'  => false
            )
        );
        $streamContext = stream_context_create($opts);
        $this->client = new \SoapClient($wsdl, [
            'exceptions' => 1,
            'cache_wsdl' => WSDL_CACHE_MEMORY,
            'trace' => true,
            'stream_context'    => $streamContext
        ]);
    }
    public function dailyRate($date)
    {
        $result = $this->client->GetCursOnDate([
            'On_date' => date('Y-m-d', $date),
        ]);

        $data = new \SimpleXMLElement($result->GetCursOnDateResult->any);
        foreach ($data->ValuteData->ValuteCursOnDate as $curs) {
            $queueMsg = [
                date('Y-m-d', $date) => [
                    'code' => trim($curs->VchCode),
                    'name' => trim($curs->Vname),
                    'rate' => floatval($curs->Vcurs) / floatval($curs->Vnom)
                ]
            ];

            echo printf('Отправка данных по %s в очередь', trim($curs->VchCode)) . PHP_EOL;
            \Yii::$app->rabbitmq->publish(json_encode($queueMsg));
        }

        return $result;
    }
}

