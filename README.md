## SocialTech Test Task
Requirements: PHP 7+, Composer.
Notice: some things were intentionally simplified or omitted, i.e. validation, error handling & logging, unit tests are written more like examples.

### Installation
```
git clone --recursive git@github.com:holyspecter/socialtech-test-task.git
cd socialtech-test-task
composer install
```

### Run web server
For simplicity built-in PHP server is used here and it'll be available on http://localhost:8001/ after running:
```
composer start-server
```

### Run tests
```
composer test
```

### API usage
#### Registration
POST http://localhost:8001/users
```
{
	"first_name": "John",
	"last_name": "Snow",
	"nick_name": "js",
	"age": 25,
	"password": "123"
}
```

#### Login
POST http://localhost:8001/login
```
{
	"nick_name": "js",
	"password": "123"
}
```

Response:
```
{"token": "jwt_token..."}
```
Token retrieved from this endpoint should be used for later requests in header, like `Authorization: Bearer <jwt_token>`.

#### Track events
POST http://localhost:8001/events
```
{
	"source_label": "test_event",
	"date_created": "2020-09-06"
}
```
Notice: `user_id` for event is taken from JWT if it's present, otherwise new anonymous user would be created and token would be returned in response.