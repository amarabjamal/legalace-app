<template>
    <Head :title="page_title" />

    <div class="flex justify-between items-center">
        <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs"/>

        <div class="flex items-center">
            <Link href="/admin/bank-accounts/create">
                <button class="btn-primary">
                    Create Bank Account
                </button>
            </Link>
        </div>
    </div>

    <div class="grid gap-6 mb-8 md:grid-cols-2">
        <div v-if="bankAccounts.length <= 0" class="shadow width-full text-center p-3 bg-gray-100 text-gray-500  rounded-lg  dark:bg-gray-800 dark:text-gray-300">No data.</div>
        <div 
            v-else
            v-for="bankAccount in bankAccounts" 
            class="min-w-0 bg-white  shadow-md rounded-lg overflow-hidden dark:bg-gray-800"
        >
            <h4 class="mb-4 p-4 uppercase font-semibold text-gray-500 border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
            {{ bankAccount.label }}
            </h4>
            <p class="text-gray-600 p-2 dark:text-gray-400">
                <table class="border-separate border-spacing-2">
                    <tr>
                        <td width="150px">
                            Bank Name
                        </td>
                        <td class="font-bold">
                            <span class="font-bold">{{ bankAccount.bank_name }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Account Name
                        </td>
                        <td class="font-bold">
                            {{ bankAccount.account_name }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Account Number
                        </td>
                        <td class="font-bold">
                            {{ bankAccount.account_number }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Bank Address
                        </td>
                        <td class="font-bold">
                            {{ bankAccount.bank_address }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            SWIFT Code
                        </td>
                        <td class="font-bold">
                            {{ bankAccount.swift_code }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Account Type
                        </td>
                        <td class="font-bold">
                            {{ bankAccount.bank_account_type.name }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Created By
                        </td>
                        <td>
                            <span class="font-bold">{{ bankAccount.created_by.name }}</span> <span v-if="bankAccount.created_by.name == $page.props.auth.user.name" class="text-sm text-gray-400">(You)</span>
                        </td>
                    </tr>
                </table>
                <div class="flex justify-end mt-3 p-2 pr-4">
                    <Link :href="`/admin/bank-accounts/${ bankAccount.id }/edit`">Edit</Link>
                    <Link @click="deleteBankAccount(bankAccount)" as="button" class="ml-3 font-medium text-red-600 hover:underline">Delete</Link>
                </div>
            </p>
        </div>
    </div>
</template>

<script>
import Layout from "../Shared/Layout";
import { Inertia } from "@inertiajs/inertia";

export default { 
    props: { 
        bankAccounts: Object,
     },
     methods: {
        deleteBankAccount(bankAccount) {
            if (confirm('Are you sure you want to delete this bank account?')) {
                Inertia.delete(`/admin/bank-accounts/${ bankAccount.id }`);
            }
        }
    },
    components: { 

    },
    layout: Layout,
    data() {
        return {
            page_title: 'Bank Accounts',
            breadcrumbs: [
                { link: '/admin/dashboard', label: 'Admin'},
                { link: null, label: 'Bank Accounts'},
            ],
        }
    },
};
</script>