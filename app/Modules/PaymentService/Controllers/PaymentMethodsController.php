<?php

namespace App\Modules\PaymentService\Controllers;

use App\Http\Controllers\Controller;

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
