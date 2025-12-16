<?php

return [
    /*
    |--------------------------------------------------------------------------
    | MoMo Payment Configuration
    |--------------------------------------------------------------------------
    |
    | Cấu hình thanh toán MoMo
    |
    */
    'momo' => [
        'partner_code' => env('MOMO_PARTNER_CODE', ''),
        'access_key' => env('MOMO_ACCESS_KEY', ''),
        'secret_key' => env('MOMO_SECRET_KEY', ''),
        'endpoint' => env('MOMO_ENDPOINT', 'https://test-payment.momo.vn/gw_payment/transactionProcessor'),
        'return_url' => env('MOMO_RETURN_URL', '/payment/momo/return'),
        'notify_url' => env('MOMO_NOTIFY_URL', '/payment/momo/notify'),
    ],

    /*
    |--------------------------------------------------------------------------
    | VNPay Payment Configuration (Dự phòng cho tương lai)
    |--------------------------------------------------------------------------
    */
    'vnpay' => [
        'tmn_code' => env('VNPAY_TMN_CODE', ''),
        'hash_secret' => env('VNPAY_HASH_SECRET', ''),
        'url' => env('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),
        'return_url' => env('VNPAY_RETURN_URL', '/payment/vnpay/return'),
    ],
];
