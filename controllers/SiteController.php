<?php

namespace app\controllers;

use app\service\RatesDbService;
use app\service\RatesRdbService;
use yii\rest\Controller;

class SiteController extends Controller
{
    public function verbs()
    {
        return [
            'index' => ['post'],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return \yii\web\Response
     */
    public function actionIndex()
    {
        $post = \Yii::$app->request->post();

        $prevDate = $post['date'] ? date("Y-m-d", strtotime($post['date'] . " - 1 day")) : date('Y-m-d');
        $date = $post['date'] ?? date('Y-m-d');
        $code = strtoupper($post['code']) ?? "RUB";

        $redisData = RatesRdbService::getRateAndDiff($date, $prevDate, $code);
        if ($redisData['rate'] != 0) {
            return $this->asJson([
                "success" => true,
                "rate" => $redisData['rate'],
                "diff" => $redisData['diff'],
                "error" => ""
            ]);
        }

        $dbData = RatesDbService::getRateAndDiff($date, $prevDate, $code);
        if ($dbData['rate'] != 0) {
            return $this->asJson([
                "success" => true,
                "rate" => $dbData['rate'],
                "diff" => $dbData['diff'],
                "error" => ""
            ]);
        }


        return $this->asJson([
            "success" => false,
            "rate" => 0,
            "diff" => 0,
            "error" => "No data found!"
        ]);
    }
}
