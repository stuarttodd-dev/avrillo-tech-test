<?php

namespace App\Quotes\Managers;

use App\Quotes\Services\KayneRestService;
use Illuminate\Support\Manager;

class QuoteManager extends Manager {

    public function getDefaultDriver()
    {
        return 'KayneRest';
    }

    public function createKayneRestDriver()
    {
        return new KayneRestService('https://api.kanye.rest/');
    }

}