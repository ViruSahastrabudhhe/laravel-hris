@extends('layouts.employee')

@section('page-content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Government Services Chatbot</div>
                    <div class="card-body">
                        <div id="chat-messages" class="mb-3" style="height: 400px; overflow-y: auto; border: 1px solid #ddd; padding: 15px; border-radius: 5px; background: #f9f9f9;">
                        </div>
                        <div class="input-group">
                            <input type="text" id="message-input" class="form-control" placeholder="Ask about government services...">
                            <div class="input-group-append">
                                <button id="send-button" class="btn btn-primary">Send</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .user-message {
            background: #007bff;
            color: white;
            padding: 10px 15px;
            border-radius: 15px;
            margin-bottom: 10px;
            max-width: 70%;
            margin-left: auto;
            text-align: right;
        }
        .bot-message {
            background: #e9ecef;
            color: #333;
            padding: 10px 15px;
            border-radius: 15px;
            margin-bottom: 10px;
            max-width: 70%;
        }
        .bot-message.error {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#send-button').click(function() {
                sendMessage();
            });

            $('#message-input').keypress(function(e) {
                if (e.which == 13) {
                    sendMessage();
                }
            });

            function sendMessage() {
                const message = $('#message-input').val().trim();
                if (!message) return;

                $('#chat-messages').append(`<div class="user-message">${escapeHtml(message)}</div>`);
                $('#message-input').val('');
                scrollToBottom();

                $.ajax({
                    url: '/chatbot/chat',
                    method: 'POST',
                    data: { message: message },
                    success: function(data) {
                        $('#chat-messages').append(`<div class="bot-message">${escapeHtml(data.response)}</div>`);
                        scrollToBottom();
                    },
                    error: function() {
                        $('#chat-messages').append(`<div class="bot-message error">Sorry, an error occurred.</div>`);
                        scrollToBottom();
                    }
                });
            }

            function scrollToBottom() {
                $('#chat-messages').scrollTop($('#chat-messages')[0].scrollHeight);
            }

            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }
        });
    </script>
@endpush
