<template>
    <Head title="Lawyers" />
    <Header title="Lawyers" />

    <main>
      <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Replace with your content -->
        <div class="relative mb-8">
            <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input 
                v-model="searchLawyers"
                type="text" 
                class="block p-4 pl-10 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                placeholder="Search Lawyers...">
        </div>
        
        <div class="mb-6">
            <Link 
                href="/lawyers/create" 
                class=" text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                >
                Create new lawyer
            </Link>
        </div>

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            <span class="sr-only">Edit</span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr 
                        v-for="user in users.data"
                        :key="user.id"
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
                    >
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                            {{ user.name }}
                        </th>
                        <td class="px-6 py-4 text-right">
                            <Link :href="`/lawyers/${ user.id }/edit`" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</Link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Paginator -->
        <Pagination :links="users.links" :total="users.total" :from="users.from" :to="users.to"/>
        <!-- /End replace -->
      </div>
    </main>
</template>

<script>
import { Head } from "@inertiajs/inertia-vue3";
import Layout from "../Shared/Layout";
import Header from "../Shared/Header.vue";
import Pagination from "../Shared/Pagination";
import { ref, watch } from "vue";
import { Inertia } from "@inertiajs/inertia";
import throttle from 'lodash/throttle';

export default { 
    setup(props) {
        let searchLawyers = ref(props.filters.search);

        watch(searchLawyers, throttle(value => {
            Inertia.get('/lawyers', { search: value }, {
                preserveState: true,
                replace: true,
            });
        }, 500));

        return { searchLawyers };
    },
    props: { 
        users: Object,
        filters: Object
     },
    components: { Head, Header, Pagination, ref },
    layout: Layout,
};
</script>