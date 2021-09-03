<?php

namespace App\Http\Controllers;

use Braintree;
use Braintree\Gateway;
use Illuminate\Http\Request;

class BraintreePaymentController extends Controller
{
    protected Gateway $gateway;

    public function __construct()
    {
        $config = new Braintree\Configuration([
            'environment' => 'sandbox',
            'merchantId' => 'zx2j8r7z87g78ps6',
            'publicKey' => 'r4ypnq35bt68vcpy',
            'privateKey' => 'b32fa193463532ccf5e9886a0da6594c',
        ]);

        $this->gateway = new Braintree\Gateway($config);
    }

    public function index()
    {
        $clientToken = $this->gateway->clientToken()->generate();

        return view('braintreePayment', compact('clientToken'));
    }

    public function processPayment(Request $request)
    {
        $payment = $this->gateway->transaction()->sale([
            'amount' => '100.00',
            'paymentMethodNonce' => "fake-valid-visa-nonce",
            'deviceData' => $request->input('client_device_data'),
            'options' => [
                'submitForSettlement' => True
            ]
        ]);

        return redirect()->route('braintree')->with('paymentSuccesful', $payment->success);
    }
}
