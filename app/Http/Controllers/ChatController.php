<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageStoreRequest;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Src\Domains\Auth\Models\Participant;
use Src\Domains\Conferences\Models\Conference;
use Src\Domains\Messenger\Enums\Chatable;
use Src\Domains\Messenger\Enums\Role;
use Src\Domains\Messenger\Models\Chat;
use Src\Domains\Messenger\Models\Message;
use Src\Domains\Messenger\Services\MessengerService;

class ChatController extends Controller
{
    public function __construct(private MessengerService $service) {}

    public function view(): View|Factory
    {
        return view('my.chats');
    }

    public function search(Request $request): JsonResponse
    {
        $participants = Participant::query()
            ->whereNot('id', participant()?->id)
            ->where(static function ($q) use ($request) {
                $q->whereFullText(['name_ru', 'surname_ru'], $request->get('search'))
                    ->orWhereFullText(['name_en', 'surname_en'], $request->get('search'))
                    ->orWhere('orcid_id', 'like', "{$request->get('search')}%");
            })
            ->take(15)
            ->get();

        return response()->json($participants);
    }

    public function index(Request $request): JsonResponse
    {
        $chatsIds = DB::table('chat_participant')
            ->where('role', $request->get('role'))
            ->where('participant_id', participant()->id)
            ->get(['chat_id'])
            ->pluck('chat_id');

        $chats = Chat::query()
            ->whereHas('messages')
            ->with('conference:id,title_ru,title_en')
            ->whereIn('id', $chatsIds)
            ->orderByDesc('data->last_message->created_at')
            ->get();

        return response()->json($chats);
    }

    public function show(Chat $chat): JsonResponse
    {
        return response()->json($chat);
    }

    public function store(Request $request): JsonResponse
    {
        $collocutor = Participant::find($request->get('participant_id'));

        // если это мессенджер организаторов
        if (! empty($request->get('conference_id'))) {
            $conference = Conference::find($request->get('conference_id'));

            // если организатор
            if (auth()->id() === $conference->user_id) {
                $chat = $this->service->startChatWithOrganizer(participant(), $collocutor, $conference);

                return response()->json($chat->load('conference:id,title_ru,title_en'));
            }

            // если модератор
            $chat = $this->service->startChatWithModerator(participant(), $collocutor, $conference);

            return response()->json($chat->load('conference:id,title_ru,title_en'));
        }

        // если это мессенджер участника

        $authUserChatsIds = DB::table('chat_participant')
            ->whereNull('conference_id')
            ->where('role', Role::PARTICIPANT->value)
            ->where('participant_id', participant()->id)
            ->get('chat_id')
            ->pluck('chat_id');
        $commonChats = $this->service->findCommonChats($authUserChatsIds, $collocutor);

        if ($commonChats->isNotEmpty()) {
            return response()->json(Chat::find($commonChats->first()));
        }

        $chat = $this->service->createP2PChat(participant(), $collocutor);

        return response()->json($chat->load('conference:id,title_ru,title_en'));
    }

    public function storeMessage(MessageStoreRequest $request, Chat $chat): JsonResponse
    {
        $message = Message::create([
            'chat_id' => $chat->id,
            'participant_id' => participant()->id,
            'text' => $request->get('text'),
        ]);

        $chatData = $chat->data;
        $chatData->last_message = [
            'id' => $message->id,
            'text' => str($message->text)->limit(50, '')->value(),
            'created_at' => $message->created_at,
            'participant_id' => $message->participant_id,
        ];
        $chat->data = $chatData;
        $chat->save();

        // DB::table('chat_participant')
        //     ->where('chat_id', $chat->id)
        //     ->whereNot('participant_id', participant()->id)
        //     ->update(['is_read' => false]);

        return response()->json($message);
    }

    public function chatMessages(Chat $chat): JsonResponse
    {
        $messages = Message::query()
            ->where('chat_id', $chat->id)
            ->orderByDesc('id')
            ->simplePaginate(30);

        return response()->json($messages);
    }

    public function startChatWithOrganization(Request $request): JsonResponse
    {
        $conference = Conference::find($request->get('conference_id'));

        if (auth()->id() === $conference->user_id) {
            abort(Response::HTTP_CONFLICT, 'Нельзя начать чат с самим собой');
        }

        $organizer = Participant::where('user_id', $conference->user_id)->first();

        $chat = $this->service->startChatWithOrganizer($organizer, auth()->user()->participant, $conference);
        $chat->load('conference:id,title_ru,title_en');

        return response()->json($chat);
    }

    public function startChatWithModerator(Request $request): JsonResponse
    {
        if (auth()->id() === (int) $request->get('user_id')) {
            abort(Response::HTTP_CONFLICT, 'Нельзя начать чат с самим собой');
        }

        $conference = Conference::find($request->get('conference_id'));
        $moderatorParticipant = Participant::where('user_id', $request->get('user_id'))->first();

        $chat = $this->service->startChatWithModerator($moderatorParticipant, participant(), $conference);
        $chat->load('conference:id,title_ru,title_en');

        return response()->json($chat);
    }

    public function readMessages(Chat $chat, Request $request): void
    {
        Message::query()
            ->where('chat_id', $chat->id)
            ->whereNot(function ($q) {
                $q->where('participant_id', participant()->id);
            })
            ->update(['is_read' => true]);
    }

    public function readChat(Chat $chat, Request $request)
    {
        // if ($request->get('role') === Role::ORGANIZATION->value) {
        //     $chatable = 'organization';
        //     $chatableId = organization()->id;
        // } else {
        //     $chatable = 'participant';
        //     $chatableId = participant()->id;
        // }

        // DB::table('chatables')
        //     ->where('chat_id', $chat->id)
        //     ->where('chatable_id', $chatableId)
        //     ->where('chatable_type', $chatable)
        //     ->update(['is_read' => true]);
    }
}
