<script setup lang="ts">
import { ref, onMounted, nextTick, watch } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import axios from 'axios';

const props = defineProps({
    initialChats: Array,
    user1: Object, // user yang sedang login
    user2: Object,
});

const message = ref('');
const sending = ref(false);
const chatContainer = ref(null);
const file = ref<File|null>(null);
const chats = ref<Array<any>>(props.initialChats || []);
const isLoading = ref(true);

// Fungsi untuk mengambil chat
const fetchChats = async () => {
    isLoading.value = true;
    try {
        const response = await axios.get(`/api/chat/${props.user2.id}`);
        chats.value = response.data.chats;
    } catch (error) {
        console.error('Failed to fetch chats:', error);
    } finally {
        isLoading.value = false;
    }
};

function handleFileChange(e: Event) {
    const target = e.target as HTMLInputElement;
    file.value = target.files && target.files[0] ? target.files[0] : null;
}

async function sendMessage() {
    if (!message.value.trim() && !file.value) return;
    
    sending.value = true;
    const formData = new FormData();
    formData.append('recipient_id', props.user2.id.toString());
    formData.append('message', message.value);
    if (file.value) {
        formData.append('file', file.value);
    }

    try {
        const response = await axios.post('/api/chat/send', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        // Tambahkan chat baru ke daftar
        chats.value.push(response.data.chat);
        // Reset form
        message.value = '';
        file.value = null;
        const fileInput = document.getElementById('file-input') as HTMLInputElement;
        if (fileInput) fileInput.value = '';
        // Scroll ke bawah
        nextTick(() => {
            if (chatContainer.value) {
                chatContainer.value.scrollTop = chatContainer.value.scrollHeight;
            }
        });
    } catch (error) {
        console.error('Failed to send message:', error);
    } finally {
        sending.value = false;
    }
}

// Auto-scroll saat chat berubah
watch(chats, () => {
    nextTick(() => {
        if (chatContainer.value) {
            chatContainer.value.scrollTop = chatContainer.value.scrollHeight;
        }
    });
}, { deep: true });

onMounted(() => {
    fetchChats();
    nextTick(() => {
        if (chatContainer.value) {
            chatContainer.value.scrollTop = chatContainer.value.scrollHeight;
        }
    });
});
</script>

<template>
    <Head title="Chat" />
    <AppLayout>
        <div class="lg:w-[950px] sm:w-[500px] mx-auto py-8">
            <div class="flex items-center gap-3 pb-4 border-b border-sidebar-border/70 dark:border-sidebar-border">
                <img
                    :src="user2.avatar ? '/storage/' + user2.avatar : `https://ui-avatars.com/api/?name=${user2.name}`"
                    :alt="user2.name"
                    class="w-10 h-10 rounded-full border-2"
                />
                <div>
                    <h2 class="font-bold text-lg">{{ user2.name }}</h2>
                </div>
            </div>
            <div
                ref="chatContainer"
                class="bg-gray-100 rounded-lg p-4 h-[450px] overflow-y-auto mb-4 flex flex-col gap-3 relative"
                style="min-width: 0;"
            >
                <!-- Loading Spinner -->
                <div v-if="isLoading" class="absolute inset-0 flex flex-col items-center justify-center bg-gray-100 bg-opacity-80 z-10">
                    <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-[#AE7A42] mb-4"></div>
                    <p class="text-black mt-2">Loading message...</p>
                </div>
                <!-- Empty State -->
                <div v-else-if="chats.length === 0" class="flex flex-col items-center justify-center h-full text-center">
                    <div class="rounded-full h-12 w-12 border-2 border-dashed border-[#AE7A42] flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-[#AE7A42]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"/>
                        </svg>
                    </div>
                    <p class="text-gray-600">Belum ada pesan, silahkan mulai mengirim pesan baru.</p>
                </div>
                <!-- Chat List -->
                <template v-else>
                    <div
                        v-for="chat in chats"
                        :key="chat.id"
                        class="flex items-end"
                        :class="chat.sender_id === user2.id ? 'justify-start' : 'justify-end'"
                    >
                        <!-- Avatar kiri untuk recipient, kanan untuk sender -->
                        <template v-if="chat.sender_id === user2.id">
                            <img
                                :src="user2.avatar ? '/storage/' + user2.avatar : `https://ui-avatars.com/api/?name=${user2.name}`"
                                :alt="user2.name"
                                class="w-8 h-8 rounded-full mr-2 border flex-shrink-0"
                            />
                        </template>
                        <div
                            :class="[
                                'px-4 py-2 rounded-lg break-words',
                                'shadow',
                                chat.sender_id === user2.id
                                    ? 'bg-[#AE7A42] text-white rounded-bl-none'
                                    : 'bg-white text-gray-900 border border-gray-300 rounded-br-none'
                            ]"
                            style="max-width: 70%;"
                        >
                            <div class="text-sm">
                                {{ chat.message }}
                                <template v-if="chat.file_path">
                                    <br>
                                    <a
                                        :href="`/storage/${chat.file_path}`"
                                        target="_blank"
                                        rel="noopener"
                                        class="underline font-semibold"
                                    >View File</a>
                                </template>
                            </div>
                            <div
                                class="text-xs mt-1 text-right"
                                :class="chat.sender_id === user2.id ? 'text-white' : 'text-gray-900'"
                            >
                                {{
                                    new Date(chat.created_at).toLocaleString('id-ID', {
                                        day: 'numeric',
                                        month: 'long',
                                        year: 'numeric',
                                        hour: '2-digit',
                                        minute: '2-digit',
                                        hour12: false
                                    })
                                }}
                            </div>
                        </div>
                        <template v-if="chat.sender_id === user1.id">
                            <img
                                :src="user1.avatar ? '/storage/' + user1.avatar : `https://ui-avatars.com/api/?name=${user1.name}`"
                                :alt="user1.name"
                                class="w-8 h-8 rounded-full ml-2 border flex-shrink-0"
                            />
                        </template>
                    </div>
                </template>
            </div>
            <form @submit.prevent="sendMessage" class="flex gap-2 mb-4 sm:mb-0 items-center">
                <!-- File name (if selected) -->
                <span v-if="file" class="truncate max-w-[120px] text-xs text-gray-700">{{ file.name }}</span>
                <!-- Paperclip icon as file input trigger -->
                <label class="cursor-pointer flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-500 hover:text-[#AE7A42]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.24 7.76l-7.07 7.07a3 3 0 104.24 4.24l7.07-7.07a5 5 0 10-7.07-7.07l-8.49 8.49"/>
                    </svg>
                    <input
                        id="file-input"
                        type="file"
                        class="hidden"
                        @change="handleFileChange"
                        :disabled="sending"
                    />
                </label>
                <input
    v-model="message"
    type="text"
    class="flex-1 px-4 py-2 border rounded-lg focus:border-[#AE7A42] focus:ring-[#AE7A42]"
    placeholder="Type your message..."
    :disabled="sending"
    autocomplete="off"
/>
                <button
                    type="submit"
                    class="px-6 py-2 bg-[#AE7A42] hover:bg-[#8c5e30] text-white rounded-lg font-semibold"
                    :disabled="sending || (!message.trim() && !file)"
                >
                    {{ sending ? 'Sending...' : 'Send' }}
                </button>
            </form>
        </div>
    </AppLayout>
</template>