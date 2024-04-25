@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">Add New Channel</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('channels.store') }}">
                        @csrf

                        <div class="mb-3">

                            @if ($selectedGroup)
                            <!-- Display selected group -->
                            <p>Selected Group: {{ $selectedGroup->name }}</p>
                            <input type="hidden" id="groupId" name="group_id" value="{{ $selectedGroup->id }}" />
                            @else
                            <!-- Display dropdown with all groups -->
                            <div class="mb-3">
                                <label for="groupId" class="form-label">Select a Group</label>
                                <select class="form-select" id="groupId" name="group_id">
                                    <option value="">Select a group</option>
                                    @foreach ($groups as $group)
                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            <label for="name" class="form-label">Channel Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="groupName" name="name" placeholder="Enter channel name" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection