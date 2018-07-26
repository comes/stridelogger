# LICENSE

Do what the fuck you want to do with this stupid peace of shit

# Description
can be used in laravel as a log endpoint. you have to edit your `config/logging.php` config file.

```php
    'stride' => [
        'driver' => 'monolog',
        'level' => 'debug',
        'handler' => \Comes\Logging\StrideLogHandler::class,
        'handler_with' => [
            'token' => env('STRIDE_TOKEN','<APP TOKEN FOR STRIDE>'),
            'room' => env('STRIDE_ROOM_ID', '<STRIDE CONVERSATION ID>'),
            'cloudid' => env('STRIDE_CLOUD_ID', '<STRIDE CLOUD ID>'),
            'name' => env('APP_NAME', '<APP IDENTIFIER IN MESSAGE>'),
            'level' => Monolog\Logger::DEBUG
        ]
    ],
```
