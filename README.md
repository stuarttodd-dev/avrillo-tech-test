# Avrillo Technical Test

### Environment Setup
1) Run Docker Engine.
2) Clone this repository
3) Navigate to root
4) Copy the .env.example and create a new .env file (`cp .env.example .env`)
5) `composer update`
6) `./vendor/bin/sail up -d`
7) `./vendor/bin/sail artisan migrate`

### Requirements
The challenge will contain a few core features most applications have. That includes connecting to an API, basic MVC using Laravel, exposing an API, and finally, tests.

The API we want you to connect to is https://kanye.rest/

**The application should have the following features**
- A rest API that shows 5 random Kayne West quotes.
- There should be an endpoint to refresh the quotes and fetch the next 5 random quotes.
- Authentication for these APIs should be done with an API token, not using any package.
- The above features are tested with Feature tests.
- Provide a README on how we can set up and test the application.

**Nice to have's.**
- The above features are tested with Unit tests.
- Implementation of API using Laravel Manager Design Pattern.
- Making third-party API response quick by cache.

### Tests
To run feature and unit tests.
```
php artisan test
```

### Auth
Typically would use Laravel Sanctum, however for the purpose of the test I've added Middleware which checks if the given **bearer token** matches **API_TOKEN** from the env file.

### Endpoints

**GET /**
- **Description:** Retrieve 5 random Kanye West quotes. Results are cached for 15 minutes unless refreshed.
- **URL:** `http://127.0.0.1:8888/`
- **Method:** GET
- **Authentication:** Requires API token in the `Authorization` header as a Bearer token.
- **Response:** Returns a JSON array with 5 random quotes.
- **Example Request:**
```sh
  curl -H "Authorization: Bearer YOUR_API_TOKEN" http://127.0.0.1:8888/quotes
```

**GET /refresh**
- **Description:** Refresh cache and retrieve 5 new random Kanye West quotes.
- **URL:** `http://127.0.0.1:8888/refresh`
- **Method:** GET
- **Authentication:** Requires API token in the `Authorization` header as a Bearer token.
- **Response:** Returns a JSON array with 5 random quotes.
- **Example Request:**
```sh
  curl -H "Authorization: Bearer YOUR_API_TOKEN" http://127.0.0.1:8888/refresh
```
