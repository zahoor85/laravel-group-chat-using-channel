<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::user()->type !== 'S') {
                abort(403, 'Unauthorized');
            }

            return $next($request);
        });
    }

    public function index()
    {
        $groups =  Group::all();
        return view('groups.index', compact('groups'));
    }

    public function create()
    {

        return view('groups.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $slug = Str::slug($request->name);

        // Ensure slug is unique
        $slugCount = Group::where('slug', $slug)->count();
        if ($slugCount > 0) {
            $slug = $slug . '-' . ($slugCount + 1); // Append a number if the slug is not unique
        }
        $group = Group::create([
            'name' => $request->name,
            'slug' => $slug,
        ]);
        return redirect()->route('groups.index')->with('success', 'Group added successfully.');
    }

    public function edit(Group $group)
    {

        return view('groups.edit', compact('group'));
    }

    public function update(Request $request, Group $group)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $slug = Str::slug($request->name);

        // Ensure slug is unique
        $slugCount = Group::where('slug', $slug)->count();
        if ($slugCount > 0) {
            $slug = $slug . '-' . ($slugCount + 1); // Append a number if the slug is not unique
        }

        $group->update([
            'name' => $request->name,
            'slug' => $slug,
        ]);
        return redirect()->route('groups.index')->with('success', 'Group updated successfully.');
    }

    public function assignUsersForm(Request $request, $groupId)
    {
        $group = Group::findOrFail($groupId);
        $users = User::all();
        $assignedUsers = $group->users->pluck('id')->toArray();

        return view('groups.assignForm', compact('group', 'users', 'assignedUsers'));
    }

    public function assignUsers(Request $request, $groupId)
    {
        $group = Group::findOrFail($groupId);
        $group->users()->detach();
        $userIds = $request->input('users');
        $users = User::whereIn('id', $userIds)->get();
        $group->users()->attach($users);

        return redirect()->route('groups.index')->with('success', 'Users assigned successfully.');
    }

    public function destroy(Group $group)
    {
        $group->delete();
        return redirect()->route('groups.index')->with('success', 'Group deleted successfully.');
        // return response()->json(['message' => 'Group deleted']);
    }
}
