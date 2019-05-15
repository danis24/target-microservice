<?php

namespace App\Services\Targets;

use Illuminate\Contracts\Support\Arrayable;
use Uuid;

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

    public function read($id)
    {
        return $this->newTarget()->findByUuid($id);
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
}
