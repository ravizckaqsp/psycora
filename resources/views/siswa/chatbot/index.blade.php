<x-siswa-layout>
    <x-slot name="pageTitle">PsyBot</x-slot>
    <x-slot name="pageSubtitle">Asisten edukatif kesehatan psikososial & penggunaan smartphone</x-slot>

    <div class="max-w-2xl mx-auto flex flex-col" style="height: calc(100vh - 180px); min-height: 500px;">

        {{-- Chat Container --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm flex flex-col flex-1 overflow-hidden">

            {{-- Chat Header --}}
            <div class="flex items-center gap-3 px-5 py-4 border-b border-slate-100">
                <div class="relative flex-shrink-0">
                    <div class="w-10 h-10 rounded-xl bg-violet-400 flex items-center justify-center text-white text-lg shadow-md shadow-violet-100">
                        🤖
                    </div>
                    <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-violet-400 rounded-full border-2 border-white"></span>
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-800">Psybot</p>
                    <p class="text-xs text-violet-600 font-medium">● Online · Siap membantu</p>
                </div>
                <div class="ml-auto">
                    <span class="bg-violet-100 text-violet-700 text-[10px] font-bold px-2.5 py-1 rounded-full">Chatbot Edukatif</span>
                </div>
            </div>

            {{-- Messages area --}}
            <div id="chatMessages" class="flex-1 overflow-y-auto px-4 py-4 space-y-4 scroll-smooth">

                {{-- Welcome message --}}
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-xl bg-violet-400 flex items-center justify-center text-sm flex-shrink-0 shadow-sm text-white">🤖</div>
                    <div class="max-w-[85%]">
                        <div class="bg-slate-100 rounded-2xl rounded-tl-sm px-4 py-3 text-sm text-slate-700 leading-relaxed">
                            Halo! 👋 Saya <strong>PsyBot</strong>, asisten kesehatan psikososialmu.<br><br>
                            Saya bisa membantu kamu tentang:<br>
                            📱 Penggunaan gadget sehat<br>
                            🧠 Kesehatan psikososial<br>
                            ⏰ Manajemen waktu<br>
                            🌟 Aktivitas positif<br><br>
                            Mau tanya apa hari ini? 😊
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1 ml-1">PsyBot · Baru saja</p>
                    </div>
                </div>
            </div>

            {{-- Quick Replies --}}
            <div id="quickReplies" class="px-4 py-3 border-t border-slate-100">
                <p class="text-[10px] text-slate-400 font-medium mb-2 uppercase tracking-wide">Pertanyaan Cepat</p>
                <div class="flex gap-2 overflow-x-auto pb-1 max-w-full scrollbar-thin">
                    @foreach($quickReplies as $qr)
                        <button onclick="sendQuickReply(this)"
                                data-msg="{{ $qr }}"
                                class="bg-slate-100 hover:bg-violet-100 hover:text-violet-700 text-slate-600 text-xs font-medium px-3 py-1.5 rounded-full border border-slate-200 hover:border-violet-300 transition-all duration-200 whitespace-nowrap flex-shrink-0">
                            {{ $qr }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Input area --}}
            <div class="px-4 py-3 border-t border-slate-100 bg-white">
                <div class="flex items-end gap-2">
                    <div class="flex-1 relative">
                        <textarea id="chatInput"
                                  rows="1"
                                  placeholder="Ketik pertanyaanmu di sini..."
                                  class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:border-violet-400 focus:ring-2 focus:ring-violet-100 resize-none transition-all duration-200"
                                  onkeydown="handleKeyDown(event)"
                                  oninput="autoResize(this)"></textarea>
                    </div>
                    <button id="sendBtn"
                            onclick="sendMessage()"
                            class="flex-shrink-0 w-11 h-11 bg-violet-400 text-white rounded-xl flex items-center justify-center shadow-md shadow-violet-100 hover:bg-violet-500 active:scale-95 transition-all duration-200 disabled:opacity-50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                    </button>
                </div>
                <p class="text-[10px] text-slate-400 text-center mt-2">PsyBot memberikan informasi edukatif, bukan pengganti konsultasi profesional.</p>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    const messagesDiv = document.getElementById('chatMessages');
    const chatInput   = document.getElementById('chatInput');
    const sendBtn     = document.getElementById('sendBtn');
    const csrfToken   = document.querySelector('meta[name="csrf-token"]').content;

    function autoResize(el) {
        el.style.height = 'auto';
        el.style.height = Math.min(el.scrollHeight, 120) + 'px';
    }

    function handleKeyDown(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    }

    function sendQuickReply(btn) {
        const msg = btn.dataset.msg;
        chatInput.value = msg;
        sendMessage();
    }

    function scrollToBottom() {
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    }

    function formatTime() {
        return new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
    }

    function appendMessage(content, isUser = false) {
        const time = formatTime();
        const div  = document.createElement('div');
        div.className = 'flex items-start gap-3 ' + (isUser ? 'flex-row-reverse' : '');

        if (isUser) {
            div.innerHTML = `
                <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-violet-400 to-pink-400 flex items-center justify-center text-white text-xs font-bold flex-shrink-0 shadow-sm">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="max-w-[85%]">
                    <div class="bg-violet-100 text-slate-800 rounded-2xl rounded-tr-sm px-4 py-3 text-sm leading-relaxed">
                        ${escapeHtml(content)}
                    </div>
                    <p class="text-[10px] text-slate-400 mt-1 mr-1 text-right">Kamu · ${time}</p>
                </div>`;
        } else {
            // Render markdown-like formatting
            const rendered = renderMarkdown(content);
            div.innerHTML = `
                <div class="w-8 h-8 rounded-xl bg-violet-400 flex items-center justify-center text-sm flex-shrink-0 shadow-sm text-white">🤖</div>
                <div class="max-w-[85%]">
                    <div class="bg-slate-100 rounded-2xl rounded-tl-sm px-4 py-3 text-sm text-slate-700 leading-relaxed">
                        ${rendered}
                    </div>
                    <p class="text-[10px] text-slate-400 mt-1 ml-1">PsyBot · ${time}</p>
                </div>`;
        }

        messagesDiv.appendChild(div);
        scrollToBottom();
    }

    function appendTyping() {
        const div = document.createElement('div');
        div.id = 'typingIndicator';
        div.className = 'flex items-start gap-3';
        div.innerHTML = `
            <div class="w-8 h-8 rounded-xl bg-violet-400 flex items-center justify-center text-sm flex-shrink-0 text-white">🤖</div>
            <div class="bg-slate-100 rounded-2xl rounded-tl-sm px-4 py-3">
                <div class="flex gap-1 items-center">
                    <div class="w-2 h-2 bg-slate-400 rounded-full animate-bounce" style="animation-delay:0ms"></div>
                    <div class="w-2 h-2 bg-slate-400 rounded-full animate-bounce" style="animation-delay:150ms"></div>
                    <div class="w-2 h-2 bg-slate-400 rounded-full animate-bounce" style="animation-delay:300ms"></div>
                </div>
            </div>`;
        messagesDiv.appendChild(div);
        scrollToBottom();
    }

    function removeTyping() {
        const el = document.getElementById('typingIndicator');
        if (el) el.remove();
    }

    function escapeHtml(str) {
        return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/\n/g,'<br>');
    }

    function renderMarkdown(text) {
        return text
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
            .replace(/\*(.*?)\*/g, '<em>$1</em>')
            .replace(/^> (.+)\n?/gm, '<blockquote class="border-l-4 border-violet-400 pl-3 text-slate-600 italic my-1">$1</blockquote>')
            .replace(/^• (.+)\n?/gm, '<div class="flex gap-2 my-0.5"><span class="text-violet-500">•</span><span>$1</span></div>')
            .replace(/^(\d+)\. (.+)\n?/gm, '<div class="flex gap-2 my-0.5"><span class="text-violet-500 font-bold">$1.</span><span>$2</span></div>')
            .replace(/\n/g, '<br>');
    }

    async function sendMessage() {
        const msg = chatInput.value.trim();
        if (!msg) return;

        chatInput.value = '';
        chatInput.style.height = 'auto';
        sendBtn.disabled = true;

        appendMessage(msg, true);
        appendTyping();

        try {
            const res = await fetch('{{ route("siswa.chatbot.chat") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ message: msg }),
            });

            const data = await res.json();
            await new Promise(r => setTimeout(r, 600)); // simulate typing delay
            removeTyping();

            if (data.success) {
                appendMessage(data.response);
            } else {
                appendMessage('Maaf, terjadi kesalahan. Coba lagi ya! 😊');
            }
        } catch (e) {
            removeTyping();
            appendMessage('Koneksi bermasalah. Periksa internet kamu dan coba lagi.');
        }

        sendBtn.disabled = false;
        chatInput.focus();
    }

    scrollToBottom();
    chatInput.focus();
    </script>
    @endpush
</x-siswa-layout>
