<template>
    <div class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
        <span class="flex items-center col-span-3">
            Showing {{ from }}-{{ to }} of {{ total }}
        </span>
        <span class="col-span-2"></span>
        <!-- Pagination -->
        <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
        <nav aria-label="Table navigation">
            <ul class="inline-flex items-center">
                <li v-for="link in links">
                    <Component
                        :is="link.url ? 'Link' : 'span'"
                        :href="link.url" 
                        v-html="link.label"    
                        class="px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-blue"
                        :class="{ 'text-white transition-colors duration-150 bg-blue-500 border border-r-0 border-blue-50' : link.active }"
                    />
                </li>
            </ul>
        </nav>
        </span>
    </div>
</template>

<script>
export default {
    props: {
        links: Array,
        from: Number,
        to: Number,
        total: Number,
    }
}
</script>