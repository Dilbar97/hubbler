<?php

namespace app\interfaces;

interface RateInterface
{
    /**
     * @param $date
     * @param $prevDate
     * @param $code
     * @return array should return ['success' => bool, 'rate' => 0, 'diff' => 0]
     */
    public static function getRateAndDiff($date, $prevDate, $code): array;
}
