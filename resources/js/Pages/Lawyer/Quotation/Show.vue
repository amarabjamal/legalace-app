<template>
    <Head title="Generate Quotation" />

    <h1 class="mb-6 text-xl font-bold">
        <Link class="text-blue-500 hover:text-blue-600" href="/lawyer/case-files/">Case Files</Link>
        <span class="text-blue-500 font-medium mx-2">/</span>
        <Link class="text-blue-500 hover:text-blue-600" :href="`/lawyer/case-files/${form.case_file_id}`">{{ case_file.file_number }}</Link>
        <span class="text-blue-500 font-medium mx-2">/</span>
        <span class="font-medium">Quotation</span>
    </h1>

    <div class="mx-auto max-w-7xl py-16 px-4 sm:px-6 lg:px-8">
        <div class="mx-auto grid max-w-2xl grid-rows-1 items-start gap-x-8 gap-y-8 lg:mx-0 lg:max-w-none lg:grid-cols-3">
            <div class="lg:col-start-3 lg:row-end-1">
                <div class="rounded-lg bg-gray-50/100 shadow-sm ring-gray-900/5">
                    <dl></dl>
                    <div class="mt-6 border-t border-gray-900/5 px-6 py-6">
                        <a href="#" class="text-sm font-semibold leading-6 text-gray-900/100">Download receipt →</a>
                    </div>
                </div>
            </div>
    
            <div class="bg-white/100 mx-4 px-4 py-8 shadow-sm ring-gray-900/5 sm:mx-0 sm:rounded-lg sm:px-8 sm:pb-14 lg:row-span-2">
                <h2 class="text-base font-semibold text-gray-900/100">Invoice</h2>
    
                <dl class="mt-6 grid grid-cols-1 text-sm leading-6 sm:grid-cols-2 ">
                    <div class="pr-4">
                        <dt class="inline text-gray-700/100">Issued on</dt>
                        <dd class="inline text-gray-700/100">
                            <time datetime="2023-23-01">January 23, 2023</time>
                        </dd>
                    </div>
                    <div class="mt-2 sm:mt-0 sm:pl-4">
                        <dt class="inline text-gray-700/100">Due on</dt>
                        <dd class="inline text-gray-700/100">
                            <time datetime="2023-23-01">January 23, 2023</time>
                        </dd>
                    </div>
                    <div class="mt-6 border-t border-gray-900/5 pt-6 sm:pr-4"></div>
                    <div class="mt-8 sm:mt-6 sm:border-t sm:border-gray-900/5 sm:pl-4 sm:pt-6"></div>
                </dl>

                <table class="mt-16 w-full whitespace-nowrap text-left text-sm leading-6">
                    <thead class="border-b border-gray-200/100 text-gray-900/100">
                        <tr>
                            <th scope="col"></th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                </table>
            </div>

            <div class="col-start-3">
                <h2 class="text-sm leading-6 font-semibold text-gray-900/100">Activity</h2>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header flex justify-between items-center">
            <div class="card-header-title font-size-lg text-capitalize font-weight-normal">
                Quotation for {{ case_file.matter }} ({{ case_file.file_number }})
            </div>
            <div>
                <a target="_blank" :href="`/lawyer/case-files/${form.case_file_id}/quotation/pdf`" class="mr-2 text-white bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2 text-center">PDF</a>
                <a :href="`/lawyer/case-files/${form.case_file_id}/quotation/email`"  class="text-white bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2 text-center">Email</a>
            </div>
        </div>

        <div class="card-body">
            <form @submit.prevent="submit">
                <div class="quotation-section">
                    <h3>SCOPE OF SERVICES & LEGAL FEES</h3>

                    <table class="w-full">
                        <thead class="text-xs text-gray-200 uppercase bg-blue-900">
                            <th class="px-6 py-3">No.</th>
                            <th class="px-6 py-3">Work Descriptions</th>
                            <th class="px-6 py-3">Fee (RM)</th>
                            <th class="px-3 py-3"></th>
                        </thead>
                        <tbody>
                            <tr 
                                v-for="(workDescription, index) in form.work_descriptions" 
                                :key="index"
                                class="border-slate-300 border-b text-gray-700"
                                valign="top"
                            >
                                <td class="px-6 py-3">
                                    {{ index + 1 }}
                                </td>
                                <td class="px-6 py-3">
                                    <input 
                                        v-model="workDescription.description"
                                        :aria-label="`Work Description #${index+1}`"
                                        type="text" 
                                        required
                                        class="mr-4 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                                    >
                                    <p v-if="form.errors[`work_descriptions.${index}.description`]" v-text="form.errors[`work_descriptions.${index}.description`]" class="mt-2 text-sm text-red-600"></p>
                                </td>
                                <td class="px-6 py-3">
                                    <input 
                                        v-model="workDescription.fee"
                                        @change="calculateTotal"
                                        min="0.00"
                                        step="0.01"
                                        type="number"
                                        required 
                                        class="text-right mr-4 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                                    >
                                    <p v-if="form.errors[`work_descriptions.${index}.fee`]" v-text="form.errors[`work_descriptions.${index}.fee`]" class="mt-2 text-sm text-red-600"></p>
                                </td>
                                <td class="py-3">
                                    <button 
                                        :disabled="form.work_descriptions.length <= 1" 
                                        @click="removeWorkDescription(index)"
                                        
                                        :class="'text-red-500'"
                                    >
                                        <TrashIcon class="inline-block h-5 w-5"/>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="my-4 w-100 flex justify-center">
                        <button class="button text-center" @click="addWorkDescription">
                            + Add a description
                        </button>
                    </div>

                    <div class="mt-5 flex justify-end">
                        <div class="flex">
                            <div class="mr-3">
                                <div>
                                    <p class="text-muted">Subtotal</p>
                                </div>
                                <div>
                                    <p class="text-muted">Tax (6%)</p>
                                </div>
                                <div>
                                    <h3 class="text-lg">Total</h3>
                                </div>
                            </div>
                            <div class="total-column">
                                <div>
                                    <span>{{ subtotal }}</span>
                                </div>
                                <div>
                                    <span>{{ tax }}</span>
                                </div>
                                <div>
                                    <h3>{{ total }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="quotation-section">
                    <h3>INITIAL DEPOSIT</h3>

                    <div class="flex">
                        <div class="m-3 basis-1/2">
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


                        <div class="m-3 basis-1/2">
                            <label 
                                for="bank_account_id" 
                                class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200"
                                >
                                Client Account
                            </label>
                            <select 
                                v-model="form.bank_account_id"
                                id="bank_account_id" 
                                @change="getBankAccountDetails($event)"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                                required
                            >
                                <option disabled value="">Please select client account</option>
                                <option v-for="client_bank_account in client_bank_accounts" :value="client_bank_account.id">{{client_bank_account.label}}</option>
                            </select>
                            <p v-if="form.errors.bank_account_id" v-text="form.errors.bank_account_id" class="mt-2 text-sm text-red-600"></p>
                        </div>
                    </div>

                </div>
                
                <div class="quotation-section flex justify-end">
                    <button 
                        type="submit" 
                        class="text-white bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2 text-center"
                        :disabled="form.processing"
                        >
                        Save
                    </button>
                </div>

            </form>
        </div>
    </div>
</template>

<script>
import { Head, Link } from "@inertiajs/inertia-vue3";
import Layout from "../Shared/Layout";
import Pagination from "../Shared/Pagination";
import { useForm } from "@inertiajs/inertia-vue3";
import { TrashIcon } from "@heroicons/vue/outline";
import { ref } from "vue";
import $ from "jquery";

export default { 
    setup (props) {
        let form = useForm({
            'case_file_id' : props.case_file.id,
            'work_descriptions' : props.case_file.work_descriptions,
            'deposit_amount': props.case_file.quotation.deposit_amount,
            'bank_account_id' : props.case_file.quotation.bank_account_id,
        });

        let subtotal = ref(0);
        let tax = ref(0);
        let total = ref(0);

        return { 
            form, 
            subtotal,
            tax,
            total
        };
    },
    methods: {
        addWorkDescription() {
            this.form.work_descriptions.push({
                "description" : "",
                "fee" : 0.00,
            });
        },
        removeWorkDescription(index) {
            this.form.work_descriptions.splice(index, 1);
            this.calculateTotal();
        },
        calculateTotal() {
            this.subtotal = 0;
            this.form.work_descriptions.forEach(element => {
                this.subtotal += element.fee;
            });

            this.tax = this.subtotal * 0.06;
            this.total = this.subtotal + this.tax;
        },
        getBankAccountDetails(event) {
            $.ajax({
                URL:'/lawyer/getbankaccountdetails/' + event.target.value,
                type: 'get',
                dataType: 'text',
                success: function (response) {
                    // console.log(response);
                    // alert('Response: ' + response.data);
                },
                error: function (request, error) {
                    alert('error: ' + error);
                }
            });
        },
        submit() {
            this.form.put(`/lawyer/case-files/${this.case_file.id}/quotation/`);
        }
    },
    components: { Head, Pagination, TrashIcon, Link },
    props: {
        case_file : Object,
        client_bank_accounts : Object,
    },
    mounted: function mounted() {
        this.calculateTotal()
    },
    layout: Layout,
};
</script>

<style scoped>
.client-info {
    margin: 20px 0px;
    padding: 20px 0px;
    border-top: 1px solid #000;
    border-bottom: 1px solid #000;
}

.quotation-section {
    margin: 20px 0px;
    padding: 0px 10px;
}

.quotation-section h3 {
    font-weight: bold;
    margin-bottom: 10px;
}

.total-column {
    min-width: 100px;
    text-align: right;
}
</style>