@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">


                    <div class="d-flex justify-content-between align-items-center">
                        {{ __('All Groups') }}
                        <a href="{{ route('groups.create') }}" class="btn btn-primary">New Group</a>
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
                            @foreach ($groups as $group)
                            <tr>
                                <td>{{ $group->name }}</td>
                                <td>

                                    <a href="{{ route('groups.assignUsers', $group) }}" class="btn btn-sm btn-primary me-1">Assign Users</a>

                                    <a href="{{ route('channels.index', $group) }}" class="btn btn-sm btn-primary me-1">Channels</a>



                                    <a href="{{ route('groups.edit', $group) }}" class="btn btn-sm btn-primary">Edit</a>
                                    <form action="{{ route('groups.destroy', $group) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this group?')">Delete</button>
                                    </form>
                                    <!-- Add button for creating channels here -->
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