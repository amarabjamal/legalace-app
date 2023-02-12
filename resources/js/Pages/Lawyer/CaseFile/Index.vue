<template>
    <Head title="Manage users" />

    <div class="flex flex-col flex-1">
        <main class="h-full pb-16 overflow-y-auto">
            
            <div class="container px-6 mx-auto grid">
                <h2
                class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200"
                >
                Manage Case Files
                </h2>
    
                <!-- Main Content Start -->
                <div v-if="$page.props.flash.message" class="flex p-4 mb-4 bg-green-100 rounded-lg" role="alert">
                    <svg class="flex-shrink-0 w-5 h-5 text-green-700" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                    <div class="ml-3 text-sm font-medium text-green-700">
                        {{ $page.props.flash.message }}
                    </div>
                    <button type="button" @click="$page.props.flash.message = ''" class="ml-auto -mx-1.5 -my-1.5 bg-green-100 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex h-8 w-8">
                        <span class="sr-only">Close</span>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>
                
                <div class="flex justify-between items-center mb-3">
                    <div class="relative">
                        <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input 
                            v-model="searchUsers"
                            type="text" 
                            class="block p-2 pl-10 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" 
                            placeholder="Search">
                    </div>
                    
                    <Link 
                        href="/casefiles/create" 
                        class="h-min text-white bg-blue-800 hover:bg-blue-900 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2"
                        >
                        Create New Case File
                    </Link>
                </div>

                <div class="relative overflow-x-auto shadow-md">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-200 uppercase bg-blue-900">
                            <tr>
                                <th scope="col" class="px-6 py-3 w-1">
                                    No.
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Matter
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Type
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    File No.
                                </th>
                                <th scope="col" class="px-6 py-3 w-5">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 w-5">
                                    <span>Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white  dark:bg-gray-800">
                            <tr 
                                v-if="case_files.length <= 0"
                                class="border-b dark:border-b-gray-700 text-gray-700 dark:text-gray-400"
                            >
                                <td 
                                    scope="row" 
                                    colspan="6" 
                                    class="px-6 py-4 font-medium text-center text-gray-500 bg-gray-100 whitespace-nowrap"
                                >
                                    No records found.
                                </td>
                            </tr>
                            <tr 
                                v-else
                                v-for="case_file in case_files"
                                class="border-b dark:border-b-gray-700 text-gray-700 dark:text-gray-400"
                            >
                                <th scope="row" class="px-6 py-4 font-medium whitespace-nowrap">
                                    {{ case_file.id }}
                                </th>
                                <th scope="row" class="px-6 py-4 font-medium whitespace-nowrap">
                                    {{ case_file.matter }}
                                </th>
                                <th scope="row" class="px-6 py-4 font-medium whitespace-nowrap">
                                    {{ case_file.type }}
                                </th>
                                <th scope="row" class="px-6 py-4 font-medium whitespace-nowrap">
                                    {{ case_file.file_number }}
                                </th>
                                <th scope="row" class="px-6 py-4 font-medium whitespace-nowrap">
                                    On-going
                                </th>
                                <td class="px-6 py-4">
                                    <Link :href="`casefiles/${case_file.id}`" class="font-medium hover:text-blue-600">View</Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Paginator -->
                <!-- <Pagination :links="users.links" :total="users.total" :from="users.from" :to="users.to"/> -->
                <!-- Main Content End -->
          </div>
        </main>
    </div>
</template>

<script>
import { Head } from "@inertiajs/inertia-vue3";
import Layout from "../Shared/Layout";
import Pagination from "../Shared/Pagination";

export default { 
    components: { Head, Pagination},
    props: {
        case_files: Object,
    },
    layout: Layout,
};
</script>