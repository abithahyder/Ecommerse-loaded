<?php

    return [


        'paytm-wallet' => [
            'env'              => 'local', // values : (local | production)
            'merchant_id'      => env( 'YOUR_MERCHANT_ID' ),
            'merchant_key'     => env( 'YOUR_MERCHANT_KEY' ),
            'merchant_website' => env( 'YOUR_WEBSITE' ),
            'channel'          => env( 'YOUR_CHANNEL' ),
            'industry_type'    => env( 'YOUR_INDUSTRY_TYPE' ),
        ],


    ];