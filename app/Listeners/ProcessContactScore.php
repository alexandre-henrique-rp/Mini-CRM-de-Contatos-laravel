<?php

namespace App\Listeners;

use App\Events\ContactSaved;
use App\Jobs\UpdateContactScore;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ProcessContactScore
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ContactSaved $event): void
    {
        UpdateContactScore::dispatch($event->contact);
    }
}
