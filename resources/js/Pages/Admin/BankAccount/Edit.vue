<template>
    <Head :title="page_title" />

    <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs"/>

    <div class="max-w-3xl px-6 py-8 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="submit">
            <div class="mb-6">
                <label for="account_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Account Name
                </label>
                <input 
                    v-model="form.account_name"
                    type="text" 
                    id="account_name" 
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:border-purple-400 focus:shadow-outline-purple block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                    placeholder="" 
                    required
                />
                <p v-if="form.errors.account_name" v-text="form.errors.account_name" class="mt-2 text-sm text-red-600"></p>
            </div>

            <div class="mb-6">
                <label for="bank_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Bank Name
                </label>
                <input 
                    v-model="form.bank_name"
                    type="text" 
                    id="bank_name" 
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:border-purple-400 focus:shadow-outline-purple block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                    placeholder="" 
                    required
                />
                <p v-if="form.errors.bank_name" v-text="form.errors.bank_name" class="mt-2 text-sm text-red-600"></p>
            </div>

            <div class="mb-6">
                <label for="account_number" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Account Number
                </label>
                <input 
                    v-model="form.account_number"
                    type="text" 
                    id="account_number" 
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:border-purple-400 focus:shadow-outline-purple block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                    placeholder="" 
                    required
                />
                <p v-if="form.errors.account_number" v-text="form.errors.account_number" class="mt-2 text-sm text-red-600"></p>
            </div>

            <div class="mb-6">
                <label for="bank_address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Bank Address
                </label>
                <input 
                    v-model="form.bank_address"
                    type="text" 
                    id="bank_address" 
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:border-purple-400 focus:shadow-outline-purple block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                    placeholder="" 
                    required
                />
                <p v-if="form.errors.bank_address" v-text="form.errors.bank_address" class="mt-2 text-sm text-red-600"></p>
            </div>

            <div class="mb-6">
                <label for="swift_code" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    SWIFT Code
                </label>
                <input 
                    v-model="form.swift_code"
                    type="text" 
                    id="swift_code" 
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:border-purple-400 focus:shadow-outline-purple block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                    placeholder="" 
                    required
                />
                <p v-if="form.errors.swift_code" v-text="form.errors.swift_code" class="mt-2 text-sm text-red-600"></p>
            </div>

            <div class="mb-6">
                <label for="bank_account_type_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Account Type
                </label>
                <select 
                    v-model="form.bank_account_type_id"
                    id="bank_account_type_id" 
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:border-purple-400 focus:shadow-outline-purple block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    required
                >
                    <option value="1">Client Account</option>
                    <option value="2">Firm Account</option>
                </select>
                <p v-if="form.errors.bank_account_type_id" v-text="form.errors.bank_account_type_id" class="mt-2 text-sm text-red-600"></p>
            </div>

            <div class="mb-6">
                <label for="label" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Label
                </label>
                <input 
                    v-model="form.label"
                    type="text" 
                    id="label" 
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:border-purple-400 focus:shadow-outline-purple block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                    placeholder="" 
                    required
                />
                <p v-if="form.errors.label" v-text="form.errors.label" class="mt-2 text-sm text-red-600"></p>
            </div>

            <button 
                type="submit" 
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center"
                :disabled="form.processing"
                >
                Submit
            </button>

            <Link 
                href="/admin/bankaccounts"
                as="button"  
                class="ml-2 text-gray-900 focus:outline-none bg-white border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center"
                :disabled="form.processing"
                >
                Cancel
            </Link>
        </form>
    </div>
</template>

<script>
import Layout from "../Shared/Layout";
import { useForm } from "@inertiajs/inertia-vue3";

export default { 
    setup (props) {
        let form = useForm({
            label: props.bankAccount.label,
            account_name: props.bankAccount.account_name,
            bank_name: props.bankAccount.bank_name,
            account_number: props.bankAccount.account_number,
            bank_address: props.bankAccount.bank_address,
            swift_code: props.bankAccount.swift_code,
            bank_account_type_id: props.bankAccount.bank_account_type_id,
        });

        let submit = () => {
            form.put(`/admin/bankaccounts/${props.bankAccount.id}`);
        };

        return { form, submit };
    },
    props: {
        bankAccount: Object,
        accountType: Object,
    },
    components: { 

    },
    layout: Layout,
    data() {
        return {
            page_title: this.bankAccount.label,
            breadcrumbs: [
                { link: '/admin', label: 'Dashboard'},
                { link: '/admin/bankaccounts', label: 'Bank Accounts'},
                { link: null, label: this.bankAccount.label},
                { link: null, label: 'Edit'},
            ],
        }
    },
};
</script>