<?php

namespace App\Repositories;

use App\Models\OfflinePaymentMethod;
use App\Repositories\BaseRepository;

/**
 * Class OfflinePaymentMethodRepository
 * @package App\Repositories
 * @version July 12, 2022, 12:02 pm UTC
*/

class OfflinePaymentMethodRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'icon',
        'name'
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
        return OfflinePaymentMethod::class;
    }
}
