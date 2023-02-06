<template>
    <Head title="Manage bank accounts" />

    <div class="flex flex-col flex-1">
        <main class="h-full pb-16 overflow-y-auto">
            
            <div class="container px-6 mx-auto grid">
                <h2
                class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200"
                >
                Manage Bank Accounts
                </h2>
    
                <!-- Main Content Start -->
                <div v-if="$page.props.flash.message" class="flex p-4 mb-4 bg-blue-100 rounded-lg" role="alert">
                    <svg class="flex-shrink-0 w-5 h-5 text-blue-900" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                    <div class="ml-3 text-sm font-medium text-blue-900">
                        {{ $page.props.flash.message }}
                    </div>
                    <button type="button" @click="$page.props.flash.message = ''" class="ml-auto -mx-1.5 -my-1.5 bg-blue-100 text-blue-500 rounded-lg focus:ring-2 focus:ring-blue-400 p-1.5 hover:bg-blue-200 inline-flex h-8 w-8">
                        <span class="sr-only">Close</span>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>
                
                <div class="flex items-center mb-4">
                    <h4 class="text-lg font-semibold text-gray-600 dark:text-gray-300">
                        Bank Accounts
                    </h4>
    
                    <Link href="/bankaccounts/create">
                        <button class="px-4 py-2 ml-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-800 border border-transparent rounded-lg active:bg-blue-900 hover:bg-blue-900 focus:outline-none focus:shadow-outline-blue">
                            Register New Bank Account
                        </button>
                    </Link>
                </div>

                <div class="grid gap-6 mb-8 md:grid-cols-2">
                    <div v-for="bankAccount in bankAccounts" class="min-w-0 p-4 bg-white  shadow-xs dark:bg-gray-800">
                        <h4 class="mb-4 font-semibold text-gray-600 dark:text-gray-300">
                        {{ bankAccount.account_name }}
                        </h4>
                        <p class="text-gray-600 dark:text-gray-400">
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
                                        {{ bankAccount.account_type.name }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Lable
                                    </td>
                                    <td class="font-bold">
                                        {{ bankAccount.label }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Created By
                                    </td>
                                    <td>
                                        <span class="font-bold">{{ bankAccount.created_by.name }} </span>{{ bankAccount.created_by.name == $page.props.auth.user.name ? '(You)' : null }}
                                    </td>
                                </tr>
                            </table>
                            <div class="flex justify-end mt-3 p-2 pr-4">
                                <Link :href="`/bankaccounts/${ bankAccount.id }/edit`">Edit</Link>
                                <Link @click="deleteBankAccount(bankAccount)" as="button" class="ml-3 font-medium text-red-600 hover:underline">Delete</Link>
                            </div>
                        </p>
                    </div>
                </div>
                <!-- Main Content End -->
          </div>
        </main>
    </div>
</template>

<script>
import { Head, Link } from "@inertiajs/inertia-vue3";
import Layout from "../Shared/Layout";
import { Inertia } from "@inertiajs/inertia";

export default { 
    props: { 
        bankAccounts: Object,
     },
     methods: {
        deleteBankAccount(bankAccount) {
            if (confirm('Are you sure you want to delete this bank account?')) {
                Inertia.delete(`/bankaccounts/${ bankAccount.id }`);
            }
        }
    },
    components: { Head, Link },
    layout: Layout,
};
</script>