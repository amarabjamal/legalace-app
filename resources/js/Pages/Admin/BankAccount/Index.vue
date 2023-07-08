<template>
    <Head :title="page_title" />

    <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs"/>
    
    <div class="flex justify-between items-center mb-6">
        <search-filter v-model="form.search" class="mr-4 w-full max-w-md"  @reset="reset"></search-filter>
        <Link class="btn-primary" :href="`/admin/bank-accounts/create`">
            <span>Create</span>
            <span class="hidden md:inline">&nbsp;Bank Account</span>
        </Link>
    </div>

    <div class="grid gap-6 mb-8 md:grid-cols-3 mt-4">
        <div v-for="bank_account in bank_accounts.data" class="min-w-0 bg-white border border-gray-300 rounded-md overflow-hidden ease-in-out duration-300 hover:shadow-md hover:scale-105 hover:-translate-y-5">
            <div class="px-4 mb-2 border-b bg-gray-50 flex justify-between items-center">
                <h4 class="py-2 text-sm uppercase font-semibold text-gray-500 w-1/2 truncate">{{ bank_account.label }}</h4>
                <span :class="accountTypeClass(bank_account.account_type)">{{ bank_account.account_type }}</span>
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
                    <Link :href="`/admin/bank-accounts/${ bank_account.id }`">View</Link>
                    <Link :href="`/admin/bank-accounts/${ bank_account.id }/edit`">Edit</Link>
                    <Link @click="deleteBankAccount(bank_account)" as="button" class="font-medium text-red-600 hover:underline">Delete</Link>
                </div>
            </p>
        </div>
        <div v-if="bank_accounts.data.length === 0" class="md:col-span-3 h-24 flex justify-center items-center border border-gray-300 w-full text-center bg-gray-100 text-gray-500 rounded-md"><span>No records found.</span></div>
    </div>
</template>

<script>
import Layout from "../Shared/Layout";
import SearchFilter from '../../../Shared/SearchFilter';
import Icon from '../../../Shared/Icon';
import Pagination from '../../../Shared/Pagination';
import throttle from 'lodash/throttle';
import pickBy from 'lodash/pickBy'
import mapValues from 'lodash/mapValues';
import { cva } from "class-variance-authority";

export default { 
    components: { 
        SearchFilter,
        Icon,
        Pagination,
    },
    layout: Layout,
    props: { 
        filters: Object,
        bank_accounts: Object,
    },
    data() {
        return {
            page_title: 'Bank Accounts',
            breadcrumbs: [
                { link: '/admin/dashboard', label: 'Admin'},
                { link: null, label: 'Bank Accounts'},
            ],
            form: {
                search: this.filters.search,
            },
        }
    },
    watch: {
        form: {
          deep: true,
          handler: throttle(function () {
            this.$inertia.get(`/admin/bank-accounts`, pickBy(this.form), {preserveState: true})
          }, 150),
        },
    },
    methods: {
        deleteBankAccount(bank_account) {
            if (confirm('Are you sure you want to delete this bank account?')) {
                this.$inertia.delete(`/admin/bank-accounts/${ bank_account.id }`);
            }
        },
        reset() {
          this.form = mapValues(this.form, () => null);
        },
        accountTypeClass(type) {
            return cva("text-xs py-1 px-2 border rounded-sm", {
                variants: {
                    type: {
                        'Client Account':'text-blue-500 bg-blue-50  border-blue-300',
                        'Firm Account':'text-pink-500 bg-pink-50  border-pink-300',
                    }
                }
            }) ({
                type: type,
            })
        }
    },
};
</script>