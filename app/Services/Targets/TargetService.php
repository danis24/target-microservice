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

    public function add($payload)
    {
        return $this->newTarget()->create($payload);
    }

    public function showByEmail($email)
    {
         return $this->newTarget()->where('email', $email)->paginate();
    }
}
