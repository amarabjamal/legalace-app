<template>
    <TransitionRoot :show="isOpen" as="template" appear>
        <Dialog as="div" :open="isOpen" @close="closeModal" class="relative z-50">
            <TransitionChild
                as="template"
                enter="duration-300 ease-out"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="duration-200 ease-in"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-black/30" aria-hidden="true" />
            </TransitionChild>

            <TransitionChild
                as="template"
                enter="ease-out duration-300" 
                enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                enter-to="opacity-100 translate-y-0 sm:scale-100" 
                leave="ease-in duration-200" 
                leave-from="opacity-100 translate-y-0 sm:scale-100" 
                leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            >
                <form @submit.prevent="approveVoucher">
                    <div class="fixed inset-0 flex items-center justify-center p-4">
                        <div class="fixed inset-0 z-10 overflow-y-auto">
                            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                <DialogPanel class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl">
                                    <div class="p-6 mt-3 text-center sm:mt-0 sm:text-left">
                                        <DialogTitle as="h3" class="text-base font-semibold leading-6 text-gray-900 mb-6">Approval Confirmation</DialogTitle>
                            
                                        <div class="flex flex-col space-y-4">
                                            <div class="flex space-x-4">
                                                <div class="w-1/2">
                                                    <select-input @change="bankAccountSelected($event)" v-model="form.pay_from_bank_account_id" :error="form.errors.pay_from_bank_account_id"  label="Transfer from account" required>
                                                        <optgroup label="Client Account">
                                                            <option v-for="client_account in client_accounts" :value="client_account.id">{{ client_account.label }}</option>
                                                        </optgroup>
                                                        <optgroup label="Firm Account">
                                                            <option v-for="firm_account in firm_accounts" :value="firm_account.id">{{ firm_account.label }}</option>
                                                        </optgroup>
                                                    </select-input>
        
                                                </div>
                                                <div class="w-1/2">
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
        
                                            <textarea-input v-model="form.notes" :error="form.errors.notes" label="Notes" rows="4"></textarea-input>
                                        </div>
                                    </div>

                                    <div class="bg-gray-50 px-4 py-3 flex flex-row-reverse space-x-2 space-x-reverse">
                                        <loading-button :loading="form.processing" :disabled="!form.isDirty" class="btn-primary" type="submit">Confirm</loading-button>
                                        <button type="button" class="btn-cancel" @click="closeModal">Close</button>
                                    </div>
                                </DialogPanel>
                            </div>
                        </div>
                    </div>
                </form>
            </TransitionChild>
        </Dialog>
    </TransitionRoot>
</template>
  
<script>
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
} from '@headlessui/vue'
import SelectInput from '../../../../Shared/SelectInput';
import TextareaInput from '../../../../Shared/TextareaInput';
import LoadingButton from '../../../../Shared/LoadingButton'
import axios from 'axios';

export default {
    components: {
        TransitionRoot,
        TransitionChild,
        Dialog,
        DialogPanel,
        DialogTitle,
        SelectInput,
        TextareaInput,
        LoadingButton,
    },
    props: {
        isOpen: Boolean,
        claim_voucher: Object,
    },
    data() {
        return {
            form: this.$inertia.form({
                'claim_voucher_id': this.claim_voucher.id,
                'pay_from_bank_account_id': null,
                'notes': null,
            }),
            client_accounts: [],
            firm_accounts: [],
            selected_bank_account: null,
            loading_bank_account_details: false,
            errors: {
                loading_bank_account_details: false,
            },
        }
    },
    methods: {
        closeModal() {
            this.$emit('close-modal');
        },
        approveVoucher() {
            const proceed = confirm("Please ensure that all the details are correct before proceed. Are you sure to proceed?");

            if(proceed) {
                this.form.post(`/admin/voucher-requests/${this.claim_voucher.id}/approve`);
            }
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
    beforeMount() {
        axios.get('/bank-accounts')
            .then(response => {
                this.client_accounts = [
                    ...response.data.client_accounts,
                ];

                this.firm_accounts = [
                    ...response.data.firm_accounts,
                ]
            })
            .catch(error => {
                console.log(error);
            })
    }
}
</script>