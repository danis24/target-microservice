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

    public function read(Arrayable $payload)
    {
        return $this->newTarget()->create($payload->toArray());
    }

    public function add($payload)
    {
        return $this->newTarget()->create($payload);
    }
}
