<template>
    <Head title="Client Account" />
    <Sidebar/>

    <div class="flex flex-col flex-1">
        <Header title="Firm Account" />
        <main class="h-full pb-16 overflow-y-auto mx-3 my-4">
            
            <div class="container px-6 mx-auto grid">
                <h2
                class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200"
                >
                Manage Client Accounts
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
        <div class="flex items-center mb-4">
            <h4 class="text-lg font-semibold text-gray-600 dark:text-gray-300">
                Client Accounts
            </h4>

            <Link href="/bankaccounts/create">
                <button class="px-4 py-2 ml-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-800 border border-transparent rounded-lg active:bg-blue-900 hover:bg-blue-900 focus:outline-none focus:shadow-outline-blue">
                    Add New Client Account
                </button>
            </Link>
        </div>

        <div class="grid gap-6 mb-8 md:grid-cols-2">
                    <div class="min-w-0 p-4 bg-white  shadow-xs dark:bg-gray-800">
                    <!-- <div v-for="bankAccount in bankAccounts" class="min-w-0 p-4 bg-white  shadow-xs dark:bg-gray-800"> -->
                        <h4 class="mb-4 font-semibold text-gray-600 dark:text-gray-300">
                        <!-- {{ bankAccount.account_name }} -->Megat Holding
                        </h4>
                        <p class="text-gray-600 dark:text-gray-400">
                            <table class="border-separate border-spacing-2 dark:text-gray-400">
                                <tr>
                                    <td width="150px">
                                        Date
                                    </td>
                                    <td class="font-bold">
                                        <!-- <span class="font-bold">{{ bankAccount.bank_name }}</span> -->
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Description
                                    </td>
                                    <td class="font-bold">
                                        <!-- {{ bankAccount.account_number }} -->
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Transaction type
                                    </td>
                                    <td class="font-bold">
                                        <!-- {{ bankAccount.bank_address }} -->
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Debit
                                    </td>
                                    <td class="font-bold">
                                        <!-- {{ bankAccount.swift_code }} -->
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Credit
                                    </td>
                                    <td class="font-bold">
                                        <!-- {{ bankAccount.account_type.name }} -->
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Balance
                                    </td>
                                    <td class="font-bold">
                                        <!-- {{ bankAccount.label }} -->
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Created By
                                    </td>
                                    <td class="font-bold">
                                        <!-- {{ bankAccount.created_by.name }} {{ bankAccount.created_by.name == $page.props.auth.user.name ? '(You)' : null }} -->
                                    </td>
                                </tr>
                            </table>
                            <div class="flex justify-end mt-3 p-2 pr-4">
                                <!-- <Link :href="`/bankaccounts/${ bankAccount.id }/edit`">Edit</Link>
                                <Link @click="deleteBankAccount(bankAccount)" as="button" class="ml-3 font-medium text-red-600 hover:underline">Delete</Link> -->
                            </div>
                        </p>
                    </div>
                </div>

                <div class="flex items-center mb-4">
                    <h4 class="text-lg font-semibold text-gray-600 dark:text-gray-300">
                        Transcation lists
                    </h4>

                    <Link href="/firm-accounts/create">
                        <button class="px-4 py-2 ml-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-800 border border-transparent rounded-lg active:bg-blue-900 hover:bg-blue-900 focus:outline-none focus:shadow-outline-blue">
                            Add new transaction
                        </button>
                    </Link>
                </div>
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
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
                                    Debit
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Credit
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Balance
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    <span class="sr-only">Edit</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr 
                                v-for="acc in clientAccounts.data"
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
                                    <Link :href="`/client-account/${ acc.id }/edit`" class="font-medium text-blue-600 hover:underline">Edit</Link>
                                    <Link @click="deleteAcc(acc)" as="button" class="ml-3 font-medium text-red-600 hover:underline">Delete</Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Paginator -->
            <Pagination :links="clientAccounts.links" :total="clientAccounts.total" :from="clientAccounts.from" :to="clientAccounts.to"/>
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
        let searchClients = ref(props.filters.search);

        watch(searchClients, throttle(value => {
            Inertia.get('/client-account', { search: value }, {
                preserveState: true,
                replace: true,
            });
        }, 500));

        return { searchClients };
    },
    props: { 
        clientAccounts: Object,
        filters: Object
     },
    components: { Head, Header, Sidebar, Pagination, ref },
    layout: Layout,
    methods: {
        deleteAcc(acc) {
            if (confirm('Are you sure you want to delete this client?')) {
                Inertia.delete(`/client-account/${ acc.id }`);
            }
        }
    },
};
</script>