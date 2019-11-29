<?php

namespace App\Modules\PaymentService\Jobs;

use App\Jobs\Job;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

/**
 * Class CreatePaymentJob
 * @package App\Jobs
 */
class CreatePaymentJob extends Job
{
    public $result;
    public $paymentData;

    /**
     * Create a new job instance.
     *
     * @param  Collection  $paymentData
     */
    public function __construct(Collection $paymentData)
    {
        $this->paymentData = $paymentData;
        $this->result = false;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        try {
            $this->calculateSum();
            $this->createPayment();
            $this->result = true;
        } catch (QueryException $ex) {
            // Handle an exception
            $this->result = false;
        }
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->result;
    }

    /**
     * Calculate needfull sum of invoice.
     */
    private function calculateSum(): void
    {
        if (! $this->paymentData->has('amount')) {

            $invoiceId = $this->paymentData->get('invoice_id');

            $invoicePrice = app('db')
                ->table('invoices')
                ->select('order_price')
                ->where('id', $invoiceId)
                ->sum('order_price');

            $transactionsSum = app('db')
                ->table('payments')
                ->select('amount')
                ->where('invoice_id', $invoiceId)
                ->sum('amount');

            $sum = $invoicePrice - $transactionsSum;
            $this->paymentData->put('amount', $sum);
        }
    }

    /**
     * Insert new payment row to db.
     */
    private function createPayment(): void
    {
        app('db')->table('payments')->insert([
            'invoice_id' => $this->paymentData->get('invoice_id'),
            'amount' => $this->paymentData->get('amount'),
            'payment_method' => $this->paymentData->get('payment_method'),
            'reference' => $this->paymentData->get('reference'),
            'datetime' => $this->paymentData->get('datetime'),
        ]);
    }
}
