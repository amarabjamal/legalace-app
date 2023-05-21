<template>
    <nav v-if="links != null" class="flex justify-between mt-10" aria-label="Page navigation">
        <span v-if="total != 0" class="text-sm text-gray-700">
            Showing <span class="font-semibold text-gray-900 dark:text-gray-300">{{ from }}</span> to <span class="font-semibold text-gray-900 dark:text-gray-300">{{ to }}</span> of <span class="font-semibold text-gray-900 dark:text-gray-300">{{ total }}</span> Entries
        </span>
        <span v-else class="text-sm text-gray-700">
            Showing <span class="font-semibold text-gray-900 dark:text-gray-300">{{ total }}</span> Entries
        </span>

        <ul class="inline-flex -space-x-px">
            <li v-for="link in links">
                <Component
                    :is="link.url ? 'Link' : 'span'"
                    :href="link.url" 
                    v-html="link.label"    
                    class="py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700"
                    :class="{ 'text-blue-600 bg-blue-50 border border-blue-300 hover:bg-blue-100 hover:text-blue-700' : link.active }"
                />
            </li>
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