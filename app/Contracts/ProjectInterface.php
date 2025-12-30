<?php

/**
 * Planning
 *
 */

namespace App\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface ProjectInterface extends BaseInterface
{
    public function findByName(string $name): Collection;
}