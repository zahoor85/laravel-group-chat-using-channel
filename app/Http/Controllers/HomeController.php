<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\ChannelMessages;
use App\Models\Channel;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\ChannelPermission;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $loggedInUser = Auth::user();
        $groups = $loggedInUser->groups()->with('channels')->get();
        return view('home', compact('groups'));
    }

    public function start($channelId)
    {
        // Load the channel by ID
        $channel = Channel::findOrFail($channelId);

        // Get the logged-in user
        $loggedInUser = Auth::user();

        // Get the user's permissions for the specified channel
        $permissions = ChannelPermission::where('user_id', $loggedInUser->id)
            ->where('channel_id', $channelId)
            ->first();
        // Pass the channel and permissions data to the view
        return view('chat.start', compact('channel', 'permissions'));
    }

    public function chat()
    {
        return view('chat');
    }

    public function chatJquery()
    {
        return view('chat_jquery');
    }

    public function messages($channelId)
    {
        return Message::where('channel_id', $channelId)->with('user')->get();
    }

    public function messageStore(Request $request)
    {
        $user = Auth::user();
        $channel = Channel::where('name', $request->channel)->first();

        if ($channel) {

            if ($user->channelPermissions()->where('channel_id', $channel->id)->value('can_post')) {

                // Create the message with the retrieved channel ID
                $message = $user->messages()->create([
                    'content' => $request->message,
                    'channel_id' => $channel->id, // Assign the channel ID to the message
                ]);

                $messageWithUser = Message::with('user')->find($message->id);
                broadcast(new ChannelMessages($user, $message, 'chat-channel'))->toOthers();

                return response()->json([
                    'message' => 'Message sent',
                    'data' => $messageWithUser // Assuming your message model has the necessary attributes
                ]);
            }

            // Broadcast the message or perform any other necessary actions
        } else {
            return response()->json([
                'message' => 'Error'
            ]);
        }



        // $messages = $user->messages()->create([
        //     'message' => $request->message
        // ]);
        // $messageWithUser = Message::with('user')->find($messages->id);

        // broadcast(new ChannelMessages($user, $messages, 'chat-channel'))->toOthers();

        // return response()->json([
        //     'message' => 'Message sent',
        //     'data' => $messageWithUser // Assuming your message model has the necessary attributes
        // ]);
    }
}




//

// if ($user->channelPermissions()->where('channel_name', $channelName)->value('can_post')) {
//     // Broadcast the message
//     event(new ChatMessages($user, $message, $channelName));
// }
