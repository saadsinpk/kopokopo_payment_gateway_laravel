<?php

namespace App\Notifications;

use App\Models\Message;
use Illuminate\Notifications\Notification;
use Benwilkins\FCM\FcmMessage;


class NewMessage extends Notification
{

    protected $message;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
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
        $fcmMessage = new FcmMessage();
        $notification = [
            'title'        => __('New Message in order #:order_id',['order_id' => $this->message['order_id']]),
            'body'         => $this->message->message,
            'sound'        => 'default',
            'image'        => $this->message->getFirstMediaUrl('file'),
            'icon'         => $appLogo,
        ];

        return $fcmMessage->content($notification)->data($notification)->priority(FcmMessage::PRIORITY_HIGH);
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
            'order_id' => $this->message['order_id'],
            'sender_id' => $this->message['sender_id'],
            'message' => $this->message['message'],
        ];
    }
}
