<?php

namespace App\Http\Controllers;

class PaymentMethodsController extends Controller
{
    public function index()
    {
        return response()->json([
            [
                'name' => 'Cash',
                'description' => 'Cash payment.',
            ],
            [
                'name' => 'Transfer',
                'message' => 'Offline bank transfer payment.',
            ],
        ], 200);
    }
}
