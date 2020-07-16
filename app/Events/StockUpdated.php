<?php

namespace App\Events;

use App\Stock;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class StockUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $stock;

    public $message;

    /**
     * Create a new event instance.
     *
     * @param Stock  $stock - The stock that was updated
     * @param string $message
     */
    public function __construct(Stock $stock, string $message = "")
    {
        $this->stock = $stock;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('stocks');
    }
}
