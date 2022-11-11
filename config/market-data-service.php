<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Market data Service
    |--------------------------------------------------------------------------
    |
    | This option controls the default data service to use for getting
    | historic and realtime market data.
    |
    */
    'default' => env('MARKET_DATA_SERVICE', 'polygon_io'),

    'polygon_io' => [
        'key' => env('POLYGON_IO_KEY'),
    ],
];
