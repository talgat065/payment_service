<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $this->validate($request, [
            'order_number' => 'required|integer',
            'order_price' => 'required|integer',
            'payment_time_limit' => 'required|date_format:Y-m-d H:i:s',
        ]);

        try {
            app('db')->table('invoices')->insert($request->all());
            return response()->json([
                'status' => true,
            ], 201);
        } catch (QueryException $ex) {
            // Catch an exception
        }

        return response()->json([
            'status' => false,
        ], 500);
    }

    /**
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request): JsonResponse
    {
        $this->validate($request, [
            'id' => 'required|integer',
            'order_price' => 'required|integer',
            'payment_time_limit' => 'required|date_format:Y-m-d H:i:s',
        ]);

        try {
            app('db')
                ->table('invoices')
                ->where('id', $request->get('id'))
                ->update($request->all());
            return response()->json([
                'status' => true,
            ], 200);
        } catch (QueryException $ex) {
            // Catch an exception
        }

        return response()->json([
            'status' => true,
        ]);
    }
}
