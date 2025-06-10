<?php

namespace App\Events;

use App\Models\Contact;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

// A interface ShouldBroadcast é a chave para a transmissão em tempo real.
class ScoreUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * O contato que foi atualizado.
     */
    public Contact $contact;

    /**
     * Create a new event instance.
     */
    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('contacts.' . $this->contact->id),
        ];
    }

    /**
     * Define o nome do evento a ser transmitido.
     */
    public function broadcastAs(): string
    {
        return 'score.updated';
    }

    /**
     * Define os dados que serão transmitidos.
     */
    public function broadcastWith(): array
    {
        // Enviamos apenas os dados que são relevantes para a atualização.
        return [
            'score' => $this->contact->score,
        ];
    }
}
