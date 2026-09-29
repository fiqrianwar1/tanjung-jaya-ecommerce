<div x-data="chatbotWidget()" class="fixed z-50" style="bottom: 2rem; right: 2rem;">
    
    <!-- Chat Button -->
    <button @click="isOpen = !isOpen" type="button" aria-label="Buka chat Tanjung Jaya" hover:bg-emerald-700 text-white rounded-full shadow-2xl flex items-center justify-center transition-transform transform hover:scale-110 focus:outline-none focus:ring-4 focus:ring-emerald-300">
        <svg x-show="!isOpen" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
        <svg x-show="isOpen" x-cloak class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
    </button>

    <!-- Chat Box -->
    <div x-show="isOpen" x-cloak x-transition.scale.origin.bottom.right class="bg-white rounded-2xl shadow-2xl border border-slate-100 flex flex-col overflow-hidden" style="position: absolute; bottom: 4.5rem; right: 0; width: 350px; height: 500px; max-height: calc(100vh - 8rem); max-width: calc(100vw - 4rem);">

        <!-- Header -->
        <div class="bg-gradient-to-r from-emerald-600 to-teal-600 p-4 flex items-center justify-between text-white">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <h4 class="font-bold">Tanjung Jaya AI</h4>
                    <p class="text-xs text-emerald-100">Tanya harga, stok & rekomendasi produk</p>
                </div>
            </div>
            <button @click="isOpen = false" class="text-emerald-100 hover:text-white focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- Messages Area -->
        <div x-ref="chatContainer" class="flex-1 p-4 overflow-y-auto bg-slate-50 space-y-4">
            <template x-for="(msg, index) in messages" :key="index">
                <div class="flex" :class="msg.role === 'user' ? 'justify-end' : 'justify-start'">
                    <!-- Assistant Message -->
                    <div x-show="msg.role === 'assistant'" class="flex items-start max-w-[85%]">
                        <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mr-2 shrink-0 mt-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <div class="bg-white border border-slate-100 p-3 rounded-2xl rounded-tl-sm shadow-sm text-sm text-slate-700" x-text="msg.content"></div>
                    </div>

                    <!-- User Message -->
                    <div x-show="msg.role === 'user'" class="bg-emerald-600 text-white p-3 rounded-2xl rounded-tr-sm shadow-sm text-sm max-w-[85%]" x-text="msg.content"></div>
                </div>
            </template>

            <!-- Loading indicator -->
            <div x-show="isLoading" class="flex justify-start">
                <div class="flex items-start max-w-[85%]">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mr-2 shrink-0 mt-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <div class="bg-white border border-slate-100 p-4 rounded-2xl rounded-tl-sm shadow-sm text-sm text-slate-700 flex space-x-1 items-center">
                        <div class="w-2 h-2 bg-slate-300 rounded-full animate-bounce"></div>
                        <div class="w-2 h-2 bg-slate-300 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                        <div class="w-2 h-2 bg-slate-300 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="p-3 bg-white border-t border-slate-100">
            <!-- Saran pertanyaan cepat -->
            <div class="flex gap-2 overflow-x-auto pb-2 hide-scrollbar">
                <template x-for="(prompt, idx) in quickPrompts" :key="idx">
                    <button type="button" @click="sendQuick(prompt)" :disabled="isLoading" class="shrink-0 text-[11px] font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-100 rounded-full px-3 py-1.5 transition disabled:opacity-50" x-text="prompt"></button>
                </template>
            </div>

            <form @submit.prevent="sendMessage" class="flex items-center relative">
                <input type="text" x-model="newMessage" aria-label="Tulis pesan untuk asisten Tanjung Jaya" placeholder="Ketik pesan Anda..." class="w-full pl-4 pr-12 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm bg-slate-50" :disabled="isLoading">
                <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg flex items-center justify-center transition-colors" :disabled="isLoading || newMessage.trim() === ''">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </form>
            <div class="text-[10px] text-center text-slate-400 mt-2">
                Powered by Groq
            </div>
        </div>
    </div>
</div>
{{-- Logika widget dipisah dari atribut x-data supaya tidak bentrok dengan escaping Blade
     (karakter apostrof pada teks Indonesia merusak atribut HTML dan mematikan x-data). --}}
<script>
    if (typeof window.chatbotWidget !== 'function') {
        window.chatbotWidget = function () {
            return {
                isOpen: false,
                messages: [
                    {
                        role: 'assistant',
                        content: 'Halo bro! Ada yang bisa saya bantu hari ini? Tanya saja soal harga, stok, atau rekomendasi produk Tanjung Jaya.',
                        isGreeting: true,
                    },
                ],
                newMessage: '',
                isLoading: false,

                quickPrompts: [
                    'Rekomendasi produk murah',
                    'Cek harga cat tembok',
                    'Barang apa yang stoknya tersedia?',
                    'Bagaimana cara checkout?',
                ],

                sendQuick(text) {
                    if (this.isLoading) return;
                    this.newMessage = text;
                    this.sendMessage();
                },

                csrfToken() {
                    const meta = document.querySelector('meta[name="csrf-token"]');
                    return meta ? meta.getAttribute('content') : '';
                },

                sendMessage() {
                    if (this.isLoading) return;

                    const userMessage = (this.newMessage || '').trim();
                    if (userMessage === '') return;

                    // Riwayat percakapan sebelumnya (tanpa pesan sapaan awal)
                    const history = this.messages
                        .filter((msg) => ! msg.isGreeting)
                        .map((msg) => ({ role: msg.role, content: msg.content }));

                    this.messages.push({ role: 'user', content: userMessage });
                    this.newMessage = '';
                    this.isLoading = true;

                    this.$nextTick(() => this.scrollToBottom());

                    fetch('/chat', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken(),
                        },
                        body: JSON.stringify({ message: userMessage, history: history }),
                    })
                        .then(async (response) => {
                            const data = await response.json().catch(() => ({}));

                            if (! response.ok) {
                                throw new Error(data.message || data.response || 'Gagal menghubungi server');
                            }

                            return data;
                        })
                        .then((data) => {
                            this.messages.push({
                                role: 'assistant',
                                content: data.response || 'Maaf bro, saya belum bisa menjawab itu. Coba tanyakan hal lain seputar produk Tanjung Jaya ya.',
                            });
                        })
                        .catch((error) => {
                            console.error('Chatbot error:', error);
                            this.messages.push({
                                role: 'assistant',
                                content: 'Maaf bro, lagi ada gangguan sistem. Coba lagi nanti ya!',
                            });
                        })
                        .finally(() => {
                            this.isLoading = false;
                            this.$nextTick(() => this.scrollToBottom());
                        });
                },

                scrollToBottom() {
                    const container = this.$refs.chatContainer;
                    if (container) {
                        container.scrollTop = container.scrollHeight;
                    }
                },
            };
        };
    }
</script>