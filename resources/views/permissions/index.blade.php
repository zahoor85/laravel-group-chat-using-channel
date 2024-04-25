@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">Permission Users</div>

                <div class="card-body">

                    @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif


                    <form method="post" action="{{ route('permissions.update', ['channel' => $channel->id]) }}">
                        @csrf
                        @method('PUT')

                        @foreach ($groupUsers as $user)
                        <div>
                            <label>{{ $user->name }}</label>
                            <div>
                                <label>
                                    <input type="checkbox" name="users[{{ $user->id }}][can_join]" value="1" class="form-check-input" checked>
                                    Can Join
                                </label>
                                <label>
                                    <input type="checkbox" name="users[{{ $user->id }}][can_post]" value="1" class="form-check-input" {{ $user->hasPermission($channel->id, 'can_post') ? 'checked' : '' }}>
                                    Can Post
                                </label>
                                <label>
                                    <input type="checkbox" name="users[{{ $user->id }}][can_view_messages]" value="1" class="form-check-input" {{ $user->hasPermission($channel->id, 'can_view_messages') ? 'checked' : '' }}>
                                    Can View Messages
                                </label>
                            </div>
                        </div>
                        @endforeach

                        <button type="submit" class="btn btn-sm btn-primary">Update Permissions</button>
                    </form>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection