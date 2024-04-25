<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Channel;
use Illuminate\Support\Str;
use App\Models\Group;
use Illuminate\Support\Facades\Auth;

class ChannelController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user()->type !== 'S') {
                abort(403, 'Unauthorized');
            }

            return $next($request);
        });
    }

    public function index($groupId = null)
    {
        if ($groupId) {
            $group = Group::findOrFail($groupId);
            $channels = $group->channels;
            $groupName = $group->name;
        } else {
            $channels = Channel::all();
            $groupName = null;
        }

        return view('channels.index', compact('channels', 'groupId', 'groupName'));
    }

    public function create($groupId = null)
    {
        $groups = Group::all();
        $selectedGroup = null;

        if ($groupId) {
            $selectedGroup = Group::findOrFail($groupId);
        }
        return view('channels.create', compact('groups', 'selectedGroup'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'name' => 'required|string|max:255',
        ]);

        $slug = Str::slug($request->name);
        $slugCount = Channel::where('slug', $slug)->count();
        if ($slugCount > 0) {
            $slug = $slug . '-' . ($slugCount + 1);
        }
        $channel = Channel::create([
            'name' => $request->name,
            'slug' => $slug,
            'group_id' => $request->group_id
        ]);
        return redirect()->route('channels.index')->with('success', 'Channel added successfully.');
    }


    public function edit(Channel $channel)
    {
        $groups = Group::all();
        return view('channels.edit', compact('channel', 'groups'));
    }

    public function update(Request $request, Channel $channel)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'name' => 'required|string|max:255',
        ]);
        $slug = Str::slug($request->name);
        $slugCount = Channel::where('slug', $slug)->count();
        if ($slugCount > 0) {
            $slug = $slug . '-' . ($slugCount + 1);
        }
        $channel->update([
            'name' => $request->name,
            'slug' => $slug,
            'group_id' => $request->group_id
        ]);
        return redirect()->route('channels.index')->with('success', 'Channel updated successfully.');
    }

    public function destroy(Channel $channel)
    {
        $action = $channel->delete();
        return redirect()->route('channels.index')->with('success', 'Channel deleted successfully.');
    }
}
