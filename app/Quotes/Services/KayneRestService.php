<?php

namespace App\Quotes\Services;

use App\Quotes\Contracts\QuoteContract;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class KayneRestService implements QuoteContract
{
    private int     $cacheDuration = 15 * 60;
    private string  $cacheKey = 'quotes';

    public function __construct(public string $url)
    {
        //
    }

    public function get(int $numQuotes = 5): \Illuminate\Support\Collection
    {
        return Cache::remember($this->cacheKey, $this->cacheDuration, function() use ($numQuotes) {
            $responses = [];

            for ($i = 0; $i < $numQuotes; $i++) {
                $responses[] = Http::get($this->url)->json();
            }

            return collect($responses);
        });
    }

    public function refresh(): \Illuminate\Support\Collection
    {
        Cache::forget($this->cacheKey);

        return $this->get();
    }
}
