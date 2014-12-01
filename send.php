<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

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


$exchange = 'amq.direct';
$queue = 'basic_get_queue';

try {    
    
    $connection = new AMQPConnection($url['host'], 5672, $url['user'], $url['pass'], substr($url['path'], 1));
    $channel = $connection->channel();
    $channel->queue_declare($queue,  false, true, false, false);

    $channel->exchange_declare($exchange, 'direct', true, true, false);
    $channel->queue_bind($queue, $exchange);

    $mensaje = 'Habla Gildus !!! '.date('Y-m-d H:i:s');
    $msg = new AMQPMessage($mensaje);
    $channel->basic_publish($msg, $exchange);
    echo " [x] Sent '".$mensaje."'\n";

    $channel->close();
    $connection->close();
    
} catch(Exception $e) {
    
    die($e->getMessage());
    
}


