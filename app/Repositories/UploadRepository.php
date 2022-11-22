<?php

namespace App\Repositories;


use App\Models\Upload;
use App\Repositories\BaseRepository;

/**
 * Class UploadRepository
 * @package App\Repositories
 * @version March 23, 2022, 1:02 pm UTC
*/

class UploadRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'uuid'
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
        return Upload::class;
    }

    public function getByUuid($uuid = '')
    {
        return $this->model()::where('uuid', $uuid)->first();
    }

    /**
     * @param $uuid
     */
    public function clear($uuid)
    {
        $uploadModel = $this->getByUuid($uuid);
        return $uploadModel->delete();
    }

    /**
     * clear all uploaded cache
     */
    public function clearAll()
    {
        Upload::query()->where('id', '>', 0)->delete();
        Media::query()->where('model_type', '=', 'App\Models\Upload')->delete();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function allMedia($collection = null)
    {
        $medias = Media::where('model_type', '=', 'App\Models\Upload');
        if ($collection) {
            $medias = $medias->where('collection_name', $collection);
        }
        $medias = $medias->orderBy('id','desc')->get();
        return $medias;
    }


    public function collectionsNames()
    {
        $medias = Media::all('collection_name')->pluck('collection_name','collection_name')->map(function ($c) {
            return ['value' => $c,
                'title' => title_case(preg_replace('/_/', ' ', $c))
            ];
        })->unique();
        unset($medias['default']);
        $medias->prepend(['value' => 'default', 'title' => 'Default'],'default');
        return $medias;
    }

}
