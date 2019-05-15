<?php

namespace App\Services\Targets;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Services\UUIDEntity;
use App\Presenters\JsonApiPresenterable as Presenterable;
use Uuid;

class Target extends Model implements AuthenticatableContract, AuthorizableContract, Presenterable
{
    use Authenticatable, Authorizable, SoftDeletes, UUIDEntity;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'email', 'ip', 'url', 'city', 'country', 'countryCode', 'isp', 'latitude', 'longitude', 'org', 'regionName', 'timeZone', 'zip', 'tokenSite', 'status', 'scanner_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */

    protected $casts = [
        "id" => "uuid",
    ];


    /**
     * @{inheritDoc}
     */
    public function transform()
    {
        $transformed = $this->toArray();
        foreach ($this->getUuidAttributeNames() as $uuidAttributeName) {
            $value = $this->getAttribute($uuidAttributeName);
            $transformed[$uuidAttributeName] = Uuid::import($value)->string;
        }
        return $transformed;
    }


    /**
     * @{inheritDoc}
     */
    public function entityType()
    {
        return "targets";
    }
}
