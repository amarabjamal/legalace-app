<template>
    <Head :title="page_title" />

    <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs"/>

    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
        <form @submit.prevent="store">
            <div class="flex flex-wrap px-8 py-12">
                <h3>SCOPE OF SERVICES & LEGAL FEES</h3>

                <!-- Work Description Table -->
                <table class="w-full my-4 whitespace-nowrap text-left text-sm leading-6">
                    <thead class="border-b border-gray-200/100 text-gray-900 select-none">
                        <tr>
                            <th class="w-12">No.</th>
                            <th>Work Description</th>
                            <th class="w-40 text-right">Amount</th>
                            <th class="w-16"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <transition-group
                            enter-from-class="opacity-0 scale-150 overflow-hidden"
                            enter-active-class="duration-300"
                            leave-active-class="duration-300"
                            leave-to-class="translate-x-full scale-0  opacity-0"
                            appear 
                        >
                            <tr v-for="(work_description, index) in form.work_descriptions" :key="work_description.key" class="bg-gray-50 border border-gray-200 rounded-md py-4">
                                <td class="max-w-0 px-4 py-2 align-top">
                                    {{ index + 1 }}
                                </td>
                                <td class="max-w-0 pr-4 py-2 align-top">
                                    <text-input v-model="work_description.description" :error="form.errors[`work_descriptions.${index}.description`]" required/>
                                </td>
                                <td class="pr-0 pl-0 py-2 align-top text-right tabular-nums">
                                    <div>
                                        <input v-model.lazy="work_description.fee" v-money="moneyConfig" class="form-input text-right" :class="{ error: form.errors[`work_descriptions.${index}.fee`] }" required/>
                                        <div v-if="form.errors[`work_descriptions.${index}.fee`]" class="form-error">{{ fform.errors[`work_descriptions.${index}.fee`] }}</div>
                                    </div>
                                </td>
                                <td class="max-w-0 px-0 py-2 align-top">
                                    <div class="flex justify-center items-center mt-2.5">
                                        <button type="button" @click="removeItem(index)"><icon name="close-circle-fill" class="block w-5 h-5 fill-gray-400" /></button>    
                                    </div>
                                </td>
                            </tr>
                        </transition-group>

                        <tr v-if="form.work_descriptions.length === 0" key="empty_list" class="border-b border-gray-100 bg-gray-100">
                            <td class="text-center py-2 text-gray-500" colspan="100">No items in the list</td>
                        </tr>

                        <tr class="border border-gray-200 bg-gray-200">
                            <td class="" colspan="100">
                                <div class="w-100 flex justify-center py-2">
                                    <button type="button" class="select-none" @click="addItem">+ Add New Row</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th scope="row" colspan="2" class="px-0 pb-0 sm:pt-6 sm:text-right font-normal text-gray-700">Subtotal</th>
                            <td colspan="1" class="pb-0 pl-8 pr-0 pt-6 text-right text-gray-900 tabular-nums">{{ $filters.currency(subtotal) }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <th scope="row" colspan="2" class="pt-4 sm:text-right font-normal text-gray-700">Tax (0%)</th>
                            <td colspan="1" class="pb-0 pl-8 pr-0 pt-4 text-right tabular-nums text-gray-900">{{ $filters.currency(tax) }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <th scope="row" colspan="2" class="pt-4 sm:text-right font-semibold text-gray-900">Total</th>
                            <td colspan="1" class="pb-0 pl-8 pr-0 pt-4 text-right font-semibold tabular-nums text-gray-900">{{ $filters.currency(total) }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>

                <div class="w-full">
                    <h3 class="mb-4">INITIAL DEPOSIT</h3>
    
                    <div class="flex space-x-2">
                        <div class="flex flex-col space-y-2 w-1/2">
                            <select-input v-model="form.bank_account_id"  @change="bankAccountSelected($event)" label="Pay to"  class="" required>
                                <option disabled>Select client account</option>
                                <option v-for="client_bank_account in client_bank_accounts" :value="client_bank_account.id">{{client_bank_account.label}}</option>
                            </select-input>

                            <div class="">
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

                        <div class="w-1/2">
                            <label class="form-label">Amount</label>
                            <input v-model.lazy="form.deposit_amount" v-money="moneyConfig" class="form-input text-right" :class="{ error: form.errors.deposit_amount }" required/>
                            <div v-if="form.errors.deposit_amount" class="form-error">{{ form.errors.deposit_amount }}</div>
                        </div>
                    </div>

                </div>

            </div>
            
            <!-- Form Footer -->
            <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
                <Link 
                    :href="`/lawyer/case-files/${case_file.id}`"
                    as="button"  
                    class="mr-2 text-gray-500 focus:outline-none hover:text-blue-700 hover:underline focus:z-10 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center"
                    :disabled="form.processing"
                    >
                    Cancel
                </Link>
                <loading-button :loading="form.processing" class="btn-primary" type="submit">Create Quotation</loading-button>
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
import { TrashIcon } from "@heroicons/vue/outline";
import axios from "axios";

export default { 
    components: { 
        TrashIcon,
        TextInput,
        SelectInput,
        LoadingButton,
    },
    layout: Layout,
    props: {
        case_file : Object,
        client_bank_accounts : Object,
    },
    data() {
        return {
            page_title: 'Create Quotation',
            breadcrumbs: [
                { link: `/lawyer/dashboard`, label: 'Lawyer'},
                { link: `/lawyer/case-files/`, label: 'My Cases'},
                { link: `/lawyer/case-files/${this.case_file.id}`, label: this.case_file.file_number},
                { link: `/lawyer/case-files/${this.case_file.id}/quotation`, label: 'Quotation'},
                { link: null, label: 'Create'},
            ],
            form: this.$inertia.form({
                case_file_id : this.case_file.id,
                work_descriptions : [],
                deposit_amount: null,
                bank_account_id : null,
            }),
            subtotal: 0,
            tax: 0,
            total: 0,
            selected_bank_account: null,
            loading_bank_account_details: false,
            errors: {
                loading_bank_account_details: false,
            },
            moneyConfig: {
                decimal: '.',
                thousands: ',',
                prefix: 'RM ',
                suffix: '',
                precision: 2,
                masked: false
            },
        }
    },
    directives: {money: VMoney},
    methods: {
        addItem() {
            this.form.work_descriptions.push({
                key: Symbol(),
                description : null,
                fee : null,
            });
        },
        removeItem(index) {
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
        store() {
            this.form.work_descriptions.forEach((work_description) => {
                work_description.fee = this.handleUnmasked(work_description.fee);
            });

            this.form.deposit_amount = this.handleUnmasked(this.form.deposit_amount);

            console.log(this.form);

            this.form.post(`/lawyer/case-files/${this.case_file.id}/quotation`);
        },
        handleUnmasked(amount) {
            // Parse the unmasked value to a numerical value
            const parsedAmount = parseFloat(amount.replace(/[^0-9.-]+/g, ''));
            
            if(isNaN(parsedAmount)) {
                return 0;
            }

            return parsedAmount;
        },
        bankAccountSelected(event) {
            const bank_account_id = event.target.value;

            this.loading_bank_account_details = true;
            this.errors.loading_bank_account_details = false;
            this.selected_bank_account = null;

            axios.get(`/bank-accounts/${bank_account_id}`)
                .then(response => {
                    this.selected_bank_account = response.data;
                })
                .catch(error => {
                    this.errors.loading_bank_account_details = true;
                    console.log(error);
                })
                .finally(() => {
                    this.loading_bank_account_details = false;
                })
        }
    },
};
</script>