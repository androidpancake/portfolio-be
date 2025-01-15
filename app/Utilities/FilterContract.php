<?php

namespace App\Utilities;

interface FilterContract
{
    /**
     * Create a new class instance.
     */
    public function handle($value): void;
}
