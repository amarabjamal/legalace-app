<template>
    <Head :title="page_title" />

    <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs"/>

    <div class="max-w-3xl bg-white rounded-md border border-gray-300 overflow-hidden">
        <form @submit.prevent="update">
            <div class="p-8 space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Scope of services & legal fees</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">
                        Add work items to your quotation. The total fee amount will be the initial legal fees for this case file.
                    </p>
                    <div v-if="form.errors.case_file_id" class="w-full flex p-4 mt-4 text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                        <svg aria-hidden="true" class="flex-shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                        <span class="sr-only">Info</span>
                        <div class="ml-3 text-sm font-medium">
                            {{ form.errors.case_file_id }}
                        </div>
                    </div>
        
                    <!-- Work Description Table -->
                    <div class="mt-10 w-full overflow-x-auto overflow-y-hidden  mb-10">
                        <table class="w-full whitespace-nowrap text-left text-sm leading-6">
                            <thead class="border border-gray-300 text-gray-900 bg-gray-100 select-none">
                                <tr>
                                    <th class="w-12 px-4 py-2">No.</th>
                                    <th>Work Description</th>
                                    <th class="w-40 text-right">Amount</th>
                                    <th class="w-16"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <transition-group
                                    enter-from-class="opacity-0 scale-75"
                                    enter-active-class="duration-300"
                                    leave-active-class="duration-300"
                                    leave-to-class="scale-75  opacity-0"
                                    appear 
                                >
                                    <tr v-for="(work_description, index) in form.work_descriptions" :key="getKey" class="border border-gray-300">
                                        <td class="max-w-0 px-4 py-2 align-top">
                                            {{ index + 1 }}
                                        </td>
                                        <td class="max-w-0 pr-4 py-2 align-top">
                                            <text-input v-model="work_description.description" :error="form.errors[`work_descriptions.${index}.description`]" required/>
                                        </td>
                                        <td class="pr-0 pl-0 py-2 align-top text-right tabular-nums">
                                            <div>
                                                <input v-model.lazy="work_description.fee" v-money="money" class="form-input text-right" :class="{ error: form.errors[`work_descriptions.${index}.fee`] }" required/>
                                                <div v-if="form.errors[`work_descriptions.${index}.fee`]" class="form-error w-40">{{ form.errors[`work_descriptions.${index}.fee`] }}</div>
                                            </div>
                                        </td>
                                        <td class="max-w-0 px-0 py-2 align-top">
                                            <div class="flex justify-center items-center mt-2.5">
                                                <button type="button" @click="removeItem(index)" tabindex="-1"><icon name="close-circle-fill" class="block w-5 h-5 fill-gray-400" /></button>    
                                            </div>
                                        </td>
                                    </tr>
                                </transition-group>
        
                                <tr v-if="form.work_descriptions.length === 0" class="border-x border-gray-300 bg-gray-100">
                                    <td v-if="form.errors.work_descriptions" class="text-center py-2 text-gray-500" colspan="100">{{ form.errors.work_descriptions }}</td>
                                    <td v-else class="text-center py-2 text-gray-500" colspan="100">No items in the list</td>
                                </tr>
        
                                <tr class="border border-gray-300">
                                    <td class="" colspan="100">
                                        <div class="w-100 flex justify-center px-4 py-2">
                                            <button type="button" class="select-none text-gray-700 text-sm font-medium" @click="addItem">+ Add item</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                            <!-- <tfoot>
                                <tr>
                                    <th scope="row" colspan="2" class="sm:pt-2 sm:text-right font-normal text-gray-700 border-t border-l border-gray-300">Subtotal</th>
                                    <td colspan="1" class="pt-2 text-right text-gray-900 tabular-nums border-t border-gray-300">{{ $filters.currency(subtotal) }}</td>
                                    <td class="border-r border-t border-gray-300"></td>
                                </tr>
                                <tr>
                                    <th scope="row" colspan="2" class="py-2 sm:text-right font-normal text-gray-700 border-b border-l border-gray-300">Tax (0%)</th>
                                    <td colspan="1" class="py-2 text-right tabular-nums text-gray-900 border-b border-gray-300">{{ $filters.currency(tax) }}</td>
                                    <td class="border-b border-r border-gray-300 "></td>
                                </tr>
                                <tr>
                                    <th scope="row" colspan="2" class="py-2 sm:text-right font-semibold text-gray-900 border-l border-b border-gray-300 bg-gray-100">Total</th>
                                    <td colspan="1" class="py-2 text-right font-semibold tabular-nums text-gray-900 border-b border-gray-300 bg-gray-100">{{ $filters.currency(total) }}</td>
                                    <td class="border-b border-r border-gray-300 bg-gray-100"></td>
                                </tr>
                            </tfoot> -->
                        </table>
                    </div>
                </div>

                <div>
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Initial deposit</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">
                        Add deposit amount and the payment instructions for client.
                    </p>

                    <div class="mt-10 grid md:grid-cols-2 gap-2">
                        <div>
                            <label class="form-label">Amount</label>
                            <input v-model.lazy="form.deposit_amount" v-money="money" class="form-input text-right" :class="{ error: form.errors.deposit_amount }" required/>
                            <div v-if="form.errors.deposit_amount" class="form-error">{{ form.errors.deposit_amount }}</div>
                        </div>

                        <div class="flex flex-col space-y-2">
                            <select-input v-model="form.bank_account_id"  @change="bankAccountSelected($event)" label="Pay to"  class="" required>
                                <option disabled>Select client account</option>
                                <option v-for="client_bank_account in client_bank_accounts" :value="client_bank_account.id">{{client_bank_account.label}}</option>
                            </select-input>

                            <div>
                                <div v-if="selected_bank_account === null">
                                    <div v-if="errors.loading_bank_account_details" class="flex justify-center items-center w-full bg-red-50 border-2 border-red-200 rounded-md px-6 py-4" style="height: 134px;">
                                        <span class="text-md text-red-500 tracking-tight">System encountered error.</span> 
                                    </div>
                                    <div v-else class="flex justify-center items-center w-full bg-gray-50 border-2 border-gray-200 rounded-md px-6 py-4" style="height: 134px;">
                                        <span class="text-md text-gray-500 tracking-tight">Bank Account Details</span> 
                                    </div>
                                </div>
                                <div v-else>         
                                    <div v-if="loading_bank_account_details" class="w-full bg-gray-200 border-2 border-gray-200 rounded-md px-6 py-4">
                                        <p class="mt-1 h-4 w-32 rounded-sm animated-background"></p>
                                        <p class="mt-2.5 h-4 w-full rounded-sm animated-background"></p>
                                        <p class="mt-2.5 h-4 w-full rounded-sm animated-background"></p>
                                        <p class="mt-2.5 h-4 w-full rounded-sm animated-background"></p>
                                    </div>
        
                                    <div v-else class="relative w-full bg-gray-50 border-2 border-gray-200 rounded-md px-6 py-4">
                                        <div class="absolute top-0 right-0 p-1">
                                            <div class="px-2 py-1 bg-blue-100 text-blue-500 text-xs rounded-md border border-blue-400">
                                                {{ selected_bank_account.account_type }}
                                            </div>
                                        </div>
                                        <p class="mt-1 h-4 w-full rounded-sm text-sm truncate text-gray-700 font-semibold">{{ selected_bank_account.label }}</p>
                                        <p class="mt-2.5 h-4 w-full rounded-sm truncate text-sm text-gray-600 font-medium"><span class="text-gray-400 select-none">Bank Name</span> {{ selected_bank_account.bank_name }}</p>
                                        <p class="mt-2.5 h-4 w-full rounded-sm truncate text-sm text-gray-600 font-medium"><span class="text-gray-400 select-none">Account Name</span> {{ selected_bank_account.account_name }}</p>
                                        <p class="mt-2.5 h-4 w-full rounded-sm truncate text-sm text-gray-600 font-medium"><span class="text-gray-400 select-none">Account Number</span> {{ selected_bank_account.account_number }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            
            <!-- Form Footer -->
            <div class="flex flex-row-reverse space-x-2 space-x-reverse items-center justify-start px-8 py-4 bg-gray-50 border-t border-gray-100">
                <loading-button :loading="form.processing" class="btn-primary" type="submit">Update Quotation</loading-button>
                <Link :href="`/lawyer/case-files/${case_file.id}/quotation`" as="button" class="btn-cancel" :disabled="form.processing">
                    Cancel
                </Link>
            </div>
        </form>
    </div>
</template>

<script>
import Layout from "../Shared/Layout";
import TextInput from '../../../Shared/TextInput';
import SelectInput from '../../../Shared/SelectInput';
import LoadingButton from '../../../Shared/LoadingButton';
import { VMoney } from "v-money";
import axios from "axios";
import moneyConfig from '../../../Stores/MoneyConfig';
import { unmaskMoneyToNumeric } from "../../../Stores/Utils";

export default { 
    components: { 
        TextInput,
        SelectInput,
        LoadingButton,
    },
    layout: Layout,
    props: {
        case_file : Object,
        quotation: Object,
        client_bank_accounts : Object,
    },
    data() {
        return {
            page_title: 'Edit Quotation',
            breadcrumbs: [
                { link: `/lawyer/dashboard`, label: 'Lawyer'},
                { link: `/lawyer/case-files/`, label: 'My Cases'},
                { link: `/lawyer/case-files/${this.case_file.id}`, label: this.case_file.file_number},
                { link: `/lawyer/case-files/${this.case_file.id}/quotation`, label: 'Quotation'},
                { link: null, label: 'Edit'},
            ],
            form: this.$inertia.form({
                work_descriptions : this.quotation.work_descriptions,
                deposit_amount: this.quotation.deposit_amount.amount,
                bank_account_id : this.quotation.bank_account_id,
            }),
            subtotal: 0,
            tax: 0,
            total: 0,
            selected_bank_account: null,
            loading_bank_account_details: false,
            errors: {
                loading_bank_account_details: false,
            },
            money: moneyConfig,
        }
    },
    directives: {money: VMoney},
    methods: {
        addItem() {
            this.form.work_descriptions.push({
                description : null,
                fee : null,
            });
        },
        removeItem(index) {
            this.form.work_descriptions.splice(index, 1);
        },
        getKey() {
            return Symbol();
        },
        update() {
            this.form.work_descriptions.forEach((work_description) => {
                work_description.fee = this.handleUnmasked(work_description.fee);
            });

            this.form.deposit_amount = this.handleUnmasked(this.form.deposit_amount);
            
            this.form.put(`/lawyer/case-files/${this.case_file.id}/quotation`);
        },
        handleUnmasked(amount) {

            return unmaskMoneyToNumeric(amount);
        },
        bankAccountSelected(event) {
            const id = event.target.value;

            this.loadBankAccountDetails(id);
        },
        loadBankAccountDetails(bank_account_id) {
            this.loading_bank_account_details = true;
            this.errors.loading_bank_account_details = false;
            this.selected_bank_account = null;

            axios.get(`/bank-accounts/${bank_account_id}`)
                .then(response => {
                    this.selected_bank_account = response.data;
                })
                .catch(error => {

                })
                .finally(() => {
                    this.loading_bank_account_details = false;
                })
        }
    },
    mounted() {
        const id = this.form.bank_account_id;

        if(id !== null)
            this.loadBankAccountDetails(id);
    },
};
</script>