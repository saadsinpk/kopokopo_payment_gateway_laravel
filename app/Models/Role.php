<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Role
 * @package App\Models
 * @version July 8, 2022, 2:56 pm UTC
 *
 * @property \App\Models\ModelHasRole $modelHasRole
 * @property \Illuminate\Database\Eloquent\Collection $permissions
 * @property string $name
 * @property boolean $default
 * @property string $guard_name
 */
class Role extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'roles';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'name',
        'default',
        'guard_name'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'default' => 'boolean',
        'guard_name' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|string|max:255',
        'default' => 'required|boolean',
        'guard_name' => 'required|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     **/
    public function modelHasRole()
    {
        return $this->hasOne(\App\Models\ModelHasRole::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function permissions()
    {
        return $this->belongsToMany(\App\Models\Permission::class, 'role_has_permissions');
    }
}
