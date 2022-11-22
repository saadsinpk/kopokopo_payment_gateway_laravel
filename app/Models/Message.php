<?php

namespace App\Models;

use App\Notifications\NewMessage;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Message extends Model implements HasMedia
{
    use InteractsWithMedia {
        getFirstMediaUrl as protected getFirstMediaUrlTrait;
    }

    public static function boot()
    {
        parent::boot();

        static::created(function (Message $model) {
            if (setting('enable_firebase')) {
                if ($model->sender_id != $model->order->user_id) {
                    \Illuminate\Support\Facades\Notification::sendNow([$model->order->user], new NewMessage($model));
                } else {
                    \Illuminate\Support\Facades\Notification::sendNow([$model->order->courier->user], new NewMessage($model));
                }
            }
        });
    }


    public $fillable = [
        'order_id',
        'sender_id',
        'message',
    ];

    protected $appends = [
        'has_media',
        'sender',
    ];

    /**
     * @param Media|null $media
     * @throws \Spatie\Image\Exceptions\InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Manipulations::FIT_CROP, 250, 250)
            ->width(250)
            ->height(250);
    }

    /**
     * Add Media to api results
     * @return bool
     */
    public function getHasMediaAttribute()
    {
        return $this->hasMedia('file') ? true : false;
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class);
    }

    public function getSenderAttribute()
    {
        return $this->sender()->first();
    }
}
