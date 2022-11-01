<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Benwilkins\FCM\FcmMessage;

class OrderStatusChanged extends Notification
{
    use Queueable;

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
        $notification =[
            'title'        => __('Order #:order_id status changed for :status',['order_id' => $this->order['id'],'status' => trans('general.order_status_list.'.$this->order['order_status'])]),
            'body'         => __('Order status changed for :status',['status' => trans('general.order_status_list.'.$this->order['order_status'])]),
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
