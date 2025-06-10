<?php

namespace App\Jobs;

use App\Models\Contact;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Testing\Fluent\Concerns\Interaction;

class UpdateContactScore implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * O contato que será processado por este job.
     * O 'public' é importante para que o Laravel possa acessá-lo.
     */
    public Contact $contact;

    /**
     * Create a new job instance.
     */
    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        sleep(2);

        $newScore = rand(0, 100);

        $this->contact->update([
            'score' => $newScore,
            'updated_at' => now()
        ]);
    }
}
