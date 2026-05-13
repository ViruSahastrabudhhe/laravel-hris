<?php

namespace App\Observers;

use App\Models\PerformanceCycle;

class PerformanceCycleObserver
{
    /**
     * Handle the PerformanceCycle "created" event.
     */
    public function created(PerformanceCycle $performanceCycle): void
    {
        $this->setOthersToInactive();
    }

    /**
     * Handle the PerformanceCycle "updated" event.
     */
    public function updated(PerformanceCycle $performanceCycle): void
    {
        $this->setOthersToInactive();
    }

    /**
     * Handle the PerformanceCycle "deleted" event.
     */
    public function deleted(PerformanceCycle $performanceCycle): void
    {
        //
    }

    /**
     * Handle the PerformanceCycle "restored" event.
     */
    public function restored(PerformanceCycle $performanceCycle): void
    {
        //
    }

    /**
     * Handle the PerformanceCycle "force deleted" event.
     */
    public function forceDeleted(PerformanceCycle $performanceCycle): void
    {
        //
    }

    private function setOthersToInactive() {
        $performanceCycles = PerformanceCycle::all();
        $latestPerformanceCycle = PerformanceCycle::latest()->first();

        foreach ($performanceCycles as $performanceCycle) {
            if ($performanceCycle->id == $latestPerformanceCycle->id) {
                continue;
            }
            $performanceCycle->is_active = false;
            $performanceCycle->saveQuietly();
        }
    }
}
