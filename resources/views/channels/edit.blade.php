@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">Add New Group</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('channels.update', $channel) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="groupId" class="form-label">Select a Group</label>
                            <select class="form-select" id="groupId" name="group_id">
                                <option value="">Select a group</option>
                                @foreach ($groups as $group)
                                <option value="{{ $group->id }}" @if ($group->id == $channel->group_id) selected @endif>{{ $group->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="groupName" class="form-label">Channel Name</label>
                            <input type="text" class="form-control" id="groupName" name="name" value="{{ $channel->name }}" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection