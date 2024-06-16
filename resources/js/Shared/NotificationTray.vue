<template>
    <li class="relative">
        <button
            class="notifiation-toggle-button"
            @click="setIsOpen(true)"
            aria-label="Notifications"
            aria-haspopup="true"
            >
            <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
            </svg>
            <!-- Notification badge -->
            <span v-if="$page.props.auth.notifications.unreadCount > 0" aria-hidden="true" class="notification-badge">{{ $page.props.auth.notifications.unreadCount }}</span>
        </button>
    </li>

    
    <TransitionRoot appear as="template" :show="isOpen">
        <Dialog :open="isOpen" @close="setIsOpen" class="relative z-50">
            <!-- Backdrop Overlay -->
            <TransitionChild 
                as="template"
                enter="transition-opacity ease-linear duration-300"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="transition-opacity ease-linear duration-300"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-black/30" aria-hidden="true" />
            </TransitionChild>

            <!-- Notification Tray -->
            <TransitionChild 
                as="template"
                enter="transition ease-in-out duration-500 transform"
                enter-from="translate-x-full"
                enter-to="translate-x-0"
                leave="transition ease-in-out duration-500 transform"
                leave-from="translate-x-0"
                leave-to="translate-x-full"
            >
                <div class="bg-white fixed inset-y-0 right-0 w-full max-w-md py-2">
                    <DialogPanel class="flex flex-col h-full">
                        <button @click="setIsOpen(false)" class="absolute top-2 right-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="button h-4 w-4 cursor-pointer text-gray-400"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                        <div class="border-b">
                            <div class="px-6 py-2">
                                <DialogTitle class="text-xl font-bold tracking-tight">Notifications</DialogTitle>
                                <div class="mt-2 text-sm">
                                    <button @click="markAllAsRead" class="inline-flex items-center gap-0.5 font-medium outline-none hover:underline focus:underline text-gray-600 hover:text-gray-500">Mark all as read</button>
                                    <span class="hidden"> • </span>
                                    <button @click="clearAll" class="hidden items-center gap-0.5 font-medium outline-none hover:underline focus:underline text-gray-600 hover:text-gray-500">Clear</button>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2 p-2 flex-1 overflow-y-auto">
                            <div class="px-4 py-2 space-y-4">
                                <div class="mt-[calc(-1rem-1px)]">

                                    <!-- Notification card -->
                                    <div v-for="notification in notifications.data" :key="notification.id" class="-mx-6 border-b border-t">
                                        <div class="py-2 pl-4 pr-2 bg-primary-50 -mb-px" :class="{'bg-blue-50' : notification.read_at === null}">
                                            <div class="pointer-events-auto flex gap-3 w-full transition duration-300">
                                                <!-- Receive tray icon -->
                                                <svg v-if="notification.type.includes('SubmitClaimVoucherNotification')" class="w-6 h-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M5.478 5.559A1.5 1.5 0 016.912 4.5H9A.75.75 0 009 3H6.912a3 3 0 00-2.868 2.118l-2.411 7.838a3 3 0 00-.133.882V18a3 3 0 003 3h15a3 3 0 003-3v-4.162c0-.299-.045-.596-.133-.882l-2.412-7.838A3 3 0 0017.088 3H15a.75.75 0 000 1.5h2.088a1.5 1.5 0 011.434 1.059l2.213 7.191H17.89a3 3 0 00-2.684 1.658l-.256.513a1.5 1.5 0 01-1.342.829h-3.218a1.5 1.5 0 01-1.342-.83l-.256-.512a3 3 0 00-2.684-1.658H3.265l2.213-7.191z" clip-rule="evenodd" /><path fill-rule="evenodd" d="M12 2.25a.75.75 0 01.75.75v6.44l1.72-1.72a.75.75 0 111.06 1.06l-3 3a.75.75 0 01-1.06 0l-3-3a.75.75 0 011.06-1.06l1.72 1.72V3a.75.75 0 01.75-.75z" clip-rule="evenodd" /></svg>
                                                <!-- Mail Icon -->
                                                <svg v-else class="w-6 h-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M1.5 8.67v8.58a3 3 0 003 3h15a3 3 0 003-3V8.67l-8.928 5.493a3 3 0 01-3.144 0L1.5 8.67z" /><path d="M22.5 6.908V6.75a3 3 0 00-3-3h-15a3 3 0 00-3 3v.158l9.714 5.978a1.5 1.5 0 001.572 0L22.5 6.908z" /></svg>


                                                <div class="grid flex-1">
                                                    <div class="flex h-6 items-center text-sm font-medium text-gray-900">
                                                        <p>{{ notification.data.title }}</p>
                                                    </div>
                                                    <p class="text-xs text-gray-500">
                                                        {{ notification.created_at }}
                                                    </p>
                
                                                    <div class="mt-1 text-sm text-gray-500">
                                                        <p><strong>{{ notification.data.body }}</strong></p>
                                                    </div>
                
                                                    <div class="mt-2 flex gap-3">
                                                        <a :href="notification.data.action_link" class="inline-flex items-center justify-center gap-0.5 font-medium outline-none hover:underline focus:underline text-sm text-blue-600 hover:text-blue-500">
                                                            View
                                                        </a>
                                                    </div>
                                                </div>
                                                <!-- <svg class="h-4 w-4 cursor-pointer text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg> -->
                                            </div>
                                        </div>
                                    </div> 
                                    
                                    <!-- Notification card skeleton -->
                                    <div v-if="loadingNotification">
                                        <div v-for="n in 2" class="-mx-6 border-b border-t">
                                            <div class="py-2 pl-4 pr-2 bg-primary-50 -mb-px bg-white">
                                                <div class="pointer-events-auto flex gap-3 w-full transition duration-300">
                                                    <div class="h-5 w-5 animated-background rounded-sm"></div>
                                                    <div class="grid flex-1">
                                                        <div class="h-6 animated-background rounded-sm">
                                                        </div>
                                                        <p class="mt-1 h-4 animated-background w-24 rounded-sm">
                                                            
                                                        </p>
                    
                                                        <div class="mt-1 animated-background h-5 rounded-sm">
                                                        
                                                        </div>
                    
                                                        <div class="mt-2 flex gap-3">
                                                            <a class="animated-background h-5 w-10 inline-flex items-center justify-center gap-0.5 rounded-sm">
                                                                
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-else class="flex justify-center py-4">
                                        <button v-if="notifications.next_page_url !== null" @click="loadMore" class="text-sm text-blue-500 hover:text-blue-700 hover:underline">Show More Notifications</button>
                                        <span v-else class="text-gray-400">No more notifications</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </DialogPanel>
                </div>
            </TransitionChild>
        </Dialog>
    </TransitionRoot>
</template>

<script>
import {
    Dialog,
    DialogPanel,
    DialogTitle,
    DialogDescription,
    TransitionRoot,
    TransitionChild
} from '@headlessui/vue'
import axios from 'axios';

export default {
    components: {
        Dialog,
        DialogPanel,
        DialogTitle,
        DialogDescription,
        TransitionRoot,
        TransitionChild,
    },
    data() {
        return {
            isOpen: false,
            loadingNotification: false,
            notifications: {},
        }
    },
    methods: {
        setIsOpen(value) {
            this.isOpen = value;

            if(value) {
                this.fetchNotification();
            }
        }, 
        fetchNotification() {
            this.loadingNotification = true;

            axios.get('/notifications')
                .then(response => {
                    this.notifications = {
                        ...response.data,
                        data: [...response.data.data],
                    }
                })
                .catch(error => {
                    console.log(error);
                }).finally(() => {
                    this.loadingNotification = false;
                })
        },
        loadMore() {
            this.loadingNotification = true;

            axios.get(this.notifications.next_page_url)
                .then(response => {
                    this.loadingNotification = false;

                    this.notifications = {
                        ...response.data,
                        data: [...this.notifications.data ,...response.data.data],
                    }
                })
                .catch(error => {
                    console.log(error);
                })
        },
        markAllAsRead() {
            axios.get(`/notifications/mark-all-as-read`)
                .catch (error => {
                    console.log(error);
                })
                .finally(() => {
                    this.fetchNotification();
                });
        },
        clearAll() {},
    },
}
</script>