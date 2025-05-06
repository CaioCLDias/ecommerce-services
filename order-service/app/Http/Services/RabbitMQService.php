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
            env('RABBITMQ_PASSWORD'),
            '/',
            false,
            'AMQPLAIN',
            null,
            'en_US',
            10,
            65,
            null,
            true,
            30
        );


        $this->channel = $this->connection->channel();

      
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


    public function consume($callback)
    {
        $this->channel->basic_consume($this->queue, '', false, false, false, false, function ($msg) use ($callback) {
            try {
                echo 'Message received: ', $msg->body, "\n";

                $callback($msg);

                $this->channel->basic_ack($msg->delivery_info['delivery_tag']);
            } catch (\Exception $e) {
                echo 'Error processing message: ', $e->getMessage(), "\n";

                // Reject da mensagem
                $this->channel->basic_reject($msg->delivery_info['delivery_tag'], false);
            }
        });

        while ($this->channel->callbacks) {
            $this->channel->wait();
        }
    }

 
    public function publish($data)
    {
        $message = new AMQPMessage(json_encode($data), [
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
        ]);

        $this->channel->basic_publish($message, '', $this->queue);
    }
}
