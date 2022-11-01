<?php

namespace App\Repositories;

use App\Models\CourierPayout;
use App\Repositories\BaseRepository;

/**
 * Class CourierPayoutRepository
 * @package App\Repositories
 * @version July 26, 2022, 12:11 pm UTC
*/

class CourierPayoutRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'courier_id',
        'method',
        'amount',
        'date'
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
        return CourierPayout::class;
    }
}
