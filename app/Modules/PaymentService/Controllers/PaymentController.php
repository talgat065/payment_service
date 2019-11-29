<?php

namespace App\Modules\PaymentService\Controllers;

use App\Http\Controllers\Controller;
use App\Jobs\CreatePaymentJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $this->validate($request, [
            'invoice_id' => 'required|integer',
            'payment_method' => 'required',
            'datetime' => 'required|date_format:Y-m-d H:i:s',
            'amount' => 'numeric|nullable',
            'reference' => 'string|max:255|nullable',
        ]);

        $job = new CreatePaymentJob(collect($request->toArray()));
        $job->handle();

        if ($job->isSuccess()) {
            return response()->json([
                'status' => true,
            ], 201);
        }

        return response()->json([
            'status' => false,
        ], 500);
    }
}
