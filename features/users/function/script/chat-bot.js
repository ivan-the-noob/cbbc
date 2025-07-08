function toggleChat() {
    const chatInterface = document.getElementById('chat-interface');
    chatInterface.classList.toggle('hidden');
}

function typeMessage(message, element, typingSpeed = 100) {
    element.textContent = ""; // Clear any existing text
    let charIndex = 0;

    function type() {
        if (charIndex < message.length) {
            element.textContent += message.charAt(charIndex);
            charIndex++;
            setTimeout(type, typingSpeed); // Adjust typing speed here
        }
    }

    type();
}

document.addEventListener("DOMContentLoaded", function() {
    const initialMessage = "Hello, I am Chat Bot. Please ask me a question just by pressing the question buttons.";
    const typingTextElement = document.getElementById("typing-text");
    typeMessage(initialMessage, typingTextElement, 20); // Typing speed in ms
});

function sendResponse(question) {
    const chatBody = document.getElementById('chat-body');
    const responseContainer = document.createElement('div');
    responseContainer.classList.add('admin', 'mt-3');

    const adminChatBubble = document.createElement('div');
    adminChatBubble.classList.add('admin-chat');

    const adminImage = document.createElement('img');
    adminImage.src = "bg.png";
    adminImage.alt = "Admin";

    const adminName = document.createElement('p');
    adminName.textContent = "Admin";

    adminChatBubble.appendChild(adminImage);
    adminChatBubble.appendChild(adminName);

    const responseText = document.createElement('p');
    responseText.classList.add('text');

    let responseMessage;
    switch (question) {
        case 'Who is CBBC Pastor?':
            responseMessage = 'Pastor Herbert Collano Jr.';
            break;
        case 'Who is CBBC Pastor?':
            responseMessage = 'Pastor Herbert Collano Jr.".';
            break;
        case 'Who is CBBC Pastor?':
            responseMessage = 'Pastor Herbert Collano Jr.".';
            break;
        case 'Who is CBBC Pastor?':
            responseMessage = 'Pastor Herbert Collano Jr.".';
            break;
      
    }

    typeMessage(responseMessage, responseText, 20); 

    responseContainer.appendChild(adminChatBubble);
    responseContainer.appendChild(responseText);

    chatBody.appendChild(responseContainer);

    chatBody.scrollTop = chatBody.scrollHeight;
}