<?php

namespace IgniterLabs\KitchenDisplay\Events;

use Igniter\Cart\Models\Order;
use Illuminate\Broadcasting\Channel;

//use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class KitchenDisplayUpdated implements ShouldBroadcast
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order)
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
}
