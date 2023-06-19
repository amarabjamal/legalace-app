<template>
    <Menu as="div" class="relative inline-block text-left">
        <!-- Profile Button -->
        <menu-button class="align-middle flex items-center focus:shadow-outline-blue focus:outline-none" >
            <avatar/>
            <div class="auth-nav">
                <p class="auth-name">
                    {{ auth.user.name }}
                </p>
                <p class="auth-role">
                    <span v-if="auth.user.roles.length > 1" v-for="(role, index) in auth.user.roles" :key="index" class="capitalize">{{ index != auth.user.roles.length-1 ? `${role}, ` : `& ${role}` }}</span>
                    <span v-else class="capitalize">{{ auth.user.roles[0] }}</span>
                </p>
            </div>
        </menu-button>
    
        <!-- Profile Menu -->
        <transition
            enter-active-class="transition duration-100 ease-out"
            enter-from-class="transform scale-95 opacity-0"
            enter-to-class="transform scale-100 opacity-100"
            leave-active-class="transition duration-75 ease-in"
            leave-from-class="transform scale-100 opacity-100"
            leave-to-class="transform scale-95 opacity-0"
        >
        <menu-items class="absolute right-0 mt-4 w-56 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
            <div class="px-1 py-1">
                <menu-item v-slot="{ active }">
                    <Link
                        as="button"
                        :href="profileLink"
                        :class="[
                        active ? 'bg-violet-500 text-white' : 'text-gray-900',
                        'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                        ]"
                    >
                        <svg class="mr-2 h-5 w-5 fill-violet-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M4 22C4 17.5817 7.58172 14 12 14C16.4183 14 20 17.5817 20 22H18C18 18.6863 15.3137 16 12 16C8.68629 16 6 18.6863 6 22H4ZM12 13C8.685 13 6 10.315 6 7C6 3.685 8.685 1 12 1C15.315 1 18 3.685 18 7C18 10.315 15.315 13 12 13ZM12 11C14.21 11 16 9.21 16 7C16 4.79 14.21 3 12 3C9.79 3 8 4.79 8 7C8 9.21 9.79 11 12 11Z"></path></svg>
                        Profile
                    </Link>
                </menu-item>
            </div>

            <div class="px-1 py-1">
                <menu-item v-slot="{ active }">
                    <form @submit.prevent="logOut">
                        <button
                            type="submit"
                            :class="[
                            active ? 'bg-violet-500 text-white' : 'text-gray-900',
                            'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                            ]"
                        >
                            <svg class="mr-2 h-5 w-5 fill-violet-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M4 18H6V20H18V4H6V6H4V3C4 2.44772 4.44772 2 5 2H19C19.5523 2 20 2.44772 20 3V21C20 21.5523 19.5523 22 19 22H5C4.44772 22 4 21.5523 4 21V18ZM6 11H13V13H6V16L1 12L6 8V11Z"></path></svg>
                            Logout
                        </button>
                    </form>
                </menu-item>
            </div>
            </menu-items>
        </transition>
    </Menu>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import Avatar from './Avatar';
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue';

export default {
    components: {
        Avatar,
        Menu,
        MenuButton,
        MenuItems,
        MenuItem,
        Link
    },
    data() {
        return {
            auth: this.$page.props.auth,
            atAdminLayout: window.location.href.includes('/admin'),
        }
    },
    methods: {
        logOut() {
            this.$inertia.post('/logout');
        },
    },
    computed: {
        profileLink() {
            if(this.atAdminLayout) {
                return '/admin/profile';
            }

            return '/lawyer/profile';
        },
    },
}
</script>