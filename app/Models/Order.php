<?php

namespace App\Models;

use App\Notifications\NewOrder;
use App\Notifications\OrderStatusChanged;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

/**
 * Class Order
 * @package App\Models
 * @version July 12, 2022, 12:01 pm UTC
 *
 * @property string $mid
 * @property integer $user_id
 * @property integer $courier_id
 * @property string $pickup_location
 * @property string $pickup_location_data
 * @property boolean $save_pickup_location_for_next_order
 * @property string $delivery_locations_data
 * @property boolean $need_return_to_pickup_location
 * @property number $distance
 * @property number $courier_value
 * @property number $app_value
 * @property number $total_value
 * @property string $customer_observation
 * @property integer $offline_payment_method_id
 * @property string $payment_gateway
 * @property string $gateway_id
 * @property string $payment_status
 * @property string|\Carbon\Carbon $payment_status_date
 * @property string $order_status
 * @property string|\Carbon\Carbon $order_status_date
 * @property string $status_observation
 */
class Order extends Model
{

    use HasFactory;

    public $table = 'orders';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public static function boot()
    {
        parent::boot();

        static::created(function (Order $model) {
            if(setting('enable_firebase')){
                if($model->order_status != 'waiting'){
                    \Illuminate\Support\Facades\Notification::sendNow([$model->courier()->with('user')->first()->user], new NewOrder($model));
                }
            }
        });

        static::updated(function (Order $model) {
            //TODO: notify the customer or the courier acconding to who made the change and the status
            if(setting('enable_firebase')) {
                if ($model->order_status != $model->getOriginal('order_status')) {
                    $isCustomer = auth()->user()->id == $model->user_id;

                    if ($isCustomer) {
                        //notify driver
                        if ($model->getOriginal('order_status') == 'waiting' && $model->order_status != 'cancelled') {
                            \Illuminate\Support\Facades\Notification::sendNow([$model->courier()->with('user')->first()->user], new NewOrder($model));
                        } else {
                            \Illuminate\Support\Facades\Notification::sendNow([$model->courier()->with('user')->first()->user], new OrderStatusChanged($model));
                        }
                    } else {
                        //notify customer
                        \Illuminate\Support\Facades\Notification::sendNow([$model->user()->first()], new OrderStatusChanged($model));
                    }
                }
            }
        });
    }

    public $fillable = [
        'user_id',
        'courier_id',
        'pickup_location',
        'pickup_location_data',
        'save_pickup_location_for_next_order',
        'delivery_locations_data',
        'need_return_to_pickup_location',
        'distance',
        'courier_value',
        'app_value',
        'total_value',
        'customer_observation',
        'offline_payment_method_id',
        'payment_gateway',
        'gateway_id',
        'payment_status',
        'payment_status_date',
        'order_status',
        'order_status_date',
        'status_observation'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'courier_id' => 'integer',
        'pickup_location' => 'string',
        'pickup_location_data' => 'string',
        'save_pickup_location_for_next_order' => 'boolean',
        'delivery_locations_data' => 'string',
        'need_return_to_pickup_location' => 'boolean',
        'distance' => 'decimal:3',
        'courier_value' => 'decimal:2',
        'app_value' => 'decimal:2',
        'total_value' => 'decimal:2',
        'customer_observation' => 'string',
        'offline_payment_method_id' => 'integer',
        'payment_gateway' => 'string',
        'gateway_id' => 'string',
        'payment_status' => 'string',
        'payment_status_date' => 'datetime',
        'order_status' => 'string',
        'order_status_date' => 'datetime',
        'status_observation' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_id' => 'required|integer',
        'courier_id' => 'required|integer',
        'distance' => 'required|numeric',
        'courier_value' => 'required|numeric',
        'app_value' => 'required|numeric',
        'customer_observation' => 'nullable|string',
        'payment_status' => 'required|string',
        'order_status' => 'required|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    protected $appends = [
        'user',
        'courier',
    ];

    public function getUserAttribute()
    {
        return $this->user()->first();
    }

    public function getCourierAttribute()
    {
        return $this->courier()->first();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function courier()
    {
        return $this->belongsTo(Courier::class, 'courier_id');
    }

    public function offlinePaymentMethod(){
        return $this->belongsTo(OfflinePaymentMethod::class, 'offline_payment_method_id');
    }

}
