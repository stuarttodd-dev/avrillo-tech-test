<?php

namespace App\Http\Controllers;

use App\Quotes\Facades\Quotes;
use App\Http\Resources\QuoteResource;
use Illuminate\Http\Request;

class QuotesController extends Controller
{
    public function __construct(private Quotes $quotes)
    {

    }

    public function get(Request $request)
    {
        return QuoteResource::collection($this->quotes::get());
    }

    public function refresh(Request $request)
    {
        return QuoteResource::collection($this->quotes::refresh());
    }
}
