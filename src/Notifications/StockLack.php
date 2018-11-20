<?php

namespace Xpressengine\Plugins\XeroCommerce\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Xpressengine\Plugins\XeroCommerce\Models\ProductOptionItem;

class StockLack extends Notification
{
    use Queueable;

    private $optionItem;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(ProductOptionItem $optionItem)
    {
        //
        $this->optionItem = $optionItem;
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
            ->line($this->optionItem->product->getName() . '/' . $this->optionItem->getName())
            ->action(
                '재고 확인',
                route('xero_commerce::setting.product.show', ['productId' => $this->optionItem->product_id])
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
