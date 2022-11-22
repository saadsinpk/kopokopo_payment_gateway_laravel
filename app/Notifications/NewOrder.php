<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Notifications\Notification;
use Benwilkins\FCM\FcmMessage;


class NewOrder extends Notification
{

    protected $order;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['fcm'];
    }

    /**
     *
     * @param  mixed  $notifiable
     */
    public function toFcm($notifiable)
    {
        $this->uploadRepository = new \App\Repositories\UploadRepository(app());
        $upload = $this->uploadRepository->getByUuid(setting('app_logo', ''));
        $appLogo = asset('img/logo_default.png');
        if ($upload && $upload->hasMedia('default')) {
            $appLogo = $upload->getFirstMediaUrl('default');
        }
        $message = new FcmMessage();
        $notification = [
            'title'        => __('New order #:order_id',['order_id' => $this->order['id']]),
            'body'         => __('You received a new order #:order_id',['order_id' => $this->order['id']]),
            'sound'        => 'default',
            'icon'         => $appLogo ,
        ];
        return $message->content($notification)->data($notification)->priority(FcmMessage::PRIORITY_HIGH);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order['id'],
        ];
    }
}
