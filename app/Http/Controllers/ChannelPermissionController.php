<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChannelPermission;
use App\Models\Channel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ChannelPermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }



    public function index($channelId)
    {
        // Get the channel
        $channel = Channel::findOrFail($channelId);

        // Get group users
        $groupUsers = User::whereHas('groups', function ($query) use ($channel) {
            $query->where('group_id', $channel->group_id);
        })->get();


        // Get channel permissions
        $permissions = ChannelPermission::where('channel_id', $channelId)->get();

        // Combine user permissions
        $userPermissions = [];
        foreach ($groupUsers as $user) {
            $userPermissions[$user->id] = $permissions->where('user_id', $user->id)->first();
        }

        return view('permissions.index', compact('channel', 'groupUsers', 'userPermissions'));
    }


    public function update(Request $request, $channelId)
    {
        $request->validate([
            'users.*.can_join' => 'nullable|boolean',
            'users.*.can_post' => 'nullable|boolean',
            'users.*.can_view_messages' => 'nullable|boolean',
        ]);

        $users = $request->input('users');


        // Loop through submitted user permissions
        foreach ($users as $userId => $userData) {

            $channelPermission = ChannelPermission::updateOrCreate(
                ['user_id' => $userId, 'channel_id' => $channelId],
                [
                    'can_join' => isset($userData['can_join']) ? $userData['can_join'] : false,
                    'can_post' => isset($userData['can_post']) ? $userData['can_post'] : false,
                    'can_view_messages' => isset($userData['can_view_messages']) ? $userData['can_view_messages'] : false
                ]
            );
        }

        return redirect()->back()->with('success', 'Channel permissions updated successfully.');
    }

    public function getUserPermissions(Request $request, $channelId)
    {
        $loggedInUser = Auth::user();
        $permissions = ChannelPermission::where('user_id', $loggedInUser->id)
            ->where('channel_id', $channelId)
            ->first();
        return response()->json($permissions);
    }
}
