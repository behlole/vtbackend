<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class StudentControl extends Event implements ShouldBroadcast
{
    /**
     * Create a new event instance.
     *
     * @return void
     */

    private $activity;
    public function __construct($studentActivity)
    {
        $this->activity=$studentActivity;
    }

    public function broadcastOn()
    {
//        return ['virtual-teacher'];
//        return new PrivateChannel('StudentControl');
        return new PrivateChannel('virtual-teacher');
    }

    public function broadcastAs()
    {
        return 'virtual-teacher';
    }
}
