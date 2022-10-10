<?php 
return [ 
    
    // 'client_id' => env( 'PAYPAL_CLIENT_ID', '' ),
    'client_id' => "AZReP9ECUiJGoCpw8FwzdivlnwrUBIP0gu_jhlizgYbu-wazb0mj2C3mZxh6LL_Nk5yYu7wSVLibk6Ge",
    // 'secret'    => env( 'PAYPAL_SECRET', '' ),
    'secret' => "EIF-5c_byIFZ_O2_P6TgEDcnsqEZLX6t7N3uP1pRNBCtzZrgSvoatyy9wVySWyfsSN20Io2oFvKYj2cV",
    
    'settings' => array(
        'mode'                   => env( 'PAYPAL_MODE', 'sandbox' ),
        'http.ConnectionTimeOut' => 30,
        'log.LogEnabled'         => true,
        'log.FileName'           => storage_path() . '/logs/paypal.log',
        'log.LogLevel'           => 'ERROR'
    ),
];