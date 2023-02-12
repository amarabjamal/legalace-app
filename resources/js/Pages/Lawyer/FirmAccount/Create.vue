<template>
    <Head title="Add new transaction" />
    
    <div class="flex flex-col flex-1">
        <main class="h-full pb-16 overflow-y-auto mx-3 my-4">
            <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <form @submit.prevent="submit">
                    <div class="mb-6">
                        <label 
                            for="name" 
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                            >
                            Description
                        </label>
                        <input 
                            v-model="form.description"
                            type="text" 
                            id="name" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/2 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                            placeholder="" 
                            required
                        />
                        <p v-if="form.errors.description" v-text="form.errors.description" class="mt-2 text-sm text-red-600 dark:text-red-500"></p>
                    </div>

                    <div class="mb-6">
                        <label 
                            for="transaction_type" 
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                            >
                            Transaction Type
                        </label>
                        <!-- <input 
                            v-model="form.id_types_id"
                            type="text" 
                            id="name" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/2 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                            placeholder="" 
                            required
                        /> -->
                        <select  id="transaction_type" name="transaction_type" autocomplete="transaction-type" v-model="form.transaction_type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/2 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option>Online banking</option>
                                <option>Cash</option>
                                <option>Cheque</option>
                            </select>
                        <p v-if="form.errors.transaction_type" v-text="form.errors.transaction_type" class="mt-2 text-sm text-red-600 dark:text-red-500"></p>
                    </div>
                    <div class="mb-6">
                        <label 
                            for="name" 
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                            >
                            Debit
                        </label>
                        <input 
                            v-model="form.debit"
                            type="text" 
                            id="debit" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/4 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                            placeholder="" 
                            required
                        />
                        <p v-if="form.errors.debit" v-text="form.errors.debit" class="mt-2 text-sm text-red-600 dark:text-red-500"></p>
                    </div>
                    <div class="mb-6">
                        <label 
                            for="credit" 
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                            >
                            Credit
                        </label>
                        <input 
                            v-model="form.credit"
                            type="text" 
                            id="credit" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/4 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                            placeholder="" 
                            required
                        />
                        <p v-if="form.errors.credit" v-text="form.errors.credit" class="mt-2 text-sm text-red-600 dark:text-red-500"></p>
                    </div>
                    <div class="mb-6">
                        <label 
                            for="bank_account_id" 
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                            >
                            Bank Account
                        </label>
                        <input 
                            v-model="form.bank_account_id" 
                            type="text" 
                            id="" bank_account_id
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/4 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                            placeholder="" 
                            required
                        />
                        <p v-if="form.errors.bank_account_id" v-text="form.errors.bank_account_id" class="mt-2 text-sm text-red-600 dark:text-red-500"></p>
                    </div>

                    <!-- <div class="mb-6">
                        <label 
                            for="email" 
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                            >
                            Email Address
                        </label>
                        <input 
                            v-model="form.email"
                            type="email" 
                            id="email" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                            placeholder="" 
                            required
                        />
                        <p v-if="form.errors.email" v-text="form.errors.email" class="mt-2 text-sm text-red-600 dark:text-red-500"></p>
                    </div> -->

                    <!-- <div class="mb-6">
                        <label 
                            for="phone_number" 
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                            >
                            Contact Number
                        </label>
                        <input 
                            v-model="form.phone_number"
                            type="text" 
                            id="phone_number" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                            required  
                        />
                        <p v-if="form.errors.phone_number" v-text="form.errors.phone_number" class="mt-2 text-sm text-red-600 dark:text-red-500"></p>
                    </div> -->

                    <!-- <div class="mb-6">
                        <label 
                            for="address" 
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                            >
                            Address
                        </label>
                        <input 
                            v-model="form.address"
                            type="text" 
                            id="address" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                            required  
                        />
                        <p v-if="form.errors.address" v-text="form.errors.address" class="mt-2 text-sm text-red-600 dark:text-red-500"></p>
                    </div> -->

                    <button 
                        type="submit" 
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                        :disabled="form.processing"
                        >
                        Submit
                    </button>
                </form>
            </div>
        </main>  
    </div>
</template>

<script>
import { Head } from "@inertiajs/inertia-vue3";
import Layout from "../Shared/Layout";
import { useForm } from "@inertiajs/inertia-vue3";

export default {
    setup () {
        let form = useForm({
            description: '',
            transaction_type: '',
            debit:'',
            credit: '',
            bank_account_id: '',
        });

        let submit = () => {
            form.post('/firm-account');
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