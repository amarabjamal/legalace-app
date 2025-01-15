<template>

    <Head :title="page_title" />

    <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs" />

    <div class="grid gap-6 mb-8 md:grid-cols-3 mt-4">
        <div v-for="bank_account in bank_accounts" :key="bank_account.id"
            class="min-w-0 bg-white border border-gray-300 rounded-md overflow-hidden ease-in-out duration-300 hover:shadow-md hover:scale-105 hover:-translate-y-5">
            <div class="px-4 mb-2 border-b bg-gray-50 flex justify-between items-center">
                <h4 class="py-2 text-sm uppercase font-semibold text-gray-500 w-1/2 truncate">{{ bank_account.label }}
                </h4>
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
                        Balance
                    </td>
                    <td class="font-bold">
                        {{ bank_account.opening_balance.amount }}
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

        <div v-for="bank_account in bank_accounts" :key="bank_account.id"
            class="min-w-0 bg-white border border-gray-300 rounded-md overflow-hidden ease-in-out duration-300 hover:shadow-md">
            <div class="px-4 mb-2 border-b bg-gray-50 flex justify-between items-center">
                <h4 class="py-2 text-sm uppercase font-semibold text-gray-500 w-1/2 truncate">Filter statistics:
                </h4>
                <select v-model="selectedPeriod" @change="filterByPeriod" class="m-1 px-4 py-2 border rounded-md hover:cursor-pointer">
                    <option value="this_month">This Month</option>
                    <option value="this_year">Current Year</option>
                    <option value="last_month">Last Month</option>
                    <option value="last_3_months">Last 3 Months</option>
                    <option value="last_6_months">Last 6 Months</option>
                    <option value="last_year">Last Year</option>
                    <option value="next_month">Next Month</option>
                    <option value="next_3_months">Next 3 Months</option>
                    <option value="next_6_months">Next 6 Months</option>
                    <option value="next_year">Next Year</option>
                </select>
                <!-- <span :class="accountTypeClass(bank_account.account_type)">{{ bank_account.account_type }}</span> -->
            </div>
            <p class="text-gray-600 p-2 text-sm">
            <table class="border-separate border-spacing-2">
                <tr>
                    <td width="120px">
                        Funds in
                    </td>
                    <td class="font-bold">
                        <span class="font-bold">RM {{ formatToTwoDecimal(funds_in) }}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        Funds out
                    </td>
                    <td class="font-bold">
                        RM {{ formatToTwoDecimal(funds_out) }}
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

        <div v-if="bank_accounts.length === 0"
            class="md:col-span-3 h-24 flex justify-center items-center border border-gray-300 w-full text-center bg-gray-100 text-gray-500 rounded-md">
            <span>No records found.</span>
        </div>
    </div>

    <div class="flex items-center justify-between mb-6">
        <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset"></search-filter>
        <!-- Spacer to replace search-filter -->
        <!-- <div class="mr-4 w-full max-w-md"></div> -->
        
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
                <tr v-for="acc in firmAccounts.data" :key="acc.id" class="bg-white border-b" :style="{ background: acc.transaction_type == 'funds in' ? '#cbd7c7' : '#e8b5b5' }">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        {{ acc.date }}
                    </th>
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        {{ formatString(acc.description) }}
                    </th>
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        {{ formatString(acc.transaction_type) }}
                    </th>
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        {{ formatString(acc.payment_method) }}
                    </th>
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        {{ formatToTwoDecimal(acc.transaction_type == "funds in" ? acc.debit : acc.credit) }}
                    </th>
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        {{ acc.document_no }}
                        <!-- {{ acc.transaction_id }} -->
                    </th>
                    <td class="px-6 py-4 text-left">
                        <Link :href="`/lawyer/firm-accounts/${acc_id}/${acc.id}/view`"
                            class="font-medium text-blue-600 hover:underline">View</Link>
                        <!-- <Link  :href="`/lawyer/firm-accounts/${acc_id}/${acc.id}/edit`"
                            class="ml-3 font-medium text-blue-600 hover:underline">Edit</Link> -->
                        <Link v-if="acc.transaction_id === '' || acc.transaction_id === null" :href="`/lawyer/firm-accounts/${acc_id}/${acc.id}/edit`"
                            class="ml-3 font-medium text-blue-600 hover:underline">Edit</Link>
                        <button @click="showDeleteConfirmation(acc)" as="button" class="ml-3 font-medium text-red-600 hover:underline">
                        Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- Paginator -->
    <Pagination :links="firmAccounts.links" :total="firmAccounts.total" :from="firmAccounts.from"
        :to="firmAccounts.to" />

    <!-- Modal Component -->
    <ConfirmationModel :showModal="showDeleteModal" @confirm="handleDelete" @cancel="cancelDelete" />

</template>

<script>
import Layout from "../Shared/Layout";
import { Head } from "@inertiajs/inertia-vue3";
import SearchFilter from '../../../Shared/SearchFilter';
import Pagination from "../../../Shared/Pagination.vue";
import Icon from '../../../Shared/Icon';
import { Inertia } from "@inertiajs/inertia";
import throttle from 'lodash/throttle';
import pickBy from "lodash/pickBy";
import mapValues from "lodash/mapValues";
import { ref, watch } from "vue";
import ConfirmationModel from "../../../Shared/ConfirmationModel.vue";

export default {
    setup(props) {
        let searchAccount = ref(props.filters.search);

        watch(
            searchAccount,
            throttle((value) => {
                Inertia.get(
                    `/lawyer/firm-accounts/${props.acc_id}`,
                    { search: value },
                    {
                        preserveState: true,
                        replace: true,
                    },
                );
            }, 500),
        );

        return { searchAccount };
    },
    data() {
        return {
            form: {
                search: this.filters.search,
            },
            page_title: this.generatePageTitle(),
            page_subtitle: 'Manage your Firm Account',
            breadcrumbs: [
                { link: '/lawyer/dashboard', label: 'Lawyer' },
                { link: '/lawyer/firm-accounts', label: 'Firm Account' },
                ...this.bank_accounts.map(account => ({
                link: `/lawyer/firm-accounts/${account.id}/detail`,
                label: account.label,
                })),
            ],
            showDeleteModal: false,
            selectedAcc: null,
            selectedPeriod: this.selectedPeriod || 'this_month',
        }

    },
    watch: {
        form: {
            deep: true,
            handler: throttle(function () {
                this.$inertia.get(
                    `/lawyer/firm-accounts/${this.acc_id}`,
                    pickBy(this.form),
                    {
                        preserveState: true,
                    },
                );
            }, 150),
        },
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
        selectedPeriod: String,
    },
    mounted() {
        console.log("Bank Accounts in mounted:", this.bank_accounts);
    },
    // components: { Head, Pagination, ref },
    components: { SearchFilter, Icon, Pagination, ref, ConfirmationModel },
    layout: Layout,
    methods: {
        generatePageTitle() {
        // If there are bank accounts, include their labels in the page title
        if (this.bank_accounts.length > 0) {
            const accountLabels = this.bank_accounts.map(account => account.label).join(', ');
            return `${accountLabels}`;
            // return `Firm Account - ${accountLabels}`;
        } else {
            return 'Firm Account'; // Fallback title if no bank accounts are available
        }
        },
        deleteAcc(acc) {
            if (confirm('Are you sure you want to delete this client?')) {
                Inertia.delete(`/lawyer/firm-accounts/${acc.id}`);
            }
        },
        filterList(type) {
            Inertia.get(`/lawyer/firm-accounts/${this.acc_id}/${type}/detail/`, { search: type }, {
                preserveState: true,
                replace: true,
            });
        },
        formatString(str) {
            return str
                .split('_') // Split by underscores
                .map(word => word.charAt(0).toUpperCase() + word.slice(1)) // Capitalize the first letter of each word
                .join(' '); // Join the words with spaces
        },
        formatToTwoDecimal(num) {
            if (num == null) {
                return "0.00";
            } else {
                return num.toFixed(2); // Formats the number to 2 decimal places
            }
        },
        showDeleteConfirmation(acc) {
            this.selectedAcc = acc;
            this.showDeleteModal = true;
        },
        handleDelete() {
            if (this.selectedAcc) {
                Inertia.delete(`/lawyer/firm-accounts/${this.selectedAcc.id}`);
                this.showDeleteModal = false;
            }
        },
        cancelDelete() {
            this.showDeleteModal = false;
        },
        filterByPeriod() {
            // Send the selected month value to the backend
            Inertia.get(`/lawyer/firm-accounts/${this.bank_accounts[0].id}/detail`, { period: this.selectedPeriod }, {
                preserveState: true,
                replace: true,
            });
        },
        reset() {
            this.form = mapValues(this.form, () => null);
        },
    },
};
</script>