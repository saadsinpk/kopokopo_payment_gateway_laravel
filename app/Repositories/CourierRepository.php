<?php

namespace App\Repositories;

use App\Models\Courier;
use App\Repositories\BaseRepository;

/**
 * Class CourierRepository
 * @package App\Repositories
 * @version July 12, 2022, 12:19 pm UTC
*/

class CourierRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'active',
        'last_location_at',
        'lat',
        'lng',
        'using_app_pricing',
        'base_price',
        'base_distance',
        'additional_distance_pricing',
        'return_distance_pricing',
        'additional_stop_tax'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Courier::class;
    }

    public function getCouriersNearOf($lat,$lng,$max=10){
        if(setting('distance_unit','mi') == 'mi'){
            $multiplyFactor = '69.0';
        }else{
            $multiplyFactor = '111.1111';
        }
        $distanceQuery = "(".$multiplyFactor." *
                    DEGREES(ACOS(LEAST(1.0, COS(RADIANS(couriers.lat))
                    * COS(RADIANS(" . $lat . "))
                    * COS(RADIANS(couriers.lng) - RADIANS(" . $lng . "))
                    + SIN(RADIANS(couriers.lat))
                    * SIN(RADIANS(" . $lat . "))))))";

        return Courier::selectRaw('couriers.*,'.$distanceQuery.' as distance,
                    (SELECT COUNT(o.id) FROM orders o
                    WHERE o.order_status = "completed" AND
                    o.courier_id = couriers.id
                    ) as orders_count')->where('couriers.active', true)
            ->with('user')
            ->whereNotNull('couriers.lat')
            ->whereNotNull('couriers.lng')
            ->whereRaw($distanceQuery.' < '.setting('maximum_allowed_distance',10))
            ->orderByRaw($distanceQuery.' ASC')
            ->limit($max)
            ->get();
    }
}
