<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Class Upload
 * @package App\Models
 * @version March 23, 2022, 1:02 pm UTC
 *
 * @property string $uuid
 */
class Upload extends Model implements HasMedia
{
    use InteractsWithMedia {
        getFirstMediaUrl as protected getFirstMediaUrlTrait;
    }
    use HasFactory;

    public $table = 'uploads';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'uuid'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'uuid' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'uuid' => 'required|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    protected $appends = [
        'has_media',
    ];


    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(250)
            ->height(250);
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
