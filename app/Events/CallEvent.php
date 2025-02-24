<?php

namespace App\Events;

use App\Models\Call;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CallEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $call;
    public $eventType;

    public function __construct(Call $call, string $eventType)
    {
        $this->call = $call;
        $this->eventType = $eventType; 
    }

    public function broadcastOn()
    {
        return new Channel('calls');
    }

    public function broadcastAs()
    {
        return 'Call' . ucfirst($this->eventType);
    }
}
