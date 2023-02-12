<template>
    <Head title="View Case File" />
    <Sidebar/>

    <div class="flex flex-col flex-1">
        <Header title="" />
        <main class="h-full pb-16 overflow-y-auto">
            
            <div class="container px-6 mx-auto grid">
                <h2
                class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200"
                >
                Case File: {{ case_file.file_no }}
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

                <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
                    <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
                    Case File Information
                    </h4>
                    <div>
                        Matter: {{ case_file.matter }}
                    </div>
                    <div>
                        Type: {{ case_file.type }}
                    </div>
                    <div>
                        File No.: {{ case_file.file_no }}
                    </div>
                    <div>
                        No Conflict: {{ case_file.no_conflict_check }}
                    </div>
                    <div>
                        Client: {{ case_file.client_id }}
                    </div>
                    <div>
                        Creator: {{ case_file.created_by }}
                    </div>
                </div>

                <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
                    <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
                    Quotation
                    </h4>
                    <div>
                        Matter: {{ case_file.matter }}
                    </div>
                    <div>
                        Type: {{ case_file.type }}
                    </div>
                    <div>
                        File No.: {{ case_file.file_no }}
                    </div>
                    <div>
                        No Conflict: {{ case_file.no_conflict_check }}
                    </div>
                    <div>
                        Client: {{ case_file.client_id }}
                    </div>
                    <div>
                        Creator: {{ case_file.created_by }}
                    </div>
                </div>

                <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
                    <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
                    Disbursement Items
                    </h4>
                    <div>
                        Matter: {{ case_file.matter }}
                    </div>
                    <div>
                        Type: {{ case_file.type }}
                    </div>
                    <div>
                        File No.: {{ case_file.file_no }}
                    </div>
                    <div>
                        No Conflict: {{ case_file.no_conflict_check }}
                    </div>
                    <div>
                        Client: {{ case_file.client_id }}
                    </div>
                    <div>
                        Creator: {{ case_file.created_by }}
                    </div>
                </div>

                <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
                    <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
                    Invoices
                    </h4>
                    <div>
                        Matter: {{ case_file.matter }}
                    </div>
                    <div>
                        Type: {{ case_file.type }}
                    </div>
                    <div>
                        File No.: {{ case_file.file_no }}
                    </div>
                    <div>
                        No Conflict: {{ case_file.no_conflict_check }}
                    </div>
                    <div>
                        Client: {{ case_file.client_id }}
                    </div>
                    <div>
                        Creator: {{ case_file.created_by }}
                    </div>
                </div>

                <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
                    <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
                    Receipts
                    </h4>
                    <div>
                        Matter: {{ case_file.matter }}
                    </div>
                    <div>
                        Type: {{ case_file.type }}
                    </div>
                    <div>
                        File No.: {{ case_file.file_no }}
                    </div>
                    <div>
                        No Conflict: {{ case_file.no_conflict_check }}
                    </div>
                    <div>
                        Client: {{ case_file.client_id }}
                    </div>
                    <div>
                        Creator: {{ case_file.created_by }}
                    </div>
                </div>

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
    components: { Head, Header, Sidebar, Pagination},
    props: {
        'case_file' : Object,
    },
    layout: Layout,
};
</script>