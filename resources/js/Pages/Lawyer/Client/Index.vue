<template>
    <Head title="Client" />
    <Sidebar/>

    <div class="flex flex-col flex-1">
        <Header title="Client" />
        <main class="h-full pb-16 overflow-y-auto mx-3 my-4">
            
            <!-- <div class="container px-6 mx-auto grid">
                <h2
                class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200"
                >
                Client List
                </h2>
          </div> -->
          <div class="flex justify-between items-end mb-3">
            <div class="relative">
                <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input 
                    v-model="searchClients"
                    type="text" 
                    class="block p-4 pl-10 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" 
                    placeholder="Search Clients...">
            </div>
            
            <Link 
                href="/clients/create" 
                class="h-min text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2"
                >
                New client
            </Link>
        </div>

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
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
                        v-for="user in clients.data"
                        :key="user.id"
                        class="bg-white border-b"
                    >
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            {{ user.name }}
                        </th>
                        <td class="px-6 py-4 text-right">
                            <Link :href="`/lawyers/${ user.id }/edit`" class="font-medium text-blue-600 hover:underline">Edit</Link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        </main>
    </div>
</template>

<script>
import { Head } from "@inertiajs/inertia-vue3";
import Layout from "../Shared/Layout";
import Header from "../Shared/Header";
import Sidebar from "../Shared/Sidebar";
import Pagination from "../Shared/Pagination";


export default { 
    props: { 
        clients: Object,
        filters: Object
     },
    components: { Head, Header, Sidebar, Pagination },
    layout: Layout,
};
</script>