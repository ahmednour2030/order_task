<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $orders = Order::when($request->status, fn($q) => $q->where('status', $request->status))
            ->where('user_id', auth('api')->id())
            ->simplePaginate(10);

        return $this->apiResponse('Orders retrieved successfully', $orders);
    }

    /**
     * @param StoreOrderRequest $request
     * @param OrderService $orderService
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(
        StoreOrderRequest $request,
        OrderService $orderService
    ) {
        $order = $orderService->create(
            $request->validated(),
            auth('api')->id()
        );

        return $this->apiResponse('Order created successfully', $order);
    }

    public function update(
        UpdateOrderRequest $request,
        Order $order,
        OrderService $orderService
    ) {
        try {
            $order = $orderService->update(
                $order,
                $request->validated()
            );
            return $this->apiResponse('Order updated successfully', $order);

        } catch (\Exception $e) {
            return $this->apiResponse($e->getMessage(), null , null, 403);
        }
    }

    public function destroy(Order $order, OrderService $orderService)
    {
        try {
            $orderService->delete($order);
            return $this->apiResponse('Order deleted successfully');

        } catch (\Throwable $e) {
            return $this->apiResponse($e->getMessage(), null , null, 403);
        }
    }
}
