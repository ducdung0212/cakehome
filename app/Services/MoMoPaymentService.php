<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MoMoPaymentService
{
    protected $partnerCode;
    protected $accessKey;
    protected $secretKey;
    protected $endpoint;

    public function __construct()
    {
        $this->partnerCode = config('payment.momo.partner_code');
        $this->accessKey = config('payment.momo.access_key');
        $this->secretKey = config('payment.momo.secret_key');
        $this->endpoint = config('payment.momo.endpoint');
    }

    /**
     * Tạo payment request gửi đến MoMo
     */
    public function createPayment($orderId, $amount, $orderInfo)
    {
        $requestId = time() . "";
        $redirectUrl = url(config('payment.momo.return_url'));
        $ipnUrl = url(config('payment.momo.notify_url'));
        $extraData = "";
        $requestType = "payWithATM";

        // Tạo raw signature theo v2 API (accessKey→amount→extraData→ipnUrl→orderId→orderInfo→partnerCode→redirectUrl→requestId→requestType)
        $rawHash = "accessKey=" . $this->accessKey .
            "&amount=" . $amount .
            "&extraData=" . $extraData .
            "&ipnUrl=" . $ipnUrl .
            "&orderId=" . $orderId .
            "&orderInfo=" . $orderInfo .
            "&partnerCode=" . $this->partnerCode .
            "&redirectUrl=" . $redirectUrl .
            "&requestId=" . $requestId .
            "&requestType=" . $requestType;

        $signature = hash_hmac("sha256", $rawHash, $this->secretKey);

        $data = [
            'partnerCode' => $this->partnerCode,
            'partnerName' => 'CakeShop',
            'storeId' => 'CakeStore01',
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        ];

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->post($this->endpoint, $data);

            $result = $response->json();

            Log::info('MoMo Payment Request', [
                'order_id' => $orderId,
                'request' => $data,
                'response' => $result
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::error('MoMo Payment Error', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);

            return [
                'resultCode' => 999,
                'message' => 'Lỗi kết nối đến MoMo: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Xác thực callback từ MoMo
     */
    public function verifySignature($data)
    {
        // V2 callback format theo thứ tự alphabet: accessKey→amount→extraData→message→orderId→orderInfo→orderType→partnerCode→payType→requestId→responseTime→resultCode→transId
        $rawHash = "accessKey=" . $this->accessKey .
            "&amount=" . $data['amount'] .
            "&extraData=" . ($data['extraData'] ?? '') .
            "&message=" . $data['message'] .
            "&orderId=" . $data['orderId'] .
            "&orderInfo=" . $data['orderInfo'] .
            "&orderType=" . $data['orderType'] .
            "&partnerCode=" . $data['partnerCode'] .
            "&payType=" . $data['payType'] .
            "&requestId=" . $data['requestId'] .
            "&responseTime=" . $data['responseTime'] .
            "&resultCode=" . $data['resultCode'] .
            "&transId=" . $data['transId'];

        $signature = hash_hmac("sha256", $rawHash, $this->secretKey);

        return $signature === $data['signature'];
    }

    /**
     * Kiểm tra trạng thái thanh toán
     */
    public function checkPaymentStatus($requestId, $orderId)
    {
        // Theo basic.example/paymomo/query_transaction.php
        $requestType = "transactionStatus";

        $rawHash = "partnerCode=" . $this->partnerCode .
            "&accessKey=" . $this->accessKey .
            "&requestId=" . $requestId .
            "&orderId=" . $orderId .
            "&requestType=" . $requestType;

        $signature = hash_hmac("sha256", $rawHash, $this->secretKey);

        $data = [
            'partnerCode' => $this->partnerCode,
            'accessKey' => $this->accessKey,
            'requestId' => $requestId,
            'orderId' => $orderId,
            'requestType' => $requestType,
            'signature' => $signature
        ];

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->post($this->endpoint, $data);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('MoMo Check Status Error', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);

            return null;
        }
    }
}
