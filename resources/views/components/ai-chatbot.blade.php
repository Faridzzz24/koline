<div x-data="aiChatbotApp()" class="ai-chatbot-root" x-init="initPos()">

    {{-- Floating Draggable Circular Button --}}
    <button type="button"
            @mousedown="startDrag($event)"
            @touchstart="startDrag($event)"
            @click.prevent.stop="handleTriggerClick()"
            x-show="!isOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-50"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-50"
            class="ai-chatbot-trigger"
            :style="hasCustomPos ? ('left: ' + posX + 'px; top: ' + posY + 'px; right: auto; bottom: auto;') : ''"
            aria-label="Tanya KoLine AI"
            title="Tanya KoLine AI (Tahan & Geser untuk memindahkan)">
        <div class="ai-bot-pulse"></div>
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 2a10 10 0 0 1 10 10c0 5.523-4.477 10-10 10a9.96 9.96 0 0 1-4.587-1.112l-3.827 1.056a1 1 0 0 1-1.226-1.226l1.056-3.827A9.96 9.96 0 0 1 2 12C2 6.477 6.477 2 12 2z"/>
            <path d="M8 12h.01M12 12h.01M16 12h.01"/>
        </svg>
    </button>

    {{-- Chat Window Popover --}}
    <div x-show="isOpen"
         x-transition:enter="transition cubic-bezier(0.34, 1.56, 0.64, 1) duration-400"
         x-transition:enter-start="opacity-0 scale-50 translate-y-6"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-50 translate-y-6"
         class="ai-chatbot-window"
         :style="'position: fixed; right: 2rem; bottom: 2rem; z-index: 9999; display: ' + (isOpen ? 'flex' : 'none') + ';'"
         style="display: none;"
         x-cloak>

        {{-- Header --}}
        <div class="ai-chat-header">
            <div class="flex items-center gap-3">
                <div class="ai-bot-avatar">
                    <svg width="20" height="20" fill="none" stroke="#FFFFFF" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M12 2a10 10 0 0 1 10 10c0 5.523-4.477 10-10 10a9.96 9.96 0 0 1-4.587-1.112l-3.827 1.056a1 1 0 0 1-1.226-1.226l1.056-3.827A9.96 9.96 0 0 1 2 12C2 6.477 6.477 2 12 2z"/>
                    </svg>
                </div>
                <div>
                    <div style="font-weight: 800; font-size: 1rem; color: var(--txt-heading);">KoLine AI</div>
                    <div style="font-size: 0.775rem; color: var(--clr-teal-light); font-weight: 600;">Asisten Medis Pintar</div>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" @click.prevent.stop="clearChat()" class="btn-chat-action" title="Bersihkan Percakapan">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
                <button type="button" @click.prevent.stop="closeChat()" class="btn-chat-action" title="Tutup Chat">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        {{-- Messages Container --}}
        <div id="ai-chat-body" class="ai-chat-messages">

            {{-- Welcome Message --}}
            <div class="ai-msg ai-msg-bot">
                <div class="ai-msg-content">
                    Halo! Saya <strong>KoLine AI</strong>, asisten medis Anda.
                    <br><br>
                    Ada pertanyaan seputar gejala penyakit, panduan obat, atau tips hidup sehat yang ingin Anda tanyakan?
                </div>
                <div class="ai-msg-time">Baru saja</div>
            </div>

            {{-- Quick Prompts --}}
            <div x-show="messages.length === 0" class="ai-quick-prompts">
                <div style="font-size: 0.725rem; color: var(--txt-muted); font-weight: 700; margin-bottom: 0.5rem; text-transform: uppercase;">Topik Pertanyaan Cepat:</div>
                <button @click="sendQuick('Sebutkan 3 tips utama menjaga daya tahan tubuh!')" class="quick-chip">💡 Tips Menjaga Imunitas</button>
                <button @click="sendQuick('Bagaimana langkah pertolongan pertama saat demam?')" class="quick-chip">🌡️ Pertolongan Demam</button>
                <button @click="sendQuick('Berapa dosis aman konsumsi Paracetamol untuk dewasa?')" class="quick-chip">💊 Aturan Dosis Paracetamol</button>
            </div>

            {{-- Dynamic Messages --}}
            <template x-for="(msg, index) in messages" :key="index">
                <div class="ai-msg" :class="msg.role === 'user' ? 'ai-msg-user' : 'ai-msg-bot'">
                    <div class="ai-msg-content" x-html="formatMessage(msg.content)"></div>
                    <div class="ai-msg-time" x-text="msg.time"></div>
                </div>
            </template>

            {{-- Typing Indicator --}}
            <div x-show="loading" class="ai-msg ai-msg-bot">
                <div class="ai-msg-content flex items-center gap-2" style="color: var(--clr-teal-light); font-size: 0.85rem;">
                    <div class="typing-dot"></div>
                    <div class="typing-dot" style="animation-delay: 0.2s"></div>
                    <div class="typing-dot" style="animation-delay: 0.4s"></div>
                    <span style="margin-left: 0.5rem;">KoLine AI sedang memproses...</span>
                </div>
            </div>
        </div>

        {{-- Input Form --}}
        <form @submit.prevent="sendMessage()" class="ai-chat-input-bar">
            <input type="text" x-model="input" placeholder="Tanyakan seputar kesehatan..." class="ai-chat-input" :disabled="loading" autocomplete="off">
            <button type="submit" class="btn btn-primary" :disabled="loading || !input.trim()" style="padding: 0 1.25rem; height: 42px; font-size: 0.875rem; gap: 0.5rem; flex-shrink: 0;">
                <span>Kirim</span>
                <svg width="15" height="15" fill="none" stroke="#FFFFFF" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/></svg>
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
function aiChatbotApp() {
    return {
        isOpen: false,
        loading: false,
        input: '',
        messages: [],

        // Draggable Floating Button Position
        hasCustomPos: false,
        posX: 0,
        posY: 0,
        isDragging: false,
        startX: 0,
        startY: 0,
        hasDragged: false,

        initPos() {
            const savedX = sessionStorage.getItem('koline_bot_x');
            const savedY = sessionStorage.getItem('koline_bot_y');
            if (savedX !== null && savedY !== null) {
                this.posX = Math.min(window.innerWidth - 64, Math.max(16, parseInt(savedX)));
                this.posY = Math.min(window.innerHeight - 64, Math.max(16, parseInt(savedY)));
                this.hasCustomPos = true;
            }

            window.addEventListener('resize', () => {
                if (this.hasCustomPos) {
                    this.posX = Math.min(window.innerWidth - 64, this.posX);
                    this.posY = Math.min(window.innerHeight - 64, this.posY);
                }
            });
        },

        startDrag(e) {
            this.isDragging = true;
            this.hasDragged = false;
            
            if (!this.hasCustomPos) {
                const rect = e.currentTarget.getBoundingClientRect();
                this.posX = rect.left;
                this.posY = rect.top;
                this.hasCustomPos = true;
            }

            const clientX = e.type.includes('touch') ? e.touches[0].clientX : e.clientX;
            const clientY = e.type.includes('touch') ? e.touches[0].clientY : e.clientY;
            
            this.startX = clientX - this.posX;
            this.startY = clientY - this.posY;

            const onMove = (moveEv) => {
                if (!this.isDragging) return;
                const curX = moveEv.type.includes('touch') ? moveEv.touches[0].clientX : moveEv.clientX;
                const curY = moveEv.type.includes('touch') ? moveEv.touches[0].clientY : moveEv.clientY;
                
                const deltaX = Math.abs(curX - (this.startX + this.posX));
                const deltaY = Math.abs(curY - (this.startY + this.posY));
                if (deltaX > 3 || deltaY > 3) {
                    this.hasDragged = true;
                }

                // Bound inside window viewport
                const maxX = window.innerWidth - 64;
                const maxY = window.innerHeight - 64;
                
                this.posX = Math.max(16, Math.min(maxX, curX - this.startX));
                this.posY = Math.max(16, Math.min(maxY, curY - this.startY));

                sessionStorage.setItem('koline_bot_x', this.posX);
                sessionStorage.setItem('koline_bot_y', this.posY);
            };

            const onEnd = () => {
                this.isDragging = false;
                window.removeEventListener('mousemove', onMove);
                window.removeEventListener('mouseup', onEnd);
                window.removeEventListener('touchmove', onMove);
                window.removeEventListener('touchend', onEnd);
            };

            window.addEventListener('mousemove', onMove);
            window.addEventListener('mouseup', onEnd);
            window.addEventListener('touchmove', onMove, { passive: false });
            window.addEventListener('touchend', onEnd);
        },

        handleTriggerClick() {
            if (!this.hasDragged) {
                this.openChat();
            }
        },

        openChat() {
            this.isOpen = true;
            this.scrollToBottom();
        },
        closeChat() {
            this.isOpen = false;
        },
        clearChat() {
            if (confirm('Bersihkan riwayat obrolan AI?')) {
                this.messages = [];
            }
        },
        sendQuick(text) {
            this.input = text;
            this.sendMessage();
        },
        async sendMessage() {
            const query = this.input.trim();
            if (!query || this.loading) return;

            const timeStr = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

            // Add user message
            this.messages.push({ role: 'user', content: query, time: timeStr });
            this.input = '';
            this.loading = true;
            this.scrollToBottom();

            try {
                const response = await fetch("{{ route('ai.chat') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        message: query,
                        history: this.messages
                    })
                });

                const data = await response.json();
                this.loading = false;

                if (data.status === 'success') {
                    this.messages.push({
                        role: 'assistant',
                        content: data.reply,
                        time: new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })
                    });
                } else {
                    this.messages.push({
                        role: 'assistant',
                        content: data.reply || 'Maaf, terjadi kendala. Coba ulangi pertanyaan Anda.',
                        time: timeStr
                    });
                }
            } catch (err) {
                this.loading = false;
                this.messages.push({
                    role: 'assistant',
                    content: 'Maaf, gagal menghubungkan ke layanan KoLine AI.',
                    time: timeStr
                });
            }

            this.scrollToBottom();
        },
        formatMessage(content) {
            if (!content) return '';
            let formatted = content
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                .replace(/\*(.*?)\*/g, '<em>$1</em>')
                .replace(/\n\n/g, '<br><br>')
                .replace(/\n/g, '<br>');
            return formatted;
        },
        scrollToBottom() {
            setTimeout(() => {
                const body = document.getElementById('ai-chat-body');
                if (body) body.scrollTop = body.scrollHeight;
            }, 100);
        }
    }
}
</script>
@endpush
