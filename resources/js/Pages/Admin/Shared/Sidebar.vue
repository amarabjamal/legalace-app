<template>
    <div
      class="flex h-screen bg-gray-50 dark:bg-gray-900"
      :class="{ 'overflow-hidden': isSideMenuOpen}"
    >
            <!-- Desktop sidebar -->
    <aside
        class="z-20 hidden w-64 overflow-y-auto bg-white dark:bg-gray-800 md:block flex-shrink-0"
      >
        <div class="py-4 text-gray-500 dark:text-gray-400">
          <div class="flex justify-left px-7 pt-3">
            <Link
              class="ml-6 text-lg font-bold text-gray-800 dark:text-gray-200"
              href="/"
            >
              <img :src="'/images/app/logo.png'" width="120" alt="legalace logo">
            </Link>
          </div>
          <div  class="flex justify-left pl-6 pt-6 text-sm">
            <span>
              Hello, <strong>{{ $page.props.auth.user.name }}</strong>!<br/>
              <div  class="flex justify-left pl-6 pt-2">
                <span v-for="role in $page.props.auth.user.roles" class="px-2 py-1 m-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">{{ role }}</span>
              </div>
            </span>
          </div>
          <ul class="mt-6">
            <li class="relative px-6 py-3">
              <Link
                class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                href="/"
              >
                <HomeIcon class="inline-block h-5 w-5"></HomeIcon>
                <span class="ml-4">Dashboard</span>
            </Link>
            </li>
            <!-- <NavLink v-for="link in navLinks" :key="link.name" :href="link.href" :active="$page.component === link.component">{{ link.name }}</NavLink> -->
            <li class="relative px-6 py-3">
              <Link
                class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                href="/company"
              >
                <OfficeBuildingIcon class="inline-block h-5 w-5"></OfficeBuildingIcon>
                <span class="ml-4">Company Profile</span>
            </Link>
            </li>
            <li class="relative px-6 py-3">
              <Link
                class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                href="/users"
              >
                <UsersIcon class="inline-block h-5 w-5"></UsersIcon>
                <span class="ml-4">User Accounts</span>
            </Link>
            </li>
            <li class="relative px-6 py-3">
              <Link
                class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                href="/bankaccounts"
              >
                <CreditCardIcon class="inline-block h-5 w-5"></CreditCardIcon>
                <span class="ml-4">Bank Accounts</span>
            </Link>
            </li>
            <li class="relative px-6 py-3">
              <Link
                class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                href="/voucherapprovals"
              >
                <InboxInIcon class="inline-block h-5 w-5"></InboxInIcon> 
                <span class="ml-4">Voucher Requests</span>
            </Link>
            </li>
          </ul>
        </div>
      </aside>
    </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import { HomeIcon, UsersIcon, CreditCardIcon, InboxInIcon, OfficeBuildingIcon } from "@heroicons/vue/outline";

export default {
    components: { Link, HomeIcon, UsersIcon, CreditCardIcon, InboxInIcon, OfficeBuildingIcon },
    data() {
      return {
        navLinks : [
            { name: 'Dashboard', href: '/dashboard', component: 'Admin/Dashboard'},
            { name: 'Lawyers', href: '/lawyers', component: 'Admin/Lawyers/Index'},
            { name: 'Clerks', href: '/clerks', component: 'Admin/Clerks/Index'},
        ],
      }
    }
}
</script>