<?php

namespace App\Observers;

use App\Models\PayPeriod;

class PayPeriodObserver
{
    /**
     * Handle the PayPeriod "created" event.
     */
    public function created(PayPeriod $payPeriod): void
    {
        //
    }

    /**
     * Handle the PayPeriod "updated" event.
     */
    public function updated(PayPeriod $payPeriod): void
    {
        //
    }

    /**
     * Handle the PayPeriod "deleted" event.
     */
    public function deleted(PayPeriod $payPeriod): void
    {
        $payPeriod->is_active = false;
        $payPeriod->saveQuietly();
    }

    /**
     * Handle the PayPeriod "restored" event.
     */
    public function restored(PayPeriod $payPeriod): void
    {
        //
    }

    /**
     * Handle the PayPeriod "force deleted" event.
     */
    public function forceDeleted(PayPeriod $payPeriod): void
    {
        //
    }

    private function deactivateOtherPeriods(PayPeriod $payPeriod) {
        $periods = PayPeriod::get();
        foreach ($periods as $p) {
            $p->update(['is_active' => false]);
        }

        $payPeriod->is_active = true;
        $PayPeriod->saveQuietly();
    }
}
