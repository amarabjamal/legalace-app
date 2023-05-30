<template>
    <Menu as="div" class="relative inline-block text-left">
    <div>
        <MenuButton class="px-1">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 fill-gray-700">
            <path fill-rule="evenodd" d="M10.5 6a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zm0 6a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zm0 6a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0z" clip-rule="evenodd" />
            </svg>
        </MenuButton>
    </div>

    <transition
        enter-active-class="transition duration-100 ease-out"
        enter-from-class="transform scale-95 opacity-0"
        enter-to-class="transform scale-100 opacity-100"
        leave-active-class="transition duration-75 ease-in"
        leave-from-class="transform scale-100 opacity-100"
        leave-to-class="transform scale-95 opacity-0"
    >
        <MenuItems
            class="absolute right-0 origin-top-right w-56 mt-4 divide-y divide-gray-300/70 rounded-md bg-gray-100 bg-clip-padding backdrop-filter backdrop-blur-xl bg-opacity-10 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
        >
            <div class="px-1 py-1">
                <MenuItem v-if="status_code === 2" v-slot="{ active }">
                    <button
                        @click="this.$emit('mark-sent')"
                        :class="[
                        active ? 'bg-blue-500 text-white' : 'text-gray-900',
                        'group flex w-full items-center rounded-md px-3 py-2 text-sm',
                        ]"
                    >
                        Mark Sent
                    </button>
                </MenuItem>
                <MenuItem v-if="status_code === 3" v-slot="{ active }">
                    <button
                        @click="this.$emit('send-email')"
                        :class="[
                        active ? 'bg-blue-500 text-white' : 'text-gray-900',
                        'group flex w-full items-center rounded-md px-3 py-2 text-sm',
                        ]"
                    >
                        Re-send email
                    </button>
                </MenuItem>
                <MenuItem v-slot="{ active }">
                    <a
                        :href="`/lawyer/case-files/${this.case_file_id}/invoices/${invoice_id}/pdf`" 
                        target="_blank"
                        as="button"
                        :class="[
                        active ? 'bg-blue-500 text-white' : 'text-gray-900',
                        'group flex w-full items-center rounded-md px-3 py-2 text-sm',
                        ]"
                    >
                        Download PDF
                    </a>
                </MenuItem>
            </div>
        </MenuItems>
    </transition>
    </Menu>
</template>
  
<script>
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue'
import { EllipsisVertical } from '@heroicons/vue/solid/';

export default {
    components: {
        Menu, MenuButton, MenuItems, MenuItem, EllipsisVertical
    },
    props: {
        case_file_id: Number,
        invoice_id: Number,
        status_code: Number,
    }
}
</script>