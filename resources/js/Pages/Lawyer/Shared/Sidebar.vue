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
            <span>Hello, <strong>{{ $page.props.auth.user.name }}</strong>!</span>
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
                href="/clients"
              >
                <UsersIcon class="inline-block h-5 w-5"></UsersIcon>
                <span class="ml-4">Clients</span>
              </Link>
            </li>
            <li class="relative px-6 py-3">
              <Link
                class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                href="/"
              >
                <UsersIcon class="inline-block h-5 w-5"></UsersIcon>
                <span class="ml-4">Case Files</span>
              </Link>
            </li>
            <li class="relative px-6 py-3">
              <Link
                class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                href=""
              >
                <UsersIcon class="inline-block h-5 w-5"></UsersIcon>
                <span class="ml-4">Vouchers</span>
              </Link>
            </li>
          </ul>
        </div>
      </aside>
    </div>
</template>

<script>
import { HomeIcon, UsersIcon, CreditCardIcon, InboxInIcon, OfficeBuildingIcon } from "@heroicons/vue/outline";

export default {
  components: { HomeIcon, UsersIcon, CreditCardIcon, InboxInIcon, OfficeBuildingIcon },
}
</script>