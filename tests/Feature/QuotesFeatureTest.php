<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class QuotesFeatureTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Http::fake([
            'https://api.kanye.rest/' => Http::sequence()
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

        Cache::flush();
    }

    public function test_refresh_endpoint_returns_401_http_response_unauthorised_if_token_not_given_or_valid()
    {
        $response = $this->getJson(route('quotes.refresh'));
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

        $response = $this->getJson(route('quotes.refresh'), ['Authorization' => 'Bearer invalid-token']);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_get_endpoint_returns_401_http_response_unauthorised_if_token_not_given_or_valid()
    {
        $response = $this->getJson(route('quotes.get'));
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

        $response = $this->getJson(route('quotes.get'), ['Authorization' => 'Bearer invalid-token']);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_get_endpoint_returns_200_http_response_if_authenticated()
    {
        $response = $this->getJson(route('quotes.get'), [
            'Authorization' => 'Bearer ' . config('app.api_token'),
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_refresh_endpoint_returns_200_http_response_if_authenticated()
    {
        $response = $this->getJson(route('quotes.refresh'), [
            'Authorization' => 'Bearer ' . config('app.api_token'),
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_can_get_quotes_and_results_are_cached()
    {
        $response = $this->getJson(route('quotes.get'), [
            'Authorization' => 'Bearer ' . config('app.api_token'),
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(5, 'data')
            ->assertJsonFragment(['data' => [
                'Quote 1',
                'Quote 2',
                'Quote 3',
                'Quote 4',
                'Quote 5'
            ]]);

        // Lets get the quotes AGAIN
        // But they should be the same as before.

        $response = $this->getJson(route('quotes.get'), [
            'Authorization' => 'Bearer ' . config('app.api_token'),
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(5, 'data')
            ->assertJsonFragment(['data' => [
                'Quote 1',
                'Quote 2',
                'Quote 3',
                'Quote 4',
                'Quote 5'
            ]]);
    }

    public function test_can_refresh_and_output_new_quotes()
    {
        $response = $this->getJson(route('quotes.get'), [
            'Authorization' => 'Bearer ' . config('app.api_token'),
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(5, 'data')
            ->assertJsonFragment(['data' => [
                'Quote 1',
                'Quote 2',
                'Quote 3',
                'Quote 4',
                'Quote 5'
            ]]);

        $response = $this->getJson(route('quotes.refresh'), [
            'Authorization' => 'Bearer ' . config('app.api_token'),
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(5, 'data')
            ->assertJsonFragment(['data' => [
                'Quote 6',
                'Quote 7',
                'Quote 8',
                'Quote 9',
                'Quote 10'
            ]]);
    }
}
