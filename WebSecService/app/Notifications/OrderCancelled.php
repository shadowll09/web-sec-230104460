<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OrderCancelled extends Notification implements ShouldQueue
{
    use Queueable;
    
    protected $order;
    protected $reason;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order, string $reason)
    {
        $this->order = $order;
        $this->reason = $reason;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'customer_name' => $this->order->user->name,
            'reason' => $this->reason,
            'cancelled_at' => now()->toDateTimeString(),
            'amount' => $this->order->total_amount,
            'url' => route('orders.show', $this->order->id)
        ];
    }
}
