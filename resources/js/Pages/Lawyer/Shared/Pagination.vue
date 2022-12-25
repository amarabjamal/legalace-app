<template>
    <nav class="flex justify-between mt-10" aria-label="Page navigation">
        <!-- Help text -->
        <span class="text-sm text-gray-700">
            Showing <span class="font-semibold text-gray-900">{{ from }}</span> to <span class="font-semibold text-gray-900">{{ to }}</span> of <span class="font-semibold text-gray-900">{{ total }}</span> Entries
        </span>
        <!-- Buttons -->
        <ul class="inline-flex -space-x-px">
            <!-- <li>
                <a href="#" class="py-2 px-3 ml-0 leading-tight text-gray-500 bg-white rounded-l-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Previous</a>
            </li> -->
            <li v-for="link in links">
                <Component
                    :is="link.url ? 'Link' : 'span'"
                    :href="link.url" 
                    v-html="link.label"    
                    class="py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700"
                    :class="{ 'text-blue-600 bg-blue-50 border border-blue-300 hover:bg-blue-100 hover:text-blue-700' : link.active }"
                />
            </li>
            <!-- <li>
                <a href="#" class="py-2 px-3 leading-tight text-gray-500 bg-white rounded-r-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Next</a>
            </li> -->
        </ul>
    </nav>
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