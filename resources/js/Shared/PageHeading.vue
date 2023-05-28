<template>
    <div class="mt-5 mb-8">
        <div>
            <nav class="sm:hidden" aria-label="Back">
                <Link :href="back.link" class="flex items-center text-sm font-medium text-gray-500/100">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="-ml-1 mr-1 h-5 w-5 shrink-0 text-gray-400/100"><path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd"></path></svg>
                    Back
                </Link>
            </nav>
            <nav class="hidden sm:flex" aria-label="Breadcrumb">
                <ol class="flex items-center abe" role="list">
                    <li v-for="(breadcrumb, index) in breadcrumbs">
                        <div v-if="index === 0" class="flex">
                            <Link :href="breadcrumb.link" class="text-sm font-medium text-gray-500/100">
                                {{ breadcrumb.label }}
                            </Link>
                        </div>

                        <div v-else class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="h-5 w-5 shrink-0 text-gray-400/100"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd"></path></svg>
                            <Link :href="breadcrumb.link" class="ml-4 text-sm font-medium text-gray-500/100">
                                {{ breadcrumb.label }}
                            </Link>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
        
        <div class="mt-2 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                    {{ page_title }}
                </h2>
            </div>
        </div>
    </div>
</template>

<script>
import { nth } from 'lodash';

export default {
    props: {
        page_title: String,
        breadcrumbs: Array,
    },
    data() {
        return {
            back: this.breadcrumbs.length >= 2 ? nth(this.breadcrumbs, -2) : { link: null},
        }
    }
}

</script>

<style scoped>

.abe > :not([hidden]) ~ :not([hidden]) {
  --tw-space-x-reverse: 0;
  margin-right: calc(1rem * var(--tw-space-x-reverse));
  margin-left: calc(1rem * calc(1 - var(--tw-space-x-reverse)));
}

</style>