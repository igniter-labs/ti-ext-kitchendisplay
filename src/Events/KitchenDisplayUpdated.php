<?php

namespace IgniterLabs\KitchenDisplay\Events;

use Illuminate\Broadcasting\Channel;

//use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class KitchenDisplayUpdated implements ShouldBroadcast
{
    use Queueable, SerializesModels;

    public function __construct(public $orderId, public $newTime)
    {
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('igniterlabs.kitchendisplay')
        ];
    }

    public function broadcastAs(): string
    {
        return 'kitchendisplay.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'order_id' => $this->orderId,
            'new_time' => $this->newTime,
        ];
    }
}
