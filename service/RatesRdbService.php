<?php

namespace app\service;

use app\interfaces\RateInterface;

class RatesRdbService implements RateInterface
{
    /**
     * @param $date
     * @param $prevDate
     * @param $code
     * @return array should return ['rate' => 0, 'diff' => 0]
     */
    public static function getRateAndDiff($date, $prevDate, $code): array
    {
        $rdbRates = json_decode(\Yii::$app->cache->redis->hget($date, $code), true);
        $rdbRatesPrev = json_decode(\Yii::$app->cache->redis->hget($prevDate, $code), true);

        if (!empty($rdbRates)) {
            $rate = $rdbRates["rate"] ?? 0;
            $prevRate = $rdbRatesPrev["rate"] ?? 0;
            $diff = abs($rate - $prevRate);

            return ['rate' => $rate, 'diff' => $diff];
        }

        return ['rate' => 0, 'diff' => 0];
    }
}
