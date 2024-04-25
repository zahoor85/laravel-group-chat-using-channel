@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">


                    <div class="d-flex justify-content-between align-items-center">
                        {{ __('All') }} {{ $groupName ? __(':groupName Channels', ['groupName' => $groupName]) : __('Channels') }}


                        @if($groupId)
                        <a href="{{ route('channels.create.withGroup', $groupId) }}" class="btn btn-primary">New Channel</a>
                        @else
                        <a href="{{ route('channels.create') }}" class="btn btn-primary">New Channel</a>
                        @endif

                    </div>

                </div>

                <div class="card-body">

                    @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($channels as $channel)
                            <tr>
                                <td>{{ $channel->name }}</td>
                                <td>


                                    <a href="{{ route('channels.permissions', $channel) }}" class="btn btn-sm btn-primary me-1">Permissions</a>

                                    <a href="{{ route('channels.edit', $channel) }}" class="btn btn-sm btn-primary">Edit</a>
                                    <form action="{{ route('channels.destroy', $channel) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this channel?')">Delete</button>
                                    </form>




                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>@endsection