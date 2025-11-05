<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    * {
        font-family: 'Inter', sans-serif;
    }

    .chat-button {
        position: fixed;
        bottom: 24px;
        right: 24px;
        width: 64px;
        height: 64px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 8px 32px rgba(102, 126, 234, 0.3);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 1000;
        border: none;
        outline: none;
    }

    .chat-button:hover {
        transform: translateY(-4px) scale(1.05);
        box-shadow: 0 12px 40px rgba(102, 126, 234, 0.4);
    }

    .chat-button:active {
        transform: translateY(-2px) scale(1.02);
    }

    .chat-icon {
        width: 28px;
        height: 28px;
        color: white;
        transition: all 0.3s ease;
    }

    .chat-button.active .chat-icon {
        transform: rotate(180deg);
    }

    .pulse-ring {
        position: absolute;
        width: 64px;
        height: 64px;
        border: 2px solid rgba(102, 126, 234, 0.3);
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
            opacity: 1;
        }
        100% {
            transform: scale(1.5);
            opacity: 0;
        }
    }

    .notification-dot {
        position: absolute;
        top: -2px;
        right: -2px;
        width: 20px;
        height: 20px;
        background: #ef4444;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 12px;
        font-weight: 600;
        border: 3px solid white;
        animation: bounce 2s infinite;
    }

    @keyframes bounce {
        0%, 20%, 53%, 80%, 100% {
            transform: translate3d(0,0,0);
        }
        40%, 43% {
            transform: translate3d(0,-8px,0);
        }
        70% {
            transform: translate3d(0,-4px,0);
        }
        90% {
            transform: translate3d(0,-2px,0);
        }
    }

    .chat-widget {
        position: fixed;
        bottom: 100px;
        right: 24px;
        width: 380px;
        height: 500px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        transform: translateY(20px) scale(0.95);
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 999;
        overflow: hidden;
    }

    .chat-widget.active {
        transform: translateY(0) scale(1);
        opacity: 1;
        visibility: visible;
    }

    .chat-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 20px;
        color: white;
    }

    .chat-messages {
        height: 340px;
        overflow-y: auto;
        padding: 20px;
        background: #f8fafc;
    }

    .chat-input-area {
        padding: 20px;
        background: white;
        border-top: 1px solid #e2e8f0;
    }

    .message {
        margin-bottom: 16px;
        animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .message.bot {
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .message.user {
        display: flex;
        justify-content: flex-end;
    }

    .bot-avatar {
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .message-bubble {
        max-width: 80%;
        padding: 12px 16px;
        border-radius: 18px;
        font-size: 14px;
        line-height: 1.4;
    }

    .message.bot .message-bubble {
        background: white;
        color: #374151;
        border-bottom-left-radius: 6px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .message.user .message-bubble {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-bottom-right-radius: 6px;
    }

    .typing-indicator {
        display: flex;
        align-items: center;
        gap: 4px;
        padding: 12px 16px;
        background: white;
        border-radius: 18px;
        border-bottom-left-radius: 6px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        max-width: fit-content;
    }

    .typing-dot {
        width: 8px;
        height: 8px;
        background: #9ca3af;
        border-radius: 50%;
        animation: typing 1.4s infinite ease-in-out;
    }

    .typing-dot:nth-child(1) { animation-delay: -0.32s; }
    .typing-dot:nth-child(2) { animation-delay: -0.16s; }

    @keyframes typing {
        0%, 80%, 100% {
            transform: scale(0.8);
            opacity: 0.5;
        }
        40% {
            transform: scale(1);
            opacity: 1;
        }
    }

    .quick-replies {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 12px;
    }

    .quick-reply {
        padding: 8px 12px;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .quick-reply:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
    }

    .chat-input {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #e2e8f0;
        border-radius: 25px;
        outline: none;
        font-size: 14px;
        transition: border-color 0.2s ease;
    }

    .chat-input:focus {
        border-color: #667eea;
    }

    .send-button {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .send-button:hover {
        transform: translateY(-50%) scale(1.1);
    }

    .send-button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: translateY(-50%) scale(1);
    }

    /* Mobile responsive */
    @media (max-width: 480px) {
        .chat-widget {
            width: calc(100vw - 32px);
            height: calc(100vh - 140px);
            right: 16px;
            bottom: 90px;
        }

        .chat-button {
            right: 16px;
            bottom: 16px;
        }
    }

    /* Alternative button styles */
    .chat-button.style-minimal {
        background: #ffffff;
        color: #667eea;
        border: 2px solid #667eea;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .chat-button.style-minimal:hover {
        background: #667eea;
        color: white;
    }

    .chat-button.style-dark {
        background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
    }

    .chat-button.style-green {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        box-shadow: 0 8px 32px rgba(16, 185, 129, 0.3);
    }

    .chat-button.style-orange {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        box-shadow: 0 8px 32px rgba(245, 158, 11, 0.3);
    }
</style>
