<div id="chat-widget" style="position: fixed; bottom: 24px; right: 24px; z-index: 40; font-family: 'Inter', sans-serif; pointer-events: none;">

    <!-- Chat Button -->
    <button id="chat-toggle" 
            onclick="toggleChat()"
            style="width: 56px; height: 56px; background: #10b981; color: white; border-radius: 9999px; border: none; cursor: pointer; box-shadow: 0 10px 25px rgba(0,0,0,0.2); display: flex; align-items: center; justify-content: center; transition: transform 0.2s; pointer-events: auto;"
            onmouseover="this.style.transform='scale(1.1)'"
            onmouseout="this.style.transform='scale(1)'">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 24px; height: 24px;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
        </svg>
    </button>

    <!-- Chat Window -->
    <div id="chat-window"
         style="position: fixed; bottom: 90px; right: 24px; width: 384px; max-height: calc(100vh - 200px); background: white; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); display: none; flex-direction: column; overflow: hidden; pointer-events: auto; z-index: 50;">

        <!-- Header -->
        <div style="background: linear-gradient(to right, #10b981, #059669); color: white; padding: 16px; display: flex; align-items: center; justify-content: space-between; flex-shrink: 0;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 24px; height: 24px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                    </svg>
                </div>
                <div>
                    <h3 style="font-weight: bold; font-size: 16px; margin: 0;">Health Assistant</h3>
                    <p style="font-size: 12px; color: rgba(255,255,255,0.8); margin: 0;">Siap membantu Anda</p>
                </div>
            </div>
            <button onclick="toggleChat()"
                    style="background: none; border: none; color: white; cursor: pointer; font-size: 24px; padding: 0; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center;">×</button>
        </div>

        <!-- Messages Container -->
        <div id="messages-container"
             style="flex: 1; overflow-y: auto; padding: 16px; background: #f9fafb; display: flex; flex-direction: column; gap: 16px;">
            <!-- Initial greeting -->
            <div style="display: flex; align-items: flex-end; gap: 8px;">
                <div style="width: 32px; height: 32px; border-radius: 50%; background: #10b981; color: white; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: bold; flex-shrink: 0;">AI</div>
                <div style="background: white; color: #1f2937; border-radius: 8px; border-bottom-left-radius: 0; padding: 12px 16px; max-width: 260px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    <p style="font-size: 14px; font-weight: bold; margin: 0 0 4px 0;">Halo! 👋</p>
                    <p style="font-size: 14px; margin: 0 0 8px 0;">Selamat datang di MedicStore Health Assistant. Ada yang bisa saya bantu?</p>
                    <p style="font-size: 12px; color: #9ca3af; margin: 0 0 4px 0;">Tanyakan tentang:</p>
                    <ul style="font-size: 12px; color: #4b5563; margin: 4px 0 0 0; padding-left: 16px;">
                        <li>Gejala & rekomendasi obat</li>
                        <li>Status pesanan Anda</li>
                        <li>Cara upload resep</li>
                        <li>FAQ & pertanyaan umum</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div style="border-top: 1px solid #e5e7eb; padding: 16px; background: white; flex-shrink: 0; display: flex; gap: 8px;">
            <input type="text" 
                   id="chat-input"
                   placeholder="Ketik pesan Anda..." 
                   style="flex: 1; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; font-family: 'Inter', sans-serif; outline: none;"
                   onkeypress="handleEnter(event)">
            <button id="send-btn"
                    onclick="sendChatMessage()"
                    style="background: #10b981; color: white; padding: 8px 12px; border: none; border-radius: 8px; cursor: pointer; font-weight: 500; transition: background 0.2s; display: flex; align-items: center; justify-content: center;"
                    onmouseover="this.style.background='#059669'"
                    onmouseout="this.style.background='#10b981'">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                </svg>
            </button>
        </div>
    </div>
</div>

<script>
function toggleChat() {
    const chatWindow = document.getElementById('chat-window');
    const chatToggle = document.getElementById('chat-toggle');
    
    if (chatWindow.style.display === 'flex') {
        chatWindow.style.display = 'none';
    } else {
        chatWindow.style.display = 'flex';
        // Focus input
        setTimeout(() => {
            document.getElementById('chat-input').focus();
        }, 100);
    }
}

function handleEnter(event) {
    if (event.key === 'Enter' && !event.shiftKey) {
        event.preventDefault();
        sendChatMessage();
    }
}

async function sendChatMessage() {
    const input = document.getElementById('chat-input');
    const message = input.value.trim();
    
    if (!message) return;
    
    const container = document.getElementById('messages-container');
    input.value = '';
    input.focus();
    
    // User message
    const userDiv = document.createElement('div');
    userDiv.style.cssText = 'display: flex; justify-content: flex-end;';
    userDiv.innerHTML = `
        <div style="background: #10b981; color: white; border-radius: 8px; border-bottom-right-radius: 0; padding: 12px 16px; max-width: 260px; font-size: 14px; word-wrap: break-word;">
            ${escapeHtml(message)}
        </div>
    `;
    container.appendChild(userDiv);
    
    // Scroll to bottom
    setTimeout(() => {
        container.scrollTop = container.scrollHeight;
    }, 0);
    
    try {
        const response = await fetch('{{ route("chat.send") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ 
                message: message,
                user_id: {{ auth()->check() ? auth()->user()->id : 'null' }}
            })
        });

        if (!response.ok) {
            throw new Error('Network error');
        }

        const data = await response.json();
        
        const botDiv = document.createElement('div');
        botDiv.style.cssText = 'display: flex; align-items: flex-end; gap: 8px;';
        botDiv.innerHTML = `
            <div style="width: 32px; height: 32px; border-radius: 50%; background: #10b981; color: white; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: bold; flex-shrink: 0;">AI</div>
            <div style="background: white; color: #1f2937; border-radius: 8px; border-bottom-left-radius: 0; padding: 12px 16px; max-width: 260px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); font-size: 14px; word-wrap: break-word;">
                ${escapeHtml(data.reply || 'Maaf, terjadi kesalahan.')}
            </div>
        `;
        container.appendChild(botDiv);
        
        // Scroll to bottom
        container.scrollTop = container.scrollHeight;
    } catch (error) {
        console.error('Error:', error);
        const errorDiv = document.createElement('div');
        errorDiv.style.cssText = 'display: flex; align-items: flex-end; gap: 8px;';
        errorDiv.innerHTML = `
            <div style="width: 32px; height: 32px; border-radius: 50%; background: #ef4444; color: white; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: bold; flex-shrink: 0;">!</div>
            <div style="background: #fee2e2; color: #991b1b; border-radius: 8px; border-bottom-left-radius: 0; padding: 12px 16px; max-width: 260px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); font-size: 14px;">
                Terjadi kesalahan. Silakan coba lagi.
            </div>
        `;
        container.appendChild(errorDiv);
        container.scrollTop = container.scrollHeight;
    }
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>
