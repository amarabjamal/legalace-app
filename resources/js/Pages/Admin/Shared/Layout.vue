<template>
    <Sidebar :isSidebarOpen="isSidebarOpen"/>

    <section class="dashboard">
        <!-- Header -->
        <div class="top">
            <div class="flex flex-column">
                <icon v-if="!isSidebarOpen" @click="toggleSidebar" name="menu-line" class="mt-2 h-6 w-6 fill-white cursor-pointer"></icon>
                <icon v-else @click="toggleSidebar" name="menu-fold-fill" class="mt-2 h-6 w-6 fill-white cursor-pointer"></icon>
                <div class="ml-4">
                    <Link href="/">
                        <img :src="'/images/app/logo.png'" width="90" alt="legalace logo">
                    </Link>
                </div>
            </div>

            <ul class="flex items-center flex-shrink-0 space-x-6">
                <!-- Notifications menu -->
                <li class="relative">
                <button
                    class="relative align-middle rounded-md text-white focus:outline-none focus:shadow-outline-purple"
                    @click="toggleNotificationsMenu"
                    aria-label="Notifications"
                    aria-haspopup="true"
                >
                    <svg
                    class="w-5 h-5"
                    aria-hidden="true"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                    >
                    <path
                        d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"
                    ></path>
                    </svg>
                    <!-- Notification badge -->
                    <span
                    aria-hidden="true"
                    class="absolute top-0 right-0 inline-block w-3 h-3 transform translate-x-1 -translate-y-1 bg-red-600 border-2 border-white rounded-full dark:border-gray-800"
                    ></span>
                </button>
                <transition
                    enter-active-class="transition transform duration-100 ease-out"
                    enter-from-class="opacitv-0 scale-75"
                    enter-to-class="opacity-100 scale-100"
                    leave-active-class="transition transform duration-100 ease-in" 
                    leave-from-class="opacity-100 scale-100"
                    leave-to-class="opacity-0 scale-75"
                >
                    <template v-if="isNotificationsMenuOpen">
                        <ul
                        class="absolute right-0 w-56 p-2 mt-2 space-y-2 text-gray-600 bg-white border border-gray-100 rounded-md shadow-md dark:text-gray-300 dark:border-gray-700 dark:bg-gray-700"
                        aria-label="submenu"
                        >
                        <li class="flex">
                            <a
                            class="inline-flex items-center justify-between w-full px-2 py-1 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200"
                            href="#"
                            >
                            <span>Messages</span>
                            <span
                                class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-600 bg-red-100 rounded-full dark:text-red-100 dark:bg-red-600"
                            >
                                13
                            </span>
                            </a>
                        </li>
                        <li class="flex">
                            <a
                            class="inline-flex items-center justify-between w-full px-2 py-1 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200"
                            href="#"
                            >
                            <span>Sales</span>
                            <span
                                class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-600 bg-red-100 rounded-full dark:text-red-100 dark:bg-red-600"
                            >
                                2
                            </span>
                            </a>
                        </li>
                        <li class="flex">
                            <a
                            class="inline-flex items-center justify-between w-full px-2 py-1 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200"
                            href="#"
                            >
                            <span>Alerts</span>
                            </a>
                        </li>
                        </ul>
                    </template>
                </transition>
                </li>
                <!-- Profile menu -->
                <li class="relative">
                <button
                    class="align-middle flex items-center focus:shadow-outline-blue focus:outline-none"
                    @click="toggleProfileMenu"
                    aria-label="Account"
                    aria-haspopup="true"
                >
                    <img
                    class="object-cover w-8 h-8 rounded-full shadow"
                    :src="'/images/profileImage/default.png'"
                    alt="user profile image"
                    aria-hidden="true"
                    />
                    <div class="auth-nav flex flex-col items-start pl-3 text-white">
                        <p class="auth-name">
                            {{ auth.user.name }}
                        </p>
                        <p class="auth-role">
                            <span v-for="(role, index) in auth.user.roles" :key="index" class="mr-1 capitalize">{{ index != auth.user.roles.length-1 ? `${role}, ` : `& ${role}` }}</span>
                        </p>
                    </div>
                </button>
                <transition
                    enter-active-class="transition transform duration-100 ease-out"
                    enter-from-class="opacitv-0 scale-75"
                    enter-to-class="opacity-100 scale-100"
                    leave-active-class="transition transform duration-100 ease-in" 
                    leave-from-class="opacity-100 scale-100"
                    leave-to-class="opacity-0 scale-75"
                >
                    <template v-if="isProfileMenuOpen"
                    >
                        <ul
                        class="absolute right-0 w-56 p-2 mt-2 space-y-2 text-gray-600 bg-white border border-gray-100 rounded-md shadow-md dark:border-gray-700 dark:text-gray-300 dark:bg-gray-700"
                        aria-label="submenu"
                        >
                        <li class="flex">
                            <Link as="a" href="/admin/profile" class="inline-flex items-center w-full px-2 py-1 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200">
                            <svg
                                class="w-4 h-4 mr-3"
                                aria-hidden="true"
                                fill="none"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                ></path>
                            </svg>
                            <span>Profile</span>
                            </Link>
                        </li>
                        <li class="flex">
                            <Link as="a" href="/admin/setting" class="inline-flex items-center w-full px-2 py-1 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200">
                            <svg
                                class="w-4 h-4 mr-3"
                                aria-hidden="true"
                                fill="none"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
                                ></path>
                                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>Settings</span>
                            </Link>
                        </li>
                        <li class="flex">
                            <Link as="button" href="/logout" method="post" class="inline-flex items-center w-full px-2 py-1 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200">
                            <svg
                                class="w-4 h-4 mr-3"
                                aria-hidden="true"
                                fill="none"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"
                                ></path>
                            </svg>
                            <span>Log out</span>
                            </Link>
                        </li>
                        </ul>
                    </template>
                </transition>
                </li>
            </ul>
        </div>

        <!-- Page Content -->
        <div class="dash-content">
            <div class="flex flex-col flex-1">
                <main class="h-full pb-16 overflow-y-auto">          
                    <div class="container px-6 mx-auto grid pt-6">
                        <flash-messages/>
                        <slot />
                    </div>
                </main>
            </div>
        </div>
    </section>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import Sidebar from './Sidebar';
import FlashMessages from '../../../Shared/FlashMessages';

export default {
    props: {
        auth: Object,
    },
    data() {
        return {
            isSidebarOpen: false,
            isNotificationsMenuOpen: false,
            isProfileMenuOpen: false,
        }
    },
    components: { 
        Sidebar, 
        FlashMessages,
     },
    methods: {
        toggleSidebar() {
            this.isSidebarOpen = !this.isSidebarOpen;
        },
        toggleNotificationsMenu() {
            this.isNotificationsMenuOpen = !this.isNotificationsMenuOpen;
        },
        toggleProfileMenu() {
            this.isProfileMenuOpen = !this.isProfileMenuOpen;
        },
    }
};
</script>

<style scoped>
.dashboard{
    position: relative;
    left: 250px;
    background-color: rgb(246, 250, 255);
    min-height: 100vh;
    width: calc(100% - 250px);
    padding: 10px 14px;
    transition: var(--tran-05);
}
	
nav.close ~ .dashboard{
    left: 73px;
    width: calc(100% - 73px);
}
.dashboard .top{
    position: fixed;
    top: 0;
    left: 250px;
    display: flex;
    width: calc(100% - 250px);
    justify-content: space-between;
    align-items: center;
    padding: 10px 14px;
    background-color: rgb(60, 61, 91, 1.0);
    backdrop-filter: blur( 8px );
    -webkit-backdrop-filter: blur( 8px );
    transition: var(--tran-05);
    z-index: 10;
    border-bottom: 1px solid rgb(15, 23, 42, 0.1);
}
nav.close ~ .dashboard .top{
    left: 73px;
    width: calc(100% - 73px);
}

.dashboard .top .auth-nav{
    white-space: nowrap;
    overflow: hidden;
}

.dashboard .top .auth-name{
    font-size: 13px;
    font-weight: bold;
    text-overflow: ellipsis;
    width: 120px;
    white-space: nowrap;
    overflow: hidden;
    text-align: start;
}

.dashboard .top .auth-role{
    font-size: 10px;
}

.dashboard .dash-content{
    padding-top: 50px;
}
.dash-content .title{
    display: flex;
    align-items: center;
    margin: 60px 0 30px 0;
}

@media (max-width: 1000px) {
    nav ~ .dashboard{
        left: 73px;
        width: calc(100% - 73px);
    }
    nav.close ~ .dashboard{
        left: 250px;
        width: calc(100% - 250px);
    }
    nav ~ .dashboard .top{
        left: 73px;
        width: calc(100% - 73px);
    }
    nav.close ~ .dashboard .top{
        left: 250px;
        width: calc(100% - 250px);
    }
}

@media (max-width: 400px) {
    nav ~ .dashboard{
        left: 0;
        width: 100%;
    }
    nav.close ~ .dashboard{
        left: 73px;
        width: calc(100% - 73px);
    }
    nav ~ .dashboard .top{
        left: 0;
        width: 100%;
    }
    nav.close ~ .dashboard .top{
        left: 0;
        width: 100%;
    }
}
</style>