<template>
    <li class="relative px-6 py-3">
        <span 
            v-if="active"
            class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" 
            aria-hidden="true"
        ></span>
        <Link
            href="{{ href }}"
            class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
            :class="{ 'text-gray-800 dark:text-gray-100' : active}"
            >
            <svg
                class="w-5 h-5"
                aria-hidden="true"
                fill="none"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"
                ></path>
            </svg>
            <span class="ml-4"><slot /></span>
        </Link>
    </li>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3'

export default {
    props: {
        active: Boolean,
        href: String,
    },
    components: {
        Link,
    }
}
</script>