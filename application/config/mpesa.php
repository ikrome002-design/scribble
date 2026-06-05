<?php

return [

     //Specify the environment mpesa is running, sandbox or production
     'mpesa_env' => env('MPESA_ENV', 'sandbox'),
    /*-----------------------------------------
    |The App consumer key
    |------------------------------------------
    */
    'consumer_key'   => env('MPESA_CONSUMER_KEY', ''),

    /*-----------------------------------------
    |The App consumer Secret
    |------------------------------------------
    */
    'consumer_secret' => env('MPESA_CONSUMER_SECRET', ''),

    /*-----------------------------------------
    |The paybill number
    |------------------------------------------
    */
    'paybill'         => env('MPESA_PAYBILL', ''),

    /*-----------------------------------------
    |Lipa Na Mpesa Online Shortcode
    |------------------------------------------
    */
    'lipa_na_mpesa'  => env('MPESA_LIPA_NA_MPESA', ''),

    /*-----------------------------------------
    |Lipa Na Mpesa Online Passkey
    |------------------------------------------
    */
    'lipa_na_mpesa_passkey' => env('MPESA_PASSKEY', ''),

    /*-----------------------------------------
    |Initiator Username.
    |------------------------------------------
    */
    'initiator_username' => env('MPESA_INITIATOR_USERNAME', ''),

    /*-----------------------------------------
    |Initiator Password
    |------------------------------------------
    */
    'initiator_password' => env('MPESA_INITIATOR_PASSWORD', ''),

    /*-----------------------------------------
    |Test phone Number
    |------------------------------------------
    */
    'test_msisdn ' => env('MPESA_TEST_MSISDN', ''),

    /*-----------------------------------------
    |Lipa na Mpesa Online callback url
    |------------------------------------------
    */
    'lnmocallback' => env('MPESA_LNMO_CALLBACK_URL', ''),

     /*-----------------------------------------
    |C2B  Validation url
    |------------------------------------------
    */
    'c2b_validate_callback' => env('MPESA_C2B_VALIDATE_CALLBACK_URL', ''),

    /*-----------------------------------------
    |C2B confirmation url
    |------------------------------------------
    */
    'c2b_confirm_callback' => env('MPESA_C2B_CONFIRM_CALLBACK_URL', ''),

    /*-----------------------------------------
    |B2C timeout url
    |------------------------------------------
    */
    'b2c_timeout' => env('MPESA_B2C_TIMEOUT_URL', ''),

    /*-----------------------------------------
    |B2C results url
    |------------------------------------------
    */
    'b2c_result' => env('MPESA_B2C_RESULT_URL', '')

];
