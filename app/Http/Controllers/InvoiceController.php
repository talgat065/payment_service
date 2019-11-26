<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class InvoiceController extends Controller
{
    /**
     * @param  Request  $request
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function filterByDate(Request $request)
    {
        $this->validate($request, [
            'start_date' => 'required|date_format:Y-m-d H:i:s',
            'end_date' => 'required|date_format:Y-m-d H:i:s',
            'paymentMethod' => 'string|max:255',
        ]);

        return app('db')
            ->table('invoices')
            ->whereBetween('created_at', [$request->get('start_date'), $request->get('end_date')])
            ->get();
    }

    /**
     * @param  Request  $request
     * @param $id
     * @return Collection
     */
    public function show(Request $request, $id)
    {
        /**
         * @var $invoice Collection
         */
        $invoice = app('db')
            ->table('invoices')
            ->where('id', '=', $id)
            ->get();

        $payments = app('db')
            ->table('payments')
            ->where('invoice_id', '=', $id)
            ->get();

        $invoice->put('payments', $payments);

        return $invoice;
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function check($id)
    {
        return response()->json([
            'exists' => app('db')
                ->table('invoices')
                ->where('id', $id)
                ->exists(),
        ]);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function getInfo($id)
    {
        /**
         * @var $invoice Collection
         */
        $invoice = app('db')
            ->table('invoices')
            ->where('id', '=', $id)
            ->first();

        if ($invoice === null) {
            return response()->json([
                'status' => false,
                'message' => 'Invoice not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $invoice,
        ]);
    }

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
            app('db')->table('invoices')->insert([
                'order_number' => $request->get('order_number'),
                'order_price' => $request->get('order_price'),
                'payment_time_limit' => $request->get('payment_time_limit'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
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
                ->update([
                    'order_price' => $request->get('order_price'),
                    'payment_time_limit' => $request->get('payment_time_limit'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
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
