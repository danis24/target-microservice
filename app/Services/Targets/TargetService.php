<?php

namespace App\Services\Targets;

use Illuminate\Contracts\Support\Arrayable;
use Uuid;
use DB;

class TargetService
{
    private function newTarget()
    {
        return new Target;
    }

    public function browse()
    {
        return $this->newTarget()->paginate();
    }

    public function getGeoLocation()
    {
        return DB::table("targets")->select('country', DB::raw('count(*) as total'))->groupBy('country')->get();
    }

    public function getByScannerId($id)
    {
        return $this->newTarget()->where("scanner_id", $id)->first();
    }

    public function read($id)
    {
        return $this->newTarget()->findByUuid($id);
    }

    public function filter($payload)
    {
        $target = (new Target)->newQuery();
        $target = $target->newQuery();
        foreach ($payload->get('filter') as $key => $value) {
            $fields = ['email', 'url', 'country', 'countryCode', 'region', 'regionName', 'city', 'zip', 'latitude', 'longitude', 'timeZone', 'isp', 'org', 'as', 'query'
            ];
            if (in_array($key, $fields)) {
                $target->where($key, $value)->orderBy('created_at', 'desc');
            }
        }
        return $target->paginate(10);
    }

    public function checkExist($data = [])
    {
        return $this->newTarget()->where($data);
    }

    public function add($payload)
    {
        return $this->newTarget()->create($payload);
    }

    public function showByEmail($email)
    {
        return $this->newTarget()->where('email', $email)->paginate();
    }

    public function update($id, $payload)
    {
        $target = $this->read($id);
        $target->fill($payload)->save();
        return $target;
    }

    public function delete($id)
    {
        return $this->newTarget()->destroyByUuid($id);
    }
}
