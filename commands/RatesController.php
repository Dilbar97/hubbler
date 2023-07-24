<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\service\CbrService;
use yii\console\Controller;

class RatesController extends Controller
{
    /**
     * @param int $minusDays
     * @return int Exit code
     */
    public function actionIndex($minusDays = 0)
    {
        $crbSvc =  new CbrService();

        for ($i = $minusDays; $i >= 0; $i--) {
            $date = strtotime('- ' . $i . ' day');
            if ($i == 0) {
                $date = time();
            }

            $crbSvc->dailyRate($date);
        }
    }

    public function actionConsume()
    {
        \Yii::$app->rabbitmq->consume();
    }
}
