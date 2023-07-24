<?php

namespace app\service;

use app\models\Rates;
use app\interfaces\RateInterface;

class RatesDbService implements RateInterface
{

    /**
     * @param $date
     * @param $prevDate
     * @param $code
     * @return array should return ['rate' => 0, 'diff' => 0]
     */
    public static function getRateAndDiff($date, $prevDate, $code): array
    {
        $rates = Rates::find()
            ->select([
                'rate' => 'rate',
                'prev_rate' => Rates::find()->select('rate')->where(['date' => $prevDate, 'code' => $code])
            ])
            ->where(['date' => $date, 'code' => $code])
            ->asArray()
            ->one();

        $rate = $rates['rate'] ?? 0;
        $ratePrev = $rates['prev_rate'] ?? 0;
        $diff = abs($rate - $ratePrev);

        return ['rate' => $rate, 'diff' => $diff];
    }
}
