<template>
    <Head title="Generate Quotation" />

    <div class="flex flex-col flex-1">
        <main class="h-full pb-16 overflow-y-auto">          
            <div class="container px-6 mx-auto grid">
                <Link class="my-6" :href="'/lawyer/casefiles/' + case_file.id "><i class="uil uil-arrow-left"></i> Back to Case File {{ case_file.file_number }}</Link>
    
                <!-- Main Content Start -->
                <div v-if="$page.props.flash.message" class="flex p-4 mb-4 bg-green-100 rounded-lg" role="alert">
                    <svg class="flex-shrink-0 w-5 h-5 text-green-700" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                    <div class="ml-3 text-sm font-medium text-green-700">
                        {{ $page.props.flash.message }}
                    </div>
                    <button type="button" @click="$page.props.flash.message = ''" class="ml-auto -mx-1.5 -my-1.5 bg-green-100 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex h-8 w-8">
                        <span class="sr-only">Close</span>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>

                <div class="card">
                    <form @submit.prevent="submit">
                    <div class="card-header flex justify-between items-center">
                        <div class="card-header-title font-size-lg text-capitalize font-weight-normal">
                            Quotation
                        </div>

                        <button 
                            type="submit" 
                            class="text-white bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2 text-center"
                            :disabled="form.processing"
                            >
                            Submit
                        </button>
                    </div>

                    <div class="card-body">
                        <p v-if="form.errors.case_file_id" v-text="form.errors.case_file_id" class="my-2 text-sm text-red-600"></p>

                        <div class="mb-2 pb-2 border-b border-gray-300">
                            File Number: {{ case_file.file_number }}
                        </div>
                        


                        <div class="mb-6">
                            <label 
                                for="deposit_amount" 
                                class="block mb-2 text-sm font-medium text-gray-900"
                                >
                                Initial Deposit
                            </label>
                            <input 
                                v-model="form.deposit_amount"
                                type="number" 
                                min="0.00"
                                step="0.01"
                                id="deposit_amount" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
                                placeholder="" 
                                required
                            />
                            <p v-if="form.errors.deposit_amount" v-text="form.errors.deposit_amount" class="mt-2 text-sm text-red-600"></p>
                        </div>


                        <div class="mb-6">
                            <label 
                                for="bank_account_id" 
                                class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200"
                                >
                                Client Account
                            </label>
                            <select 
                                v-model="form.bank_account_id"
                                id="bank_account_id" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                                required
                            >
                                <option disabled value="">Please select client account</option>
                                <option v-for="client_bank_account in client_bank_accounts" :value="client_bank_account.id">{{client_bank_account.label}}</option>
                            </select>
                            <p v-if="form.errors.bank_account_id" v-text="form.errors.bank_account_id" class="mt-2 text-sm text-red-600"></p>
                        </div>
                    </div>
                    </form>
                </div>
          </div>
        </main>
    </div>
</template>

<script>
import { Head } from "@inertiajs/inertia-vue3";
import Layout from "../Shared/Layout";
import Pagination from "../Shared/Pagination";
import { useForm } from "@inertiajs/inertia-vue3";

export default { 
    setup(props) {
        let form = useForm({
            case_file_id: props.case_file.id,
            deposit_amount: '',
            bank_account_id: '',
        });

        let submit = () => {
            form.post('/lawyer/quotations');
        };

        return { form, submit };
    },
    components: { Head, Pagination},
    props: {
        'case_file' : Object,
        'client_bank_accounts' : Object,
    },
    layout: Layout,
};
</script>