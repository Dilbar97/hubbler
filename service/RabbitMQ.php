<?php

namespace app\service;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use app\models\Rates;
use yii\base\Component;

class RabbitMQ extends Component
{
    public $config;

    /**
     * @throws \Exception
     */
    public function publish($pubMsg)
    {
        $connection = new AMQPStreamConnection($this->config['RABBITMQ_HOST'], $this->config['RABBITMQ_PORT'], $this->config['RABBITMQ_USER'], $this->config['RABBITMQ_PASS']);
        $client = $connection->channel();

        $client->queue_declare($this->config['RABBITMQ_QUEUE_NAME'], false, false, false, false);
        $client->exchange_declare($this->config['RABBITMQ_EXCHANGE_NAME'], 'direct', false, false, false);
        $client->queue_bind($this->config['RABBITMQ_QUEUE_NAME'], $this->config['RABBITMQ_EXCHANGE_NAME'], $this->config['RABBITMQ_ROUTING_NAME']);

        $msg = new AMQPMessage($pubMsg);
        $client->basic_publish($msg, $this->config['RABBITMQ_EXCHANGE_NAME'], $this->config['RABBITMQ_ROUTING_NAME']);

        $client->close();
        $connection->close();
    }

    /**
     * @throws \Exception
     */
    public function consume()
    {
        echo "Listening...\n";

        $connection = new AMQPStreamConnection($this->config['RABBITMQ_HOST'], $this->config['RABBITMQ_PORT'], $this->config['RABBITMQ_USER'], $this->config['RABBITMQ_PASS']);
        $client = $connection->channel();

        $client->queue_declare($this->config['RABBITMQ_QUEUE_NAME'], false, false, false, false);
        $client->exchange_declare($this->config['RABBITMQ_EXCHANGE_NAME'], 'direct', false, false, false);
        $client->queue_bind($this->config['RABBITMQ_QUEUE_NAME'], $this->config['RABBITMQ_EXCHANGE_NAME'], $this->config['RABBITMQ_ROUTING_NAME']);

        echo " [*] Waiting for messages. To exit press CTRL+C\n";

        $callback = function ($msg) {
            foreach (json_decode($msg->body, true) as $date => $rateData) {
                echo printf('Получение данных по %s из очереди', $rateData['code']) . PHP_EOL;

                $ratesDb = new Rates();
                if (!$ratesDb->exists($date, $rateData['code'])) {
                    $ratesDb->store($date, $rateData['code'], $rateData['name'], $rateData['rate']);
                }

                \Yii::$app->cache->redis->hset($date, $rateData['code'], json_encode($rateData));
            }
        };

        $client->basic_consume($this->config['RABBITMQ_QUEUE_NAME'], '', false, true, false, false, $callback);

        while ($client->is_open()) {
            $client->wait();
        }

        $client->close();
        $connection->close();
    }
}
