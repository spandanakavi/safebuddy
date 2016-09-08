<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Tracking extends Model
{
    public function publishToQueue()
    {
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->exchange_declare('newMapExchange', 'topic', false, true, false);
        $channel->queue_declare('mapQueue', false, true, false, false);
        $channel->queue_bind('mapQueue', 'newMapExchange');
        $routingKey = 'key.a';

        $json = sprintf(
                    '{"id":"%s", "lat":%s, "lng":%s, "time":"%s"}',
                    $this->trip_id, $this->lat, $this->lng, $this->current_time
        );
        $msg = new AMQPMessage($json);

        $channel->basic_publish($msg, 'newMapExchange', $routingKey);
    }
}
