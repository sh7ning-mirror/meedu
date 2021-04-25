<?php

/*
 * This file is part of the Qsnh/meedu.
 *
 * (c) XiaoTeng <616896861@qq.com>
 */

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class UserVideoWatchedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $videoId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($userId, $videoId)
    {
        $this->userId = $userId;
        $this->videoId = $videoId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
