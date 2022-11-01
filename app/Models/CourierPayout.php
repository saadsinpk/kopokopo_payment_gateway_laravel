<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class CourierPayout
 * @package App\Models
 * @version July 26, 2022, 12:11 pm UTC
 *
 * @property integer $courier_id
 * @property string $method
 * @property number $amount
 * @property string $date
 */
class CourierPayout extends Model
{

    use HasFactory;

    public $table = 'courier_payouts';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    public $fillable = [
        'courier_id',
        'method',
        'amount',
        'date'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'courier_id' => 'integer',
        'method' => 'string',
        'amount' => 'decimal:2',
        'date' => 'date'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'courier_id' => 'required',
        'method' => 'required|string|max:255',
        'amount' => 'required|numeric',
        'date' => 'required',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    public function courier()
    {
        return $this->belongsTo(Courier::class);
    }

}
