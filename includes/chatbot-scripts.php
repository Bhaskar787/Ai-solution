<script>
    let isOpen = false;
    let messageCount = 0;

    // Toggle chat widget
    function toggleChat() {
        const widget = document.getElementById('chat-widget');
        const button = document.getElementById('chatbot-button');
        const notification = button.querySelector('.notification-dot');

        isOpen = !isOpen;

        if (isOpen) {
            widget.classList.add('active');
            button.classList.add('active');
            if (notification) {
                notification.style.display = 'none';
            }
            // Focus on input when opened
            setTimeout(() => {
                document.getElementById('chat-input').focus();
            }, 300);
        } else {
            widget.classList.remove('active');
            button.classList.remove('active');
        }
    }

    // Handle enter key press
    function handleKeyPress(event) {
        if (event.key === 'Enter') {
            sendMessage();
        }
    }

    // Send message
    function sendMessage() {
        const input = document.getElementById('chat-input');
        const message = input.value.trim();

        if (message) {
            addMessage(message, 'user');
            input.value = '';

            // Show typing indicator
            showTypingIndicator();

            // Simulate bot response
            setTimeout(() => {
                hideTypingIndicator();
                addBotResponse(message);
            }, 1500);
        }
    }

    // Send quick reply
    function sendQuickReply(message) {
        addMessage(message, 'user');

        // Show typing indicator
        showTypingIndicator();

        // Simulate bot response
        setTimeout(() => {
            hideTypingIndicator();
            addBotResponse(message);
        }, 1000);
    }

    // Add message to chat
    function addMessage(text, sender) {
        const messagesContainer = document.getElementById('chat-messages');
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${sender}`;

        if (sender === 'user') {
            messageDiv.innerHTML = `
                <div class="message-bubble">${text}</div>
            `;
        } else {
            messageDiv.innerHTML = `
                <div class="bot-avatar">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>
                <div class="message-bubble">${text}</div>
            `;
        }

        messagesContainer.appendChild(messageDiv);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // Show typing indicator
    function showTypingIndicator() {
        const messagesContainer = document.getElementById('chat-messages');
        const typingDiv = document.createElement('div');
        typingDiv.className = 'message bot';
        typingDiv.id = 'typing-indicator';
        typingDiv.innerHTML = `
            <div class="bot-avatar">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
            </div>
            <div class="typing-indicator">
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
            </div>
        `;

        messagesContainer.appendChild(typingDiv);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // Hide typing indicator
    function hideTypingIndicator() {
        const typingIndicator = document.getElementById('typing-indicator');
        if (typingIndicator) {
            typingIndicator.remove();
        }
    }

    // Add bot response
function addBotResponse(userMessage) {
    let response = '';
    const msg = userMessage.toLowerCase();

    // Greeting
    if (msg.includes('hello') || msg.includes('hi') || msg.includes('hey')) {
        response = "Hello! Great to meet you! 😊 How can I assist you today?";
    } 
    // Pricing
    else if (msg.includes('pricing') || msg.includes('price') || msg.includes('cost')) {
        response = "Our pricing is very competitive! 💰 We offer flexible plans starting from $29/month. Would you like to see a detailed breakdown?";
    } 
    // Support
    else if (msg.includes('support') || msg.includes('help') || msg.includes('issue')) {
        response = "I'm here to help! 🚑 You can reach our support team 24/7. Could you tell me what specific issue you’re facing?";
    } 
    // Get Started
    else if (msg.includes('get started') || msg.includes('start') || msg.includes('begin')) {
        response = "Excellent choice! 🚀 Let’s get you started. Can you share what you’re aiming to achieve first?";
    } 
    // About company
    else if (msg.includes('about') || msg.includes('who are you')) {
        response = "We are AI-Solutions, a startup focused on innovating the digital employee experience through AI-powered tools. 🌍";
    } 
    // Services
    else if (msg.includes('services') || msg.includes('solutions')) {
        response = "We provide AI-powered solutions for industries, including digital prototyping, employee experience management, and workflow automation. ⚡";
    } 
    // Location
    else if (msg.includes('location') || msg.includes('where are you')) {
        response = "We are based in Sunderland, but we work with clients worldwide! 🌎";
    } 
    // Contact
    else if (msg.includes('contact') || msg.includes('email') || msg.includes('phone')) {
        response = "You can reach us anytime via the Contact Us page 📩 or directly at support@ai-solutions.com.";
    } 
    // Careers
    else if (msg.includes('job') || msg.includes('career') || msg.includes('hiring')) {
        response = "We’re always looking for talent! 💼 Check out our Careers page or send us your CV via email.";
    } 
    // Demo
    else if (msg.includes('demo') || msg.includes('trial')) {
        response = "We’d love to show you a demo! 🖥️ Please provide your email and we’ll set it up.";
    } 
    // Events
    else if (msg.includes('event') || msg.includes('conference') || msg.includes('seminar')) {
        response = "We regularly host events to showcase our AI innovations. 🎤 Check the Events page for upcoming sessions.";
    } 
    // Testimonials
    else if (msg.includes('testimonial') || msg.includes('review') || msg.includes('feedback')) {
        response = "Our clients love us! ⭐ You can see ratings and reviews on the Customer Feedback page.";
    } 
    // Articles
    else if (msg.includes('article') || msg.includes('blog') || msg.includes('news')) {
        response = "We share knowledge through articles and blog posts 📚 — check our Articles page for insights.";
    } 
    // Security
    else if (msg.includes('security') || msg.includes('safe')) {
        response = "We take data security seriously 🔒. All information is encrypted and handled responsibly.";
    } 
    // Subscription cancel
    else if (msg.includes('cancel') || msg.includes('unsubscribe')) {
        response = "Sorry to see you go 😢. You can manage or cancel subscriptions directly from your account dashboard.";
    } 
    // Payment methods
    else if (msg.includes('payment') || msg.includes('pay') || msg.includes('credit card')) {
        response = "We accept major credit cards, PayPal, and bank transfers 💳. Which option do you prefer?";
    } 
    // Refund
    else if (msg.includes('refund') || msg.includes('money back')) {
        response = "We offer a 14-day money-back guarantee ✅. Would you like me to guide you through the refund request?";
    } 
    // Training
    else if (msg.includes('training') || msg.includes('tutorial') || msg.includes('learn')) {
        response = "We provide training resources 📘 including video tutorials, webinars, and live sessions.";
    } 
    // Account
    else if (msg.includes('account') || msg.includes('login') || msg.includes('signup')) {
        response = "You don’t need an account to use our Contact form. For admin access, you’ll need credentials. 🔑";
    } 
    // Deadline
    else if (msg.includes('time') || msg.includes('deadline')) {
        response = "Most solutions can be delivered in 2–4 weeks depending on complexity ⏳.";
    } 
    // Partnership
    else if (msg.includes('partner') || msg.includes('collaborate')) {
        response = "We’d love to explore partnerships! 🤝 Could you share more about your company?";
    } 
    // Social media
    else if (msg.includes('linkedin') || msg.includes('twitter') || msg.includes('facebook')) {
        response = "You can follow us on LinkedIn, Twitter, and Facebook 📱 for updates!";
    } 
    // Office hours
    else if (msg.includes('open') || msg.includes('hours')) {
        response = "Our office hours are Monday to Friday, 9am–6pm UK time ⏰.";
    } 
    // Language
    else if (msg.includes('language') || msg.includes('translate')) {
        response = "We currently support English 🌐 but are working on multilingual support soon.";
    } 
    // AI Assistant
    else if (msg.includes('ai') || msg.includes('virtual assistant')) {
        response = "Our AI-powered assistant helps speed up design, prototyping, and problem-solving ⚡.";
    } 
    // Thanks
    else if (msg.includes('thanks') || msg.includes('thank you')) {
        response = "You're welcome! 🙌 I’m always here if you need anything else.";
    } 
    // Goodbye
    else if (msg.includes('bye') || msg.includes('goodbye') || msg.includes('see you')) {
        response = "Goodbye 👋 Have a wonderful day!";
    } 
    // Default fallback (random variety)
    else {
        const responses = [
            "That’s interesting! Tell me more about that 🤔.",
            "I understand. How can I help you with this?",
            "Thanks for sharing that. What would you like to know?",
            "Great question! Let me guide you.",
            "I’m here to assist. What specific information do you need?",
            "Could you clarify a bit more? 🧐",
            "I might need more details to help you better.",
            "That sounds exciting! Can you elaborate?",
            "Hmm, let me think about that 🤖.",
            "I’m not sure I got that, can you rephrase?",
            "Sounds good! Want me to check that for you?",
            "I love that question! Let’s explore it further.",
            "Interesting point! Would you like examples?",
            "That’s something we can definitely help with!",
            "Good one! Let me explain in simple terms.",
            "I’ll make sure to give you the best guidance.",
            "That reminds me of a client project we did!",
            "Would you like me to connect you with a specialist?",
            "This is important — can you share a bit more?",
            "I see where you’re coming from. Let’s dive deeper.",
            "Cool question! I’ll answer step by step.",
            "That’s definitely worth exploring further!",
            "We can solve that using AI-based solutions.",
            "That aligns with our mission to innovate!",
            "I’d recommend starting with our Solutions page.",
            "You can learn more in our Articles section.",
            "Want me to give you a quick summary?",
            "Do you want a simple answer or detailed explanation?",
            "I think I can help — give me a sec ⏳.",
            "We’ve seen this challenge before with other clients.",
            "Yes, we support that feature! 🎉",
            "No worries, I’ll walk you through it.",
            "I’ll try to break this down for you.",
            "Could you confirm if this is for business or personal use?",
            "I’ll give you an overview and then details.",
            "That’s a valid concern — let’s address it.",
            "I’d be happy to expand on that for you.",
            "You’re asking all the right questions!",
            "Perfect timing — we just launched a feature for this.",
            "I appreciate your patience. Let’s solve this.",
            "It’s a common question! Here’s the answer.",
            "We’ve researched this a lot. Here’s what we found.",
            "Here’s what most of our customers do.",
            "Let me summarize our approach.",
            "Want me to provide some examples?",
            "That’s something we care deeply about.",
            "I’ll note this down for follow-up.",
            "This is a bit complex but I’ll explain simply.",
            "Definitely possible! Would you like the steps?",
            "That’s an area where we shine the most 💡."
        ];
        response = responses[Math.floor(Math.random() * responses.length)];
    }

    addMessage(response, 'bot');
}

    // Change chatbot style
    function changeStyle(style) {
        const button = document.getElementById('chatbot-button');

        // Remove all style classes
        button.classList.remove('style-minimal', 'style-dark', 'style-green', 'style-orange');

        // Add new style class
        if (style !== 'default') {
            button.classList.add(`style-${style}`);
        }
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        // Add some demo messages if needed
        console.log('Chatbot initialized successfully!');
    });
</script>
