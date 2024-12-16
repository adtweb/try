<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Src\Domains\Auth\Models\Participant;
use Src\Domains\Conferences\Models\Conference;
use Src\Domains\Messenger\Enums\Role;
use Src\Domains\Messenger\Models\Message;

class MessageCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Participant $from;

    public Role $to;

    /**
     * Create a new event instance.
     */
    public function __construct(public Message $message)
    {
        $message->load(['chat']);

        $this->from = Participant::find($message->participant_id);

        $members = collect($this->message->chat->data->members);

        $collocutor = $members->first(function ($member) {
            return $member->user_id !== $this->from->user_id;
        });

        if ($collocutor->role === Role::ORGANIZER->value) {
            $chatable = DB::table('chat_participant')
                ->where('chat_id', $message->chat->id)
                ->first();
            $conference = Conference::find($chatable->conference_id);

            $this->to = Role::ORGANIZER;
            $this->message->conference_slug = $conference->slug;
            $this->message->conference_id = $conference->id;

            return;
        }
        if ($collocutor->role === Role::MODERATOR->value) {
            $chatable = DB::table('chat_participant')
                ->where('chat_id', $message->chat->id)
                ->first();

            $conference = Conference::find($chatable->conference_id);

            $this->to = Role::MODERATOR;
            $this->message->conference_slug = $conference->slug;
            $this->message->conference_id = $conference->id;

            return;
        }

        $this->to = Role::PARTICIPANT;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $chat = $this->message->chat;

        $channels = collect($chat->data->members)
            ->pluck('user_id')
            ->map(fn ($id) => new PrivateChannel('App.Models.User.'.$id))
            ->toArray();

        return $channels;
    }

    public function broadcastAs()
    {
        return 'message.created';
    }
}
