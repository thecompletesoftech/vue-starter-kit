<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { channels as channelsRoute } from '@/routes'
import { type BreadcrumbItem } from '@/types'
import { Head, usePage, router } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import { Input } from '@/components/ui/input';
type Channel = {
    id: number
    name: string
    description: string
}

// This would typically come from your database
type ChannelSetting = {
    userId: number
    channelName: string
    apiKey: string
    apiSecret: string
}

const page = usePage()
const userId = computed(() => page.props.auth.user.id) // Assuming user is in auth props
const channelKeys = computed(() => (page.props.channel_keys || []) as ChannelSetting[])

const channels = ref<Channel[]>([
    { id: 1, name: 'Flipkart', description: 'Company-wide announcements and work-based matters.' },
    { id: 2, name: 'Amazon', description: 'Talk shop about code, tooling, and releases.' },
    { id: 3, name: 'Meeso', description: 'Feedback, reviews, and inspiration.' },
])

const open = ref(false)
const active = ref<Channel | null>(null)

// Refs for the input fields in the modal
const apiKey = ref('')
const apiSecret = ref('')

function openModal(channel: Channel) {
    active.value = channel
    open.value = true

    // If opening Flipkart, check for existing settings and pre-fill the form
    if (channel.name === 'Flipkart') {
        const existingSetting = channelKeys.value.find(
            (s) => s.channelName === 'Flipkart',
        )
        if (existingSetting) {
            apiKey.value = existingSetting.apiKey
            apiSecret.value = existingSetting.apiSecret
        }
    }
}

function closeModal() {
    open.value = false
    active.value = null
    // Reset form fields on close
    apiKey.value = ''
    apiSecret.value = ''
}

function saveSettings() {
    if (!active.value) return

    const channelName = active.value.name
    const newSetting = {
        channelName: channelName,
        apiKey: apiKey.value,
        apiSecret: apiSecret.value,
    }

    router.post(route('channel.keys.store'), newSetting, {
        onSuccess: () => closeModal(),
    });
}
</script>

<template>

    <Head title="Channels" />

    <AppLayout :breadcrumbs="([{ title: 'Channels', href: channelsRoute().url }] as BreadcrumbItem[])">
        <div class="p-4">
            <h1 class="text-2xl font-semibold mb-4">Channels</h1>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <button v-for="ch in channels" :key="ch.id" type="button"
                    class="text-left rounded-lg border border-gray-200 dark:border-gray-800 p-4 hover:shadow transition"
                    @click="openModal(ch)">
                    <div class="text-lg font-medium">{{ ch.name }}</div>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
                        {{ ch.description }}
                    </p>
                </button>
            </div>

            <div v-if="open" class="fixed inset-0 z-50 flex items-center justify-center" aria-modal="true"
                role="dialog">
                <div class="absolute inset-0 bg-black/50" @click="closeModal" />
                <div class="relative bg-white dark:bg-neutral-900 rounded-lg shadow-xl w-full max-w-lg p-6 mx-4">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="text-xl font-semibold">{{ active?.name }}</h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ active?.description }}</p>
                        </div>
                        <button type="button" class="rounded p-1 hover:bg-gray-100 dark:hover:bg-neutral-800"
                            @click="closeModal" aria-label="Close">
                            <span class="inline-block">✕</span>
                        </button>
                    </div>

                    <!-- DYNAMIC CONTENT START -->
                    <div class="mt-4">
                        <!-- Flipkart Specific Form -->
                        <div v-if="active?.name === 'Flipkart'" class="space-y-4">
                            <div>
                                <label for="apiKey"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">API Key (Client
                                    ID)</label>
                                <Input id="apiKey" type="text" v-model="apiKey" name="apiKey" required autofocus
                                    :tabindex="0"  placeholder="ApiKey" />
                            </div>
                            <div>
                                <label for="apiSecret"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">API
                                    Secret</label>
                                      <Input id="apiSecret" type="text" v-model="apiSecret" name="apiSecret" required autofocus
                                    :tabindex="1"  placeholder="Api Secret" />
                            </div>
                        </div>

                        <!-- Default Content for other channels -->
                        <div v-else>
                            <p class="text-sm">This is a placeholder modal for the selected channel.</p>
                        </div>
                    </div>
                    <!-- DYNAMIC CONTENT END -->


                    <div class="mt-6 flex justify-end gap-2">
                        <button type="button" class="rounded-md border border-gray-300 dark:border-gray-700 px-3 py-1.5"
                            @click="closeModal">Close</button>
                        <button v-if="active?.name === 'Flipkart'" type="button"
                            class="rounded-md bg-gray-900 text-white dark:bg-white dark:text-black px-3 py-1.5"
                            @click="saveSettings">Save</button>
                        <button v-else type="button"
                            class="rounded-md bg-gray-900 text-white dark:bg-white dark:text-black px-3 py-1.5"
                            @click="closeModal">Continue</button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>