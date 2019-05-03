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
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;


class MessagesController extends Controller
{
    public function index()
    {
        // All threads, ignore deleted/archived participants
         // $threads = Thread::getAllLatest()->get();

        // All threads that user is participating in
      /*  dump($threads = Thread::forUser(Auth::id())->latest('updated_at'));
        dd($threads = Thread::forUser(Auth::id())->latest('updated_at')->get());*/
         $threads = Thread::forUser(Auth::id())->latest('updated_at')->paginate(4);

        // All threads that user is participating in, with new messages
        // $threads = Thread::forUserWithNewMessages(Auth::id())->latest('updated_at')->get();

        return view('messages.index', compact('threads'));
    }

    public function show($id)
    {
        try {
            $thread = Thread::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Session::flash('errors', 'The thread with ID: ' . $id . ' was not found.');

            return redirect()->route('messages');
        }

        // show current user in list if not a current participant
        // $users = User::whereNotIn('id', $thread->participantsUserIds())->get();

        // don't show the current user in list
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
