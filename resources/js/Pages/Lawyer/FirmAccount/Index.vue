<template>
    <Head title="FirmAccount" />
    <Sidebar/>

    <div class="flex flex-col flex-1">
        <Header title="Firm Account" />
        <main class="h-full pb-16 overflow-y-auto mx-3 my-4">
            
            <div class="container px-6 mx-auto grid">
                <h2
                class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200"
                >
                Manage Firm Account
                </h2>
          <!-- <div class="flex justify-between items-end mb-3">
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
        </div> -->

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

        <div class="flex items-center mb-4">
            <h4 class="text-lg font-semibold text-gray-600 dark:text-gray-300">
                Firm Account
            </h4>

            <!-- <Link href="/bankaccounts/create">
                <button class="px-4 py-2 ml-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-800 border border-transparent rounded-lg active:bg-blue-900 hover:bg-blue-900 focus:outline-none focus:shadow-outline-blue">
                    Register New Firm Account
                </button>
            </Link> -->
        </div>

        <div class="grid gap-6 mb-8 md:grid-cols-2">
            <div class="min-w-0 p-4 bg-white  shadow-xs dark:bg-cyan-700 rounded-lg">
                <!-- <div v-for="f in firmAccounts" class="min-w-0 p-4 bg-white  shadow-xs dark:bg-gray-800"> -->
                    <h4 class="mb-4 font-semibold text-gray-600 dark:text-gray-300">
                    <!-- {{ bankAccount.account_name }}Kembara -->
                    </h4>
                    <p class="text-gray-600 dark:text-slate-800 text-2xl">
                        <table class="border-separate border-spacing-2 dark:text-gray-300">
                            <!-- <tr>
                                <td width="150px">
                                    Date
                                </td>
                                <td class="font-bold">
                                    <span class="font-bold">{{ bankAccount.bank_name }}</span>
                                </td>
                            </tr> -->
                            <!-- <tr>
                                <td>
                                    Description
                                </td>
                                <td class="font-bold">
                                    {{ bankAccount.account_number }}
                                </td>
                            </tr> -->
                            <!-- <tr>
                                <td>
                                    Transaction type
                                </td>
                                <td class="font-bold">
                                    {{ bankAccount.bank_address }}
                                </td>
                            </tr> -->
                            <!-- <tr>
                                <td>
                                    Debit
                                </td>
                                <td class="font-bold">
                                    {{ bankAccount.swift_code }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Credit
                                </td>
                                <td class="font-bold">
                                    {{ bankAccount.account_type.name }}
                                </td>
                            </tr> -->
                            <tr>
                                <td>
                                    Balance (RM)
                                </td>
                                <td class="font-bold">
                                    {{ acc }}
                                </td>
                            </tr>
                            <!-- <tr>
                                <td>
                                    Created By
                                </td>
                                <td class="font-bold">
                                    {{ bankAccount.created_by.name }} {{ bankAccount.created_by.name == $page.props.auth.user.name ? '(You)' : null }}
                                </td>
                            </tr> -->
                        </table>
                        <div class="flex justify-end mt-3 p-2 pr-4">
                            <!-- <Link :href="`/bankaccounts/${ bankAccount.id }/edit`">Edit</Link>
                            <Link @click="deleteBankAccount(bankAccount)" as="button" class="ml-3 font-medium text-red-600 hover:underline">Delete</Link> -->
                        </div>
                    </p>
                <!-- </div> -->
            </div>
        </div>

                <div class="relative mb-3">
                    <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input 
                        v-model="searchFirms"
                        type="text" 
                        class="block p-4 pl-10 w-1/8 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" 
                        placeholder="Search Transactions...">
                </div>

                <div class="flex items-center mb-4">
                    <h4 class="text-lg font-semibold text-gray-600 dark:text-gray-300">
                        Transcation lists
                    </h4>

                    <Link href="/firm-account/create">
                        <button class="px-4 py-2 ml-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-700 border border-transparent rounded-lg active:bg-blue-900 hover:bg-blue-900 focus:outline-none focus:shadow-outline-blue">
                            Add new transaction
                        </button>
                    </Link>
                </div>
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-green-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Date
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Description
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Transaction type
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Debit(Funds in)
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Credit(Funds out)
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Balance(MYR)
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    <span class="sr-only">Edit</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr 
                                v-for="acc in firmAccounts.data"
                                :key="acc.id"
                                class="bg-white border-b"
                            >
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                    {{ acc.date }}
                                </th>
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                    {{ acc.description }}
                                </th>
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                    {{ acc.transaction_type }}
                                </th>
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                    {{ acc.debit }}
                                </th>
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                    {{ acc.credit }}
                                </th>
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                    {{ acc.balance }}
                                </th>
                                <td class="px-6 py-4 text-right">
                                    <Link :href="`/firm-account/${ acc.id }/edit`" class="font-medium text-blue-600 hover:underline">Edit</Link>
                                    <Link @click="deleteAcc(acc)" as="button" class="ml-3 font-medium text-red-600 hover:underline">Delete</Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Paginator -->
            <Pagination :links="firmAccounts.links" :total="firmAccounts.total" :from="firmAccounts.from" :to="firmAccounts.to"/>
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
import { Inertia } from "@inertiajs/inertia";
import throttle from 'lodash/throttle';
import { ref, watch } from "vue";


export default { 
    setup(props) {
        let searchFirms = ref(props.filters.search);

        watch(searchFirms, throttle(value => {
            Inertia.get('/firm-account', { search: value }, {
                preserveState: true,
                replace: true,
            });
        }, 500));

        return { searchFirms };
    },
    props: { 
        firmAccounts: Object,
        filters: Object,
        acc: Object
     },
    components: { Head, Header, Sidebar, Pagination, ref },
    layout: Layout,
    methods: {
        deleteAcc(acc) {
            if (confirm('Are you sure you want to delete this client?')) {
                Inertia.delete(`/firm-account/${ acc.id }`);
            }
        }
    },
};
</script>