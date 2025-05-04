@if (isset($messages) && $messages->count() > 0)
@foreach ($messages as $msg)
<div class="chatbox-message {{ $msg->is_bot ? 'bot' : 'user' }}">
    {{ $msg->message }}
</div>
@endforeach
@else
<div class="chatbox-message bot">Chưa có tin nhắn!</div>
@endif