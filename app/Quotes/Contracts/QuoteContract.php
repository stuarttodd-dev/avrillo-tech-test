<?php

namespace App\Quotes\Contracts;

interface QuoteContract
{
    public function get(): \Illuminate\Support\Collection;
    public function refresh(): \Illuminate\Support\Collection;
}

