<?php

namespace App\Http\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQService
{
    protected $connection;
    protected $channel;
    protected $queue;
    protected $dlqQueue;

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection(
            env('RABBITMQ_HOST'),
            env('RABBITMQ_PORT'),
            env('RABBITMQ_USER'),
            env('RABBITMQ_PASSWORD')
        );

        $this->channel = $this->connection->channel();
        

    }

    public function publish(array $data)
    {
        $message = new AMQPMessage(json_encode($data), [
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
        ]);

        $this->channel->basic_publish($message, '', $this->queue);
    }

    public function setQueue($queue)
    {
        $this->queue = $queue;
        $this->dlqQueue = $queue . '_dlq';

        // DLQ
        $this->channel->queue_declare($this->dlqQueue, false, true, false, false);

        // Fila principal com DLQ
        $this->channel->queue_declare($this->queue, false, true, false, false, false, [
            'x-dead-letter-exchange' => ['S', ''],
            'x-dead-letter-routing-key' => ['S', $this->dlqQueue],
        ]);
    }


    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }
    

}