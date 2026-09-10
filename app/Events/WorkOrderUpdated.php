<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WorkOrderUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public string $workOrderId,
        public string $status,
        public int $amount,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel('work-order')
        ];
    }

    public function broadcastAs(): string
    {
        return 'WorkOrderUpdated';
    }
}
