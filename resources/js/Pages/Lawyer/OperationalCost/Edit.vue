<template>
    <Head title="Add new operational cost" />
    <!-- <Sidebar /> -->
    
    <div class="flex flex-col flex-1">
        
        <Header title="Add New Operational Cost" />
        <main class="h-full pb-16 overflow-y-auto mx-3 my-4">
            <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <form @submit.prevent="submit">
                    <div class="mb-6">
                        <label 
                            for="name" 
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                            >
                            Details
                        </label>
                        <input 
                            v-model="form.details"
                            type="text" 
                            id="name" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/2 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                            placeholder="" 
                            required
                        />
                        <p v-if="form.errors.details" v-text="form.errors.details" class="mt-2 text-sm text-red-600 dark:text-red-500"></p>
                    </div>

                    <!-- <div class="mb-6">
                        <label 
                            for="transaction_type" 
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                            >
                            Transaction Type
                        </label>
                        <input 
                            v-model="form.id_types_id"
                            type="text" 
                            id="name" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/2 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                            placeholder="" 
                            required
                        />
                        <select  id="transaction_type" name="transaction_type" autocomplete="transaction-type" v-model="form.transaction_type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/2 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option>Online banking</option>
                                <option>Cash</option>
                                <option>Cheque</option>
                            </select>
                        <p v-if="form.errors.transaction_type" v-text="form.errors.transaction_type" class="mt-2 text-sm text-red-600 dark:text-red-500"></p>
                    </div> -->
                    <div class="mb-6">
                        <label 
                            for="name" 
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                            >
                            Amount (RM)
                        </label>
                        <input 
                            v-model="form.amount"
                            type="text" 
                            id="debit" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/4 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                            placeholder="" 
                            required
                        />
                        <p v-if="form.errors.amount" v-text="form.errors.amount" class="mt-2 text-sm text-red-600 dark:text-red-500"></p>
                    </div>
                    <div class="mb-6">
                        <label 
                            for="credit" 
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                            >
                            Recurring state
                        </label>
                        <input 
                            v-model="form.is_recurring"
                            type="text" 
                            id="credit" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/4 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                            placeholder="" 
                            required
                        />
                        <p v-if="form.errors.is_recurring" v-text="form.errors.is_recurring" class="mt-2 text-sm text-red-600 dark:text-red-500"></p>
                    </div>
                    <div class="mb-6">
                        <label 
                            for="credit" 
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                            >
                            Recurring period
                        </label>
                        <input 
                            v-model="form.recurring_period"
                            type="text" 
                            id="credit" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/4 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                            placeholder="" 
                            required
                        />
                        <p v-if="form.errors.recurring_period" v-text="form.errors.recurring_period" class="mt-2 text-sm text-red-600 dark:text-red-500"></p>
                    </div>
                    <div class="mb-6">
                        <label 
                            for="credit" 
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                            >
                            Payment state
                        </label>
                        <input 
                            v-model="form.is_paid"
                            type="text" 
                            id="credit" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/4 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                            placeholder="" 
                            required
                        />
                        <p v-if="form.errors.is_paid" v-text="form.errors.is_paid" class="mt-2 text-sm text-red-600 dark:text-red-500"></p>
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
import Header from "../Shared/Header.vue";
import Sidebar from "../Shared/Sidebar";
import { useForm } from "@inertiajs/inertia-vue3";

export default {
    setup (props) {
        let form = useForm({
            details: props.operationalCosts.details,
            amount: props.operationalCosts.amount,
            is_recurring: false,
            recurring_period: props.operationalCosts.recurring_period,
            is_paid: true,
            bank_account_id: 1,
        });

        let submit = () => {
            form.put(`/operational-cost/${props.operationalCosts.id}`);
        };

        return { form, submit };
    },
    props: { 
        operationalCosts: Object,
     },
    components: { Head, Header, Sidebar },
    layout: Layout,
};
</script>