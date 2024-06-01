<?php

namespace Tests\Unit;

use App\Quotes\Services\KayneRestService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class QuoteServiceTest extends TestCase
{
    private KayneRestService $service;
    private string $url = 'https://api.kanye.rest/';

    protected function setUp(): void
    {
        parent::setUp();

        Http::fake([
            $this->url => Http::sequence()
                ->push(['quote' => 'Quote 1'])
                ->push(['quote' => 'Quote 2'])
                ->push(['quote' => 'Quote 3'])
                ->push(['quote' => 'Quote 4'])
                ->push(['quote' => 'Quote 5'])
                ->push(['quote' => 'Quote 6'])
                ->push(['quote' => 'Quote 7'])
                ->push(['quote' => 'Quote 8'])
                ->push(['quote' => 'Quote 9'])
                ->push(['quote' => 'Quote 10']),
        ]);

        $this->service = new KayneRestService($this->url);

        Cache::clear();
    }

    public function test_get_method_fetches_and_caches_quotes()
    {
        $quotes = $this->service->get();

        $this->assertCount(5, $quotes);
        $this->assertEquals($quotes->toArray(), [
            ['quote' => 'Quote 1'],
            ['quote' => 'Quote 2'],
            ['quote' => 'Quote 3'],
            ['quote' => 'Quote 4'],
            ['quote' => 'Quote 5'],
        ]);

        // Lets try again, results should stay the same
        $quotes = $this->service->get();

        $this->assertCount(5, $quotes);
        $this->assertEquals($quotes->toArray(), [
            ['quote' => 'Quote 1'],
            ['quote' => 'Quote 2'],
            ['quote' => 'Quote 3'],
            ['quote' => 'Quote 4'],
            ['quote' => 'Quote 5'],
        ]);
    }

    public function test_refresh_method_fetches_new_quotes()
    {
        $quotes = $this->service->get();

        $this->assertCount(5, $quotes);
        $this->assertEquals($quotes->toArray(), [
            ['quote' => 'Quote 1'],
            ['quote' => 'Quote 2'],
            ['quote' => 'Quote 3'],
            ['quote' => 'Quote 4'],
            ['quote' => 'Quote 5'],
        ]);

        // Lets try again, results should change
        $quotes = $this->service->refresh();

        $this->assertCount(5, $quotes);
        $this->assertEquals($quotes->toArray(), [
            ['quote' => 'Quote 6'],
            ['quote' => 'Quote 7'],
            ['quote' => 'Quote 8'],
            ['quote' => 'Quote 9'],
            ['quote' => 'Quote 10'],
        ]);

    }



//    public function test_refresh_method_clears_cache_and_fetches_new_quotes()
//    {
//        // Mock the HTTP request
//        Http::fake([
//            $this->url => Http::response(['quote' => 'New quote'], 200)
//        ]);
//
//        // Ensure the cache forget is called
//        Cache::shouldReceive('forget')
//            ->once()
//            ->with('quotes');
//
//        // Ensure the cache remember is called after forgetting
//        Cache::shouldReceive('remember')
//            ->once()
//            ->with('quotes', 15 * 60, \Closure::class)
//            ->andReturn(collect([['quote' => 'New quote']]));
//
//        // Call the refresh method
//        $quotes = $this->service->refresh();
//
//        // Assert the returned quotes
//        $this->assertCount(5, $quotes);
//        $this->assertEquals('New quote', $quotes->first()['quote']);
//    }
//
//    public function test_get_method_fetches_quotes_when_cache_is_empty()
//    {
//        // Mock the HTTP request
//        Http::fake([
//            $this->url => Http::response(['quote' => 'Test quote'], 200)
//        ]);
//
//        // Ensure the cache is empty before the test
//        Cache::shouldReceive('remember')
//            ->once()
//            ->with('quotes', 15 * 60, \Closure::class)
//            ->andReturn(collect([['quote' => 'Test quote']]));
//
//        // Call the get method
//        $quotes = $this->service->get();
//
//        // Assert the returned quotes
//        $this->assertCount(5, $quotes);
//        $this->assertEquals('Test quote', $quotes->first()['quote']);
//    }
//
//    public function test_get_method_uses_cache_if_available()
//    {
//        // Ensure the cache returns a mocked response
//        Cache::shouldReceive('remember')
//            ->once()
//            ->with('quotes', 15 * 60, \Closure::class)
//            ->andReturn(collect([['quote' => 'Cached quote']]));
//
//        // Call the get method
//        $quotes = $this->service->get();
//
//        // Assert the returned quotes
//        $this->assertCount(5, $quotes);
//        $this->assertEquals('Cached quote', $quotes->first()['quote']);
//    }
}
