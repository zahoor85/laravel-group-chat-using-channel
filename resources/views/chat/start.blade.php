@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="chat card" id="chatContainer" data-user={{ Auth::user() }} data-permissions={{ $permissions }}>
                <div class="card-header">{{ __($channel->name) }}</div>

                <div class="scrollable card-body" id="hasScrolledToBottom">
                    <div id="messageContainer"></div>
                </div>


                @if ($permissions->can_post)
                <div class="chat-form input-group">
                    <input id="btn-input" type="text" name="message" class="form-control input-sm message-" placeholder="Type your message here...">
                    <span class="input-group-btn">
                        <button class="btn btn-primary" id="btn-chat">Send</button>
                    </span>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {

        console.log("jQuery Loaded.....!");
        // Get Current Logged In User 
        var messages = permissions = [];
        var loggedInUser = $("#chatContainer").data("user");
        var permissions = $("#chatContainer").data("permissions");
        var channelId = permissions.channel_id;
        var channelName = "{{ $channel->name }}";


        function scrollBottom() {
            var container = $('#hasScrolledToBottom');
            container.scrollTop(container[0].scrollHeight);
        }

        // fetch UserPermissions
        function fetchUserPermissions() {
            $.ajax({
                method: 'GET',
                url: '/channel-permissions/' + channelId,
                success: function(response) {
                    permissions = response;
                    console.log(permissions);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching messages:', error);
                }
            });
        }


        // Fetch Messages 
        function fetchMessages() {
            $.ajax({
                method: 'GET',
                url: '/messages/' + channelId,
                success: function(response) {
                    messages = response;
                    console.log(messages);
                    renderMessages(messages);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching messages:', error);
                }
            });
        }

        // Generate Chat 
        function renderMessages(messages) {
            var messageContainer = $('#messageContainer');
            messageContainer.empty();

            if (messages && Array.isArray(messages)) {
                messages.forEach(function(message) {
                    // Check if the user can view all messages or only their own messages
                    if (permissions.can_view_messages == 1 || loggedInUser.id === message.user.id) {
                        var messageHtml = '<div class="message ';
                        if (loggedInUser.id != message.user.id) {
                            messageHtml += 'message-receive">';
                        } else {
                            messageHtml += 'message-send">';
                        }
                        messageHtml += '<p><strong class="primary-font">' + message.user.name + ' :</strong> ' + message.content + '</p></div>';
                        messageContainer.append(messageHtml);
                    }
                });
                scrollBottom();
            } else {
                console.log('Invalid messages format:', messages);
            }

        }


        // Event listener for send button click
        $('#btn-chat').click(sendMessage);

        // Event listener for enter key press
        $('#btn-input').keypress(function(event) {
            if (event.which === 13) {
                event.preventDefault();
                sendMessage();
            }
        });

        // Function to send message
        function sendMessage() {
            var newMessage = $('#btn-input').val();
            if (newMessage.trim() === '') {
                return;
            }
            var user_message = {
                user: loggedInUser,
                message: newMessage,
                channel: channelName
            };

            // Perform AJAX post request
            $.ajax({
                method: 'POST',
                url: '/messages',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: user_message,
                success: function(response) {
                    console.log(response.data);
                    messages.push(response.data);
                    renderMessages(messages);
                    $('#btn-input').val('');
                    scrollBottom();
                },
                error: function(xhr, status, error) {
                    console.log("Ajax Post error" + error);
                }
            });
        }

        // Initialize Echo and listen for events
        window.Echo.private('chat-channel')
            .listen('ChannelMessages', (e) => {
                messages.push({
                    id: e.message.id,
                    channel_id: e.message.channel_id,
                    content: e.message.content,
                    created_at: e.message.created_at,
                    updated_at: e.message.updated_at,
                    user: e.user
                });
                renderMessages(messages);
                scrollBottom();
            });

        // Call Fetch Messages function
        fetchMessages();
        //
        fetchUserPermissions();
    });
</script>

<style type="text/css">
    .message p {
        border-radius: 10px;
        padding: 10px 20px 10px 8px;
        margin-top: 5px;
        display: inline-block;
        width: auto;
        margin: 0px;
    }

    .message-send p {
        background: #e0e3e6;
        color: #2f2d2d;
    }

    .message-send {
        text-align: right;
        margin-top: 5px;
    }

    .message-receive p {
        background: #435f7a;
        color: #f5f5f5;
    }

    .message-receive {
        margin-top: 5px;
    }

    .scrollable {
        overflow: hidden;
        overflow-y: scroll;
        height: calc(100vh - 25vh);
    }

    .message-input {
        border: none;
        border-radius: 0px;
        background: #f2f2f2;
    }
</style>