<?php

namespace Xpressengine\Plugins\XeroCommerce\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Xpressengine\Plugins\XeroCommerce\Models\Order;
use Xpressengine\Plugins\XeroCommerce\Models\ProductVariant;
use Xpressengine\Plugins\XeroCommerce\Plugin;

class OrderMake extends Notification
{
    use Queueable;

    private $order;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        //
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        \XePresenter::setSkinTargetId(\Xpressengine\Plugins\XeroCommerce\Plugin::getId());

        return (new MailMessage())
            ->subject('주문하신 상품이 정상적으로 주문되었습니다.')
            ->line(
                \XePresenter::make('order.email', ['order'=>$this->order])->render()
            );
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
