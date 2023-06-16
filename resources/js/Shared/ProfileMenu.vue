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
                        <svg class="mr-2 h-5 w-5 text-violet-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z" clip-rule="evenodd" />
                        </svg>
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
                            <svg class="mr-2 h-5 w-5 text-violet-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm-4.28 9.22a.75.75 0 000 1.06l3 3a.75.75 0 101.06-1.06l-1.72-1.72h5.69a.75.75 0 000-1.5h-5.69l1.72-1.72a.75.75 0 00-1.06-1.06l-3 3z" clip-rule="evenodd" />
                            </svg>
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