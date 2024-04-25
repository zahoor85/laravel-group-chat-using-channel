@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    {{ __('You are logged in!') }} <a href="chat">Chat</a> | <a href="groups">Groups</a> | <a href="channels">Channels</a>
                    <div>
                        @if($groups)
                        <p>Assigned Groups:</p>
                        @foreach ($groups as $group)
                        <div>
                            <p>Group Name: {{ $group->name }}</p>
                            <ul>
                                @foreach ($group->channels as $channel)
                                <li>Channel Name: <a href="{{ route('start-chat', ['channelId' => $channel->id]) }}">Start Chat at {{ $channel->name }}</a></li>
                                
                                @endforeach
                            </ul>
                        </div>
                        @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection