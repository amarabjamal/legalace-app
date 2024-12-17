<template>

    <Head :title="page_title" />

    <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs" />

    <div class="grid gap-6 mb-8 md:grid-cols-3 mt-4">
        <div v-for="bank_account in bank_accounts.data" :key="bank_account.id"
            class="min-w-0 bg-white border border-gray-300 rounded-md overflow-hidden ease-in-out duration-300 hover:shadow-md hover:scale-105 hover:-translate-y-5">
            <div class="px-4 mb-2 border-b bg-gray-50 flex justify-between items-center">
                <h4 class="py-2 text-sm uppercase font-semibold text-gray-500 w-1/2 truncate">{{ bank_account.label }}
                </h4>
                <!-- <span :class="accountTypeClass(bank_account.account_type)">{{ bank_account.account_type }}</span> -->
            </div>
            <p class="text-gray-600 p-2 text-sm">
            <table class="border-separate border-spacing-2">
                <tr>
                    <td width="120px">
                        Bank Name
                    </td>
                    <td class="font-bold">
                        <span class="font-bold">{{ bank_account.bank_name }}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        Account Name
                    </td>
                    <td class="font-bold">
                        {{ bank_account.account_name }}
                    </td>
                </tr>
                <tr>
                    <td>
                        Account Number
                    </td>
                    <td class="font-bold">
                        {{ bank_account.account_number }}
                    </td>
                </tr>
                <tr>
                    <td>
                        SWIFT Code
                    </td>
                    <td class="font-bold">
                        {{ bank_account.swift_code }}
                    </td>
                </tr>
                <tr>
                    <td>
                        Account Type
                    </td>
                    <td class="font-bold">
                        {{ bank_account.account_type }}
                    </td>
                </tr>
            </table>
            <div class="flex space-x-2 justify-end p-2 pr-4">
                <!-- <Link :href="`/admin/bank-accounts/${ bank_account.id }`">View</Link>
                    <Link :href="`/admin/bank-accounts/${ bank_account.id }/edit`">Edit</Link>
                    <Link @click="deleteBankAccount(bank_account)" as="button" class="font-medium text-red-600 hover:underline">Delete</Link> -->
            </div>
            </p>
        </div>

        <div v-for="bank_account in bank_accounts.data" :key="bank_account.id"
            class="min-w-0 bg-white border border-gray-300 rounded-md overflow-hidden ease-in-out duration-300 hover:shadow-md hover:scale-105 hover:-translate-y-5">
            <div class="px-4 mb-2 border-b bg-gray-50 flex justify-between items-center">
                <h4 class="py-2 text-sm uppercase font-semibold text-gray-500 w-1/2 truncate">This Month
                </h4>
                <!-- <span :class="accountTypeClass(bank_account.account_type)">{{ bank_account.account_type }}</span> -->
            </div>
            <p class="text-gray-600 p-2 text-sm">
            <table class="border-separate border-spacing-2">
                <tr>
                    <td width="120px">
                        Funds in
                    </td>
                    <td class="font-bold">
                        <span class="font-bold">{{ funds_in }}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        Funds out
                    </td>
                    <td class="font-bold">
                        {{ funds_out }}
                    </td>
                </tr>    
            </table>
            <div class="flex space-x-2 justify-end p-2 pr-4">
                <!-- <Link :href="`/admin/bank-accounts/${ bank_account.id }`">View</Link>
                    <Link :href="`/admin/bank-accounts/${ bank_account.id }/edit`">Edit</Link>
                    <Link @click="deleteBankAccount(bank_account)" as="button" class="font-medium text-red-600 hover:underline">Delete</Link> -->
            </div>
            </p>
        </div>
    </div>

    <div class="grid gap-6 mb-8 md:grid-cols-3 mt-4">

        <div v-if="bank_accounts.data.length === 0"
            class="md:col-span-3 h-24 flex justify-center items-center border border-gray-300 w-full text-center bg-gray-100 text-gray-500 rounded-md">
            <span>No records found.</span>
        </div>
    </div>

    <div class="flex items-center justify-between mb-6">
        <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset"></search-filter>   
        <div>Filter By:</div>     
        <button class="btn-primary" v-on:click="filterList(0)">
            Funds Out
        </button>
        <button class="btn-primary" v-on:click="filterList(1)">
            Funds In
        </button>
        <Link class="btn-primary" :href="`/lawyer/firm-accounts/${this.acc_id}/create`">
        <span>Create</span>
        <span class="hidden md:inline">&nbsp;Transaction</span>
        </Link>
    </div>

    <div class="flex items-center mb-4">
        <h4 class="text-lg font-semibold">
            Transcation lists
        </h4>

        <!-- <Link href="/firm-account/create">
            <button class="px-4 py-2 ml-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-800 border border-transparent rounded-lg active:bg-blue-900 hover:bg-blue-900 focus:outline-none focus:shadow-outline-blue">
                Add new transaction
            </button>
        </Link> -->
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
                        Payment Type
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Amount
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Document No
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="acc in firmAccounts.data" :key="acc.id" class="bg-white border-b">
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
                        {{ acc.payment_method }}
                    </th>
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        {{ acc.transaction_type == "funds in" ? acc.debit : acc.credit }}
                    </th>
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        {{ acc.balance }}
                    </th>
                    <td class="px-6 py-4 text-left">
                        <Link :href="`/firm-account/${acc.id}/edit`"
                            class="font-medium text-blue-600 hover:underline">Edit</Link>
                        <Link @click="deleteAcc(acc)" as="button" class="ml-3 font-medium text-red-600 hover:underline">
                        Delete</Link>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- Paginator -->
    <Pagination :links="firmAccounts.links" :total="firmAccounts.total" :from="firmAccounts.from"
        :to="firmAccounts.to" />
</template>

<script>
import { Head } from "@inertiajs/inertia-vue3";
import SearchFilter from '../../../Shared/SearchFilter';
import Layout from "../Shared/Layout";
import Pagination from "../../../Shared/Pagination.vue";
import Icon from '../../../Shared/Icon';
import { Inertia } from "@inertiajs/inertia";
import throttle from 'lodash/throttle';
import { ref, watch } from "vue";


export default {
    setup(props) {
        let searchAccount = ref(props.filters.search);

        watch(searchAccount, throttle(value => {
            
            Inertia.get('/lawyer/firm-account', { search: value }, {
                preserveState: true,
                replace: true,
            });
        }, 500));

        return { searchAccount };
    },
    data() {
        return {
            form: {
                search: this.filters.search,
            },
            page_title: 'Firm Accounts',
            breadcrumbs: [
                { link: '/lawyer/dashboard', label: 'Lawyer' },
                { link: null, label: 'Firm Accounts' },
            ],
        }
    },
    props: {
        firmAccounts: Object,
        filters: Object,
        acc: Object,
        acc_id: String,
        bank_accounts: Object,
        filters: Object,
        funds_in: Object,
        funds_out: Object,
    },
    // components: { Head, Pagination, ref },
    components: { SearchFilter, Icon, Pagination, ref },
    layout: Layout,
    methods: {
        deleteAcc(acc) {
            if (confirm('Are you sure you want to delete this client?')) {
                Inertia.delete(`/firm-account/${acc.id}`);
            }
        },
        filterList(type) {
            Inertia.get(`/lawyer/firm-accounts/${this.acc_id}/${type}/detail/`, { search: type }, {
                preserveState: true,
                replace: true,
            });
        }
    },
};
</script>