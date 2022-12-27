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
          <ul class="mt-6">
            <li class="relative px-6 py-3">
              <Link
                class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                href="/"
              >
                <span class="ml-4">Dashboard - Home</span>
            </Link>
            </li>
          </ul>
          <ul>
            <!-- <NavLink v-for="link in navLinks" :key="link.name" :href="link.href" :active="$page.component === link.component">{{ link.name }}</NavLink> -->
            <li class="relative px-6 py-3">
              <Link
                class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                href="/company"
              >
                <span class="ml-4">Manage Company Profile</span>
            </Link>
            </li>
            <li class="relative px-6 py-3">
              <Link
                class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                href="/users"
              >
                <span class="ml-4">Manage User Accounts</span>
            </Link>
            </li>
            <li class="relative px-6 py-3">
              <Link
                class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                href="/bankaccounts"
              >
                <span class="ml-4">Manage Bank Accounts</span>
            </Link>
            </li>
            <li class="relative px-6 py-3">
              <Link
                class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                href="/voucherapprovals"
              >
                <span class="ml-4">Approve Vouchers</span>
            </Link>
            </li>
          </ul>
        </div>
      </aside>
    </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';

export default {
    components: { Link },
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