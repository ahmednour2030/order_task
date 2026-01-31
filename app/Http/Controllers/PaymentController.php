<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Models\Order;
use App\Models\Payment as PaymentModel;
use App\Services\Payments\Facades\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request, Order $order)
    {
        $payments = PaymentModel::where('order_id', $order->id)->get();
        return $this->apiResponse('Payments retrieved successfully', $payments);
    }

    /**
     * @param PaymentRequest $request
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function process(PaymentRequest $request, Order $order)
    {
        if ($order->successfulPayment()->exists()) {
            return $this->apiResponse(
                'This order has already been paid.',
                null,
                null,
                409
            );
        }

        try {
            $paymentData = Payment::gateway($request->post('method'))->charge($order->total_amount);

            $payment =  PaymentModel::create([
                'order_id' => $order->id,
                'payment_id' => $paymentData['payment_id'],
                'amount' => $order->total_amount,
                'status' => $paymentData['status'],
                'method' => $paymentData['method']
            ]);

            return $this->apiResponse('Payment processed successfully', $payment);
        }catch (\Exception $exception){
            return $this->apiResponse(
                'Payment failed: ' . $exception->getMessage(),
                null,
                null,
                422
            );
        }

    }
}
