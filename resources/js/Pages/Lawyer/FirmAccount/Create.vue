<template>
    <Head title="Add new transaction" />

    <div class="flex flex-col flex-1">
        <div class="bg-white rounded-md shadow">
            <main class="h-full pb-16 overflow-y-auto mx-3 my-4">
                <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                    <form @submit.prevent="submit">
                        <div class="mb-6">
                            <label for="date" class="block mb-2 text-sm">
                                Date
                            </label>
                            <input
                                v-model="form.date"
                                type="text"
                                id="date"
                                class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/2 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder=""
                                required
                            />
                            <p
                                v-if="form.errors.date"
                                v-text="form.errors.date"
                                class="mt-2 text-sm text-red-600 dark:text-red-500"
                            ></p>
                        </div>
                        <div class="mb-6">
                            <label for="description" class="block mb-2 text-sm">
                                Description
                            </label>
                            <input
                                v-model="form.description"
                                type="text"
                                id="description"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/2 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder=""
                                required
                            />
                            <p
                                v-if="form.errors.description"
                                v-text="form.errors.description"
                                class="mt-2 text-sm text-red-600 dark:text-red-500"
                            ></p>
                        </div>

                        <div class="mb-6">
                            <label
                                for="transaction_type"
                                class="block mb-2 text-sm"
                            >
                                Transaction Type
                            </label>
                            <select
                                id="transaction_type"
                                name="transaction_type"
                                autocomplete="transaction-type"
                                v-model="form.transaction_type"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/2 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            >
                                <option>Funds In</option>
                                <option>Funds Out</option>
                            </select>
                            <p
                                v-if="form.errors.transaction_type"
                                v-text="form.errors.transaction_type"
                                class="mt-2 text-sm text-red-600 dark:text-red-500"
                            ></p>
                        </div>

                        <div class="mb-6">
                            <label
                                for="document_number"
                                class="block mb-2 text-sm"
                            >
                                Document number
                            </label>
                            <input
                                v-model="form.document_number"
                                type="text"
                                id="document_number"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/2 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder=""
                                required
                            />
                            <p
                                v-if="form.errors.document_number"
                                v-text="form.errors.document_number"
                                class="mt-2 text-sm text-red-600 dark:text-red-500"
                            ></p>
                        </div>

                        <div class="mb-6">
                            <label for="upload" class="block mb-2 text-sm">
                                Upload Document
                            </label>
                            <input
                                v-model="form.upload"
                                type="text"
                                id="upload"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/2 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder=""
                                required
                            />
                            <p
                                v-if="form.errors.upload"
                                v-text="form.errors.upload"
                                class="mt-2 text-sm text-red-600 dark:text-red-500"
                            ></p>
                        </div>

                        <div class="mb-6">
                            <label for="amount" class="block mb-2 text-sm">
                                Amount
                            </label>
                            <input
                                v-model="form.amount"
                                type="text"
                                id="amount"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/4 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder=""
                                required
                            />
                            <p
                                v-if="form.errors.amount"
                                v-text="form.errors.amount"
                                class="mt-2 text-sm text-red-600 dark:text-red-500"
                            ></p>
                        </div>

                        <div class="mb-6">
                            <label
                                for="payment_method"
                                class="block mb-2 text-sm"
                            >
                                Payment Method
                            </label>
                            <select
                                id="payment_method"
                                name="payment_method"
                                autocomplete="transaction-type"
                                v-model="form.payment_method"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/2 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            >
                                <option>Bank Remittance</option>
                                <option>Bank Transfer</option>
                                <option>Cash</option>
                                <option>Cheque</option>
                            </select>
                            <p
                                v-if="form.errors.payment_method"
                                v-text="form.errors.payment_method"
                                class="mt-2 text-sm text-red-600 dark:text-red-500"
                            ></p>
                        </div>

                        <div class="mb-6">
                            <label for="name" class="block mb-2 text-sm">
                                Reference
                            </label>
                            <input
                                v-model="form.reference"
                                type="text"
                                id="reference"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/4 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder=""
                                required
                            />
                            <p
                                v-if="form.errors.reference"
                                v-text="form.errors.reference"
                                class="mt-2 text-sm text-red-600 dark:text-red-500"
                            ></p>
                        </div>

                        <button
                            type="submit"
                            class="mr-5 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                            :disabled="form.processing"
                        >
                            Cancel
                        </button>

                        <button
                            type="submit"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                            :disabled="form.processing"
                        >
                            Save Transaction
                        </button>
                    </form>
                </div>
            </main>
        </div>
    </div>
</template>

<script>
import { Head } from "@inertiajs/inertia-vue3";
import Layout from "../Shared/Layout";
import { useForm } from "@inertiajs/inertia-vue3";

export default {
    setup() {
        let form = useForm({
            description: "",
            transaction_type: "",
            debit: "",
            credit: "",
            bank_account_id: "",
        });

        let submit = () => {
            form.post("/firm-account");
        };

        return { form, submit };
    },
    props: {
        firmAccounts: Object,
    },
    components: { Head },
    layout: Layout,
};
</script>
