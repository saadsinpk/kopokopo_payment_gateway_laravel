<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

/**
 * Class Courier
 * @package App\Models
 * @version July 7, 2022, 4:45 pm UTC
 *
 * @property boolean $active
 * @property string|\Carbon\Carbon $last_location_at
 * @property number $lat
 * @property number $lng
 * @property boolean $using_app_pricing
 * @property number $base_price
 * @property number $base_distance
 * @property number $additional_distance_pricing
 * @property number $additional_stop_tax
 */
class Courier extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'couriers';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    public static function boot()
    {
        parent::boot();

        static::creating(function (Courier $model) {

            //generate slug if it's not generated yet
            if (empty($model->slug)) {
                $slug = Str::slug($model->user->name);
                $originalSlug = $slug;
                $c = 0;
                do {
                    $hasMarketSameSlug = self::where('slug', $slug)->exists();
                    if ($hasMarketSameSlug) {
                        $c++;
                        $slug = $originalSlug . "-" . $c;
                        $founded = true;
                    } else {
                        $founded = false;
                    }
                } while ($founded);
                $model->slug = $slug;
            }
        });
    }

    public $fillable = [
        'active',
        "user_id",
        'last_location_at',
        'lat',
        'lng',
        "slug",
        'using_app_pricing',
        'base_price',
        'base_distance',
        'additional_distance_pricing',
        'return_distance_pricing',
        'additional_stop_tax'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'active' => 'boolean',
        'last_location_at' => 'datetime',
        'lat' => 'decimal:8',
        'lng' => 'decimal:8',
        'using_app_pricing' => 'boolean',
        'base_price' => 'decimal:2',
        'base_distance' => 'decimal:2',
        'additional_distance_pricing' => 'decimal:2',
        'additional_stop_tax' => 'decimal:2'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'active' => 'required|boolean',
        'last_location_at' => 'nullable',
        'lat' => 'nullable|numeric',
        'lng' => 'nullable|numeric',
        'using_app_pricing' => 'required|boolean',
        'base_price' => 'nullable|numeric',
        'base_distance' => 'nullable|numeric',
        'additional_distance_pricing' => 'nullable|numeric',
        'additional_stop_tax' => 'nullable|numeric',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    protected $appends = [
        'link',
    ];

    public function getLinkAttribute()
    {
        return url("/{$this->slug}");
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
