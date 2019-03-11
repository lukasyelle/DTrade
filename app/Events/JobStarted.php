<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class JobStarted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $message;

    /**
     * Create a new event instance.
     *
     * @param $jobString String: The stringified version of the job class
     */
    public function __construct($jobString)
    {
        $this->message = "{$jobString} started...";
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('jobs');
    }
}
