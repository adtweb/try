<?php

namespace Src\Domains\Messenger\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Src\Domains\Auth\Models\Participant;
use Src\Domains\Conferences\Models\Conference;
use Src\Domains\Messenger\Enums\ChatType;
use Src\Domains\Messenger\Enums\Role;
use Src\Domains\Messenger\Models\Chat;

class MessengerService
{
    public function createP2PChat(Participant $participant, Participant $collocutor)
    {
        $data = [
            'members' => [
                [
                    'role' => Role::PARTICIPANT->value,
                    ...$participant->toChatable(),
                ],
                [
                    'role' => Role::PARTICIPANT->value,
                    ...$collocutor->toChatable(),
                ],
            ],
        ];

        $chat = $this->createChat(ChatType::P2P, $data);
        $chat->participants()->sync([
            $participant->id => ['role' => Role::PARTICIPANT->value],
            $collocutor->id => ['role' => Role::PARTICIPANT->value],
        ]);

        return $chat;
    }

    public function createP2OChat(Participant $organizer, Participant $collocutor, Conference $conference)
    {
        $data = [
            'members' => [
                [
                    'role' => Role::ORGANIZER->value,
                    ...$organizer->toChatable(),
                ],
                [
                    'role' => Role::PARTICIPANT->value,
                    ...$collocutor->toChatable(),
                ],
            ],
        ];

        $chat = $this->createChat(ChatType::P2O, $data, $conference->id);

        DB::table('chat_participant')
            ->insert([
                'chat_id' => $chat->id,
                'participant_id' => $organizer->id,
                'conference_id' => $conference->id,
                'role' => Role::ORGANIZER->value,
            ]);
        DB::table('chat_participant')
            ->insert([
                'chat_id' => $chat->id,
                'participant_id' => $collocutor->id,
                'conference_id' => $conference->id,
                'role' => Role::PARTICIPANT->value,
            ]);

        return $chat;
    }

    public function createP2MChat(Participant $moderator, Participant $collocutor, Conference $conference)
    {
        $data = [
            'members' => [
                [
                    'role' => Role::MODERATOR->value,
                    ...$moderator->toChatable(),
                ],
                [
                    'role' => Role::PARTICIPANT->value,
                    ...$collocutor->toChatable(),
                ],
            ],
        ];

        $chat = $this->createChat(ChatType::P2M, $data, $conference->id);

        DB::table('chat_participant')
            ->insert([
                'chat_id' => $chat->id,
                'participant_id' => $moderator->id,
                'conference_id' => $conference->id,
                'role' => Role::MODERATOR->value,
            ]);
        DB::table('chat_participant')
            ->insert([
                'chat_id' => $chat->id,
                'participant_id' => $collocutor->id,
                'conference_id' => $conference->id,
                'role' => Role::PARTICIPANT->value,
            ]);

        return $chat;
    }

    public function createChat(ChatType $type, array $data, ?int $conferenceId = null): Chat
    {
        return Chat::create([
            'type' => $type,
            'data' => $data,
            'conference_id' => $conferenceId,
        ]);
    }

    public function findCommonChats(Collection $chatsIds, Participant $collocutor): Collection
    {
        $collocutorChatsIds = $collocutor->chats->pluck('id');
        $commonChatsIds = $chatsIds->unique()->intersect($collocutorChatsIds);

        if ($commonChatsIds->count() > 1) {
            $message = sprintf(
                'У участников больше одного чата (id авторизованного пользователя: %s, participant id собеседника %s)',
                auth()->id(),
                $collocutor->id
            );

            throw new \Exception($message, 1);
        }

        return $commonChatsIds;
    }

    public function startChatWithOrganizer(Participant $organizer, Participant $collocutor, Conference $conference): Chat
    {
        $organizerChatsIds = DB::table('chat_participant')
            ->where('conference_id', $conference->id)
            ->where('role', Role::ORGANIZER->value)
            ->where('participant_id', $organizer->id)
            ->get('chat_id')
            ->pluck('chat_id');

        $commonChatsIds = $this->findCommonChats($organizerChatsIds, $collocutor);

        if ($commonChatsIds->isNotEmpty()) {
            return Chat::find($commonChatsIds->first());
        }

        return $this->createP2OChat($organizer, $collocutor, $conference);
    }

    public function startChatWithModerator(
        Participant $moderatorParticipant,
        Participant $collocutor,
        Conference $conference,
    ): Chat {
        $moderatorChatsIds = DB::table('chat_participant')
            ->where('conference_id', $conference->id)
            ->where('role', Role::MODERATOR->value)
            ->where('participant_id', $moderatorParticipant->id)
            ->get('chat_id')
            ->pluck('chat_id');
        $commonChats = $this->findCommonChats($moderatorChatsIds, $collocutor);

        if ($commonChats->isNotEmpty()) {
            return Chat::find($commonChats->first());
        }

        return $this->createP2MChat($moderatorParticipant, $collocutor, $conference);
    }
}
