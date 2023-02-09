<template>
    <Head title="Edit Bank Account" />

    <div class="flex flex-col flex-1">
        <main class="h-full pb-16 overflow-y-auto">
            
            <div class="container px-6 mx-auto grid">
                <h2
                class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200"
                >
                Edit Bank Account
                </h2>
    
                <!-- Main Content Start -->
                <div v-if="$page.props.flash.message" class="flex p-4 mb-4 bg-red-100 rounded-lg" role="alert">
                    <svg class="flex-shrink-0 w-5 h-5 text-red-700" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                    <div class="ml-3 text-sm font-medium text-red-700">
                        {{ $page.props.flash.message }}
                    </div>
                    <button type="button" @click="$page.props.flash.message = ''" class="ml-auto -mx-1.5 -my-1.5 bg-red-100 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex h-8 w-8">
                        <span class="sr-only">Close</span>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>

                <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
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
                            <label for="bank_account_type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Account Type
                            </label>
                            <select 
                                v-model="form.bank_account_type"
                                id="bank_account_type" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:border-purple-400 focus:shadow-outline-purple block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required
                            >
                                <option value="clientaccount">Client Account</option>
                                <option value="firmaccount">Firm Account</option>
                            </select>
                            <p v-if="form.errors.bank_account_type" v-text="form.errors.bank_account_type" class="mt-2 text-sm text-red-600"></p>
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
                <!-- Main Content End -->
          </div>
        </main>
    </div>
</template>

<script>
import { Head } from "@inertiajs/inertia-vue3";
import Layout from "../Shared/Layout";
import { useForm } from "@inertiajs/inertia-vue3";

export default { 
    setup (props) {
        let form = useForm({
            account_name: props.bankAccount.account_name,
            bank_name: props.bankAccount.bank_name,
            account_number: props.bankAccount.account_number,
            bank_address: props.bankAccount.bank_address,
            swift_code: props.bankAccount.swift_code,
            bank_account_type: props.bankAccount.bank_account_type.slug,
            label: props.bankAccount.label,
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
    components: { Head },
    layout: Layout,
};
</script>