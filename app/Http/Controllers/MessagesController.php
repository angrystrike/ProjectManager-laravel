<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;


class MessagesController extends Controller
{
    public function index()
    {
        $threads = Thread::forUser(Auth::id())->latest('updated_at')->paginate(4);
        return view('messages.index', compact('threads'));
    }

    public function show($id)
    {
        try
        {
            $thread = Thread::findOrFail($id);
        }
        catch (ModelNotFoundException $e)
        {
            Session::flash('errors', 'The thread with ID: ' . $id . ' was not found.');
            return redirect()->route('messages');
        }
        $userId = Auth::id();
        $users = User::whereNotIn('id', $thread->participantsUserIds($userId))->get();

        $thread->markAsRead($userId);

        return view('messages.show', compact('thread', 'users'));
    }

    public function create()
    {
        $users = User::where('id', '!=', Auth::id())->get();
        return view('messages.create', compact('users'));
    }

    public function currentParticipants($thread_id)
    {
        $participants = DB::table('users')
            ->select('users.id', 'users.email', 'users.name', 'participants.last_read', 'participants.created_at')
            ->join('participants', 'users.id', '=', 'participants.user_id')
            ->where('participants.thread_id', '=', $thread_id)
            ->where('participants.user_id', '!=', Auth::id())
            ->paginate(3);

        $thread = Thread::where('id', $thread_id)->first();
        if ($thread->creator()->id == Auth::id()) {
            $isCreator = true;
        }
        else {
            $isCreator = false;
        }

        return view('messages.participants', ['participants' => $participants, 'thread_id' => $thread_id, 'isCreator' => $isCreator]);
    }

    public function kickFromThread($thread_id, $user_id)
    {
        $thread = Thread::find($thread_id);

        if ($thread->creator()->id != Auth::id()) {
            return response()->json(['message' => 'Not enough credentials for this action']);
        }
        else if ($user_id == Auth::id()) {
            return response()->json(['message' => 'You cannot kick yourself']);
        }

        DB::table('participants')
            ->where('thread_id', $thread_id)
            ->where('user_id', $user_id)
            ->delete();

        return response()->json(['message' => 'Member was kicked']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|max:190',
            'message' => 'max:5000'
        ]);

        $input = Input::all();

        $thread = Thread::create([
            'subject' => $input['subject'],
        ]);

        Message::create([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'body' => $input['message'],
        ]);

        Participant::create([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'last_read' => new Carbon,
        ]);

        if (Input::has('recipients')) {
            $thread->addParticipant($input['recipients']);
        }

        if (!empty($input['isAjax'])) {
            $thread->addParticipant($request->input('recipient_id'));
            return response()->json(['message' => 'Message was successfully sent!']);
        }
        return redirect()->route('messages');
    }

    public function update($id)
    {
        if (strlen(Input::get('message')) > 190) {
            return back()->withInput()->with('errors', 'Too long message');
        }

        try {
            $thread = Thread::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Session::flash('errors', 'The thread with ID: ' . $id . ' was not found.');

            return redirect()->route('messages');
        }

        $thread->activateAllParticipants();

        Message::create([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'body' => Input::get('message'),
        ]);

        $participant = Participant::firstOrCreate([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
        ]);
        $participant->last_read = new Carbon;
        $participant->save();

        if (Input::has('recipients')) {
            $thread->addParticipant(Input::get('recipients'));
        }

        return redirect()->route('messages.show', $id);
    }

    public function edit(Request $request)
    {
        if (strlen(request('body')) > 190 || strlen(request('body')) == 0) {
            return response()->json(['status' => '422', 'message' => 'Incorrect input']);
        }
        $message = Message::where('id', $request->input('id'))->first();
        if (Auth::id() != $message->user_id) {
            return response()->json(['status' => '401', 'message' => 'You do not have enough credentials for this action']);
        }
        $message->update([
            'body' => $request->input('body')
        ]);
        return response()->json(['message' => 'Message updated successfully']);
    }

    public function destroy($id)
    {
        $message = Message::where('id', $id)->first();
        if (Auth::id() != $message->user_id) {
            return response()->json(['status' => '401', 'message' => 'You do not have enough credentials for this action']);
        }
        $message->delete();

        return response()->json(['message' => 'Message deleted!']);
    }

    public function deleteThread($thread_id)
    {
        $thread = Thread::where('id', $thread_id)->first();

        if (Auth::id() == $thread->creator()->id) {
            $thread->delete();
            return response()->json(['success' => 'Conversation was deleted!']);
        }
        else {
            return response()->json(['status' => '401', 'message' => 'You do not have enough credentials for this action']);
        }
    }
}
