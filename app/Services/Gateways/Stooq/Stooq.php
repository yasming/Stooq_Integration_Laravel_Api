<?php

namespace App\Services\Gateways\Stooq;

use Illuminate\Support\Facades\Http;

class Stooq
{
    private $url;
    public function __construct($parameter)
    {
        $this->url = str_replace('{parameter}', $parameter, config('stooq.url'));
    }

    public function getResults()
    {
        try {
            $response = Http::get($this->url);
            if ($response->successful()) {
                return explode(',',explode(PHP_EOL, $response->body())[1]);
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }
}
