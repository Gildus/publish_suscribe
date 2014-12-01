<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPConnection;

/*
 * It´s necesary to have the URL AMQP, 
 * for example with https://www.cloudamqp.com/
 * 
 * Replace the URL:
 * amqp://User:Password@Server/Vhost
 * 
 * By the correct URL
 * 
 */

$url = parse_url('amqp://User:Password@Server/Vhost');
$queue = 'basic_get_queue';

try {
    
    $connection = new AMQPConnection($url['host'], 5672, $url['user'], $url['pass'], substr($url['path'], 1));
    $channel = $connection->channel();

    $channel->queue_declare($queue,  false, true, false, false);
    echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

    $channel->basic_consume($queue, '', false, true, false, false, function($msg) {
            echo " [x] Received ", $msg->body, "\n";
    });
    while(count($channel->callbacks)) {
            $channel->wait();
    }

    $channel->close();
    $connection->close();
    
} catch (Exception $ex) {
    die($e->getMessage());
}


