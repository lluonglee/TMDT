<div class="chatbox">
    <button class="chatbox-toggle">💬</button>
    <div class="chatbox-window {{ session('chat_open') ? 'chatbox-open' : 'chatbox-hidden' }}">
        <div class="chatbox-header">
            <h3>Chat Hỗ Trợ</h3>
            <button class="chatbox-close">✕</button>
        </div>
        <div class="chatbox-body">
            @if (session('error'))
            <div class="chatbox-message bot">{{ session('error') }}</div>
            @endif
            @if (session('success'))
            <div class="chatbox-message bot">{{ session('success') }}</div>
            @endif
            @include('partials.chat_messages', ['messages' => $messages])
        </div>
        <div class="chatbox-footer">
            <form action="{{ url('/chat/send') }}" method="POST">
                @csrf
                <input type="text" name="message" class="chatbox-input" placeholder="Nhập tin nhắn..." required>
                <button type="submit" style="display: none;"></button>
            </form>
        </div>
    </div>
</div>

<style>
    .chatbox {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 20000;
        pointer-events: auto;
    }

    .chatbox-toggle {
        background-color: #007BFF;
        color: white;
        border: none;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        font-size: 24px;
        cursor: pointer;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        pointer-events: auto;
    }

    .chatbox-window {
        width: 300px;
        height: 400px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        flex-direction: column;
        margin-bottom: 10px;
        pointer-events: auto;
    }

    .chatbox-open {
        display: flex;
    }

    .chatbox-hidden {
        display: none;
    }

    .chatbox-header {
        background: #007BFF;
        color: white;
        padding: 10px;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .chatbox-header h3 {
        margin: 0;
        font-size: 16px;
    }

    .chatbox-close {
        background: none;
        border: none;
        color: white;
        font-size: 16px;
        cursor: pointer;
        pointer-events: auto;
    }

    .chatbox-body {
        flex: 1;
        padding: 10px;
        overflow-y: auto;
        background: #f9f9f9;
    }

    .chatbox-message {
        margin: 5px 0;
        padding: 8px 12px;
        border-radius: 10px;
        max-width: 80%;
        font-size: 14px;
    }

    .chatbox-message.user {
        background: #007BFF;
        color: white;
        margin-left: auto;
        border-bottom-right-radius: 2px;
    }

    .chatbox-message.bot {
        background: #e9ecef;
        color: #333;
        margin-right: auto;
        border-bottom-left-radius: 2px;
    }

    .chatbox-footer {
        padding: 10px;
        border-top: 1px solid #ddd;
    }

    .chatbox-input {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
        outline: none;
        pointer-events: auto;
    }

    @media (max-width: 480px) {
        .chatbox {
            bottom: 10px;
            right: 10px;
        }

        .chatbox-window {
            width: 80vw;
            height: 60vh;
            max-height: 450px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Chatbox loaded');

        const toggleBtn = document.querySelector('.chatbox-toggle');
        const chatWindow = document.querySelector('.chatbox-window');
        const closeBtn = document.querySelector('.chatbox-close');
        const input = document.querySelector('.chatbox-input');
        const form = document.querySelector('.chatbox-footer form');
        const chatBody = document.querySelector('.chatbox-body');

        toggleBtn.addEventListener('click', function() {
            console.log('Toggle clicked');
            chatWindow.classList.toggle('chatbox-open');
            chatWindow.classList.toggle('chatbox-hidden');
            chatBody.scrollTop = chatBody.scrollHeight;
        });

        closeBtn.addEventListener('click', function() {
            console.log('Close clicked');
            chatWindow.classList.remove('chatbox-open');
            chatWindow.classList.add('chatbox-hidden');
        });

        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && input.value.trim()) {
                e.preventDefault();
                console.log('Submitting:', input.value);
                form.submit();
            }
        });
    });
</script>