<?php

namespace Xpressengine\Plugins\XeroCommerce\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Xpressengine\Plugins\XeroCommerce\Models\ProductVariant;

class StockLack extends Notification
{
    use Queueable;

    private $productVariant;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(ProductVariant $productVariant)
    {
        //
        $this->productVariant = $productVariant;
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
        return (new MailMessage())
            ->greeting('재고 부족')
            ->line('해당 상품의 재고가 부족합니다.')
            ->line($this->productVariant->product->name . '/' . $this->productVariant->name)
            ->action(
                '재고 확인',
                route('xero_commerce::setting.product.show', ['productId' => $this->productVariant->product_id])
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
