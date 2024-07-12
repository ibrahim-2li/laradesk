<?php

namespace App\Notifications\Order;

use App\Models\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Str;

class AssignOrderToBranch extends Notification
{
    use Queueable;

    private $order;
    private $agent;

    /**
     * Create a new notification instance.
     *
     * @param  Order  $order
     * @param  User  $agent
     */
    public function __construct(Order $order, User $agent)
    {
        $this->order = $order;
        $this->agent = $agent;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(__('New order assigned to branch').': '.Str::limit($this->order->subject, 35))
            ->greeting(__('Hi').' '.$this->agent->name.',')
            ->line(__('A new order has been assigned to your branch').'.')
            ->line(__('You can view the order details and add responses from this link').':')
            ->action(__('See order'), url('/dashboard/orders/'.$this->order->uuid.'/manage'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
