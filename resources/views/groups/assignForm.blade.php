@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">Assign Users</div>

                <div class="card-body">
                    <form action="{{ route('groups.assignUsers.post', ['group' => $group->id]) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="users">Select Users to Assign:</label>
                            <select name="users[]" id="users" class="form-select" multiple aria-label="multiple select example" >
                                @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ in_array($user->id, $assignedUsers) ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Assign Users</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection