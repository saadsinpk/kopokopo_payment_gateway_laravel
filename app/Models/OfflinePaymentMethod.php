<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Class OfflinePaymentMethod
 * @package App\Models
 * @version July 12, 2022, 12:02 pm UTC
 *
 * @property string $icon
 * @property string $name
 */
class OfflinePaymentMethod extends Model implements HasMedia
{
    use InteractsWithMedia {
        getFirstMediaUrl as protected getFirstMediaUrlTrait;
    }
    use SoftDeletes;
    use HasFactory;

    public $table = 'offline_payment_methods';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'name'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'image' => 'nullable|mimes:jpeg,png,jpg,gif,svg,webp|max:512',
        'name' => 'required|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    protected $appends = [
        'has_media',
    ];
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('icon')
            ->width(50)
            ->height(50);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('default')
            ->useFallbackUrl(asset('/images/image_default.png'))
            ->useFallbackPath(asset('/images/image_default.png'));
    }

    /**
     * to generate media url in case of fallback will
     * return the file type icon
     * @param string $conversion
     * @return string url
     */
    public function getFirstMediaUrl($collectionName = 'default', $conversion = '')
    {
        $url = $this->getFirstMediaUrlTrait($collectionName);

        $array = explode('.', $url);
        $extension = strtolower(end($array));
        return asset($this->getFirstMediaUrlTrait($collectionName, $conversion));
    }

    /**
     * Add Media to api results
     * @return bool
     */
    public function getHasMediaAttribute()
    {
        return $this->hasMedia('default') ? true : false;
    }

}
