<template>
    <Head :title="page_title" />

    <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs"/>

    <div class="w-full grid lg:grid-cols-3 gap-4">
        <div class="lg:col-start-3 lg:row-end-1 w-full max-w-3xl lg:max-w-md p-8 h-fit bg-white rounded-md border border-gray-300 overflow-hidden">
            <div class="mx-auto w-full">
                <Disclosure v-slot="{ open }">
                    <DisclosureButton class="flex w-full justify-between rounded-sm bg-gray-100 px-4 py-2 text-left text-lg font-medium text-gray-900 hover:bg-gray-200 focus:outline-none focus-visible:ring focus-visible:ring-gray-500 focus-visible:ring-opacity-75">
                        <div>
                            <span>Send</span>
                            <div class="text-sm text-gray-600 font-light">Last Sent <span class="font-medium font-gray-700">{{ quotation.sent_at }}</span></div>
                        </div>
                        <ChevronUpIcon :class="open ? 'rotate-180 transform' : ''" class="h-5 w-5 text-gray-500"/>
                    </DisclosureButton>

                    <transition
                        enter-active-class="transition duration-100 ease-out"
                        enter-from-class="transform scale-95 opacity-0"
                        enter-to-class="transform scale-100 opacity-100"
                        leave-active-class="transition duration-75 ease-out"
                        leave-from-class="transform scale-100 opacity-100"
                        leave-to-class="transform scale-95 opacity-0"
                    >
                        <DisclosurePanel class="px-4 pt-4 pb-2 text-sm text-gray-500">
                            <div class="space-x-2">
                                <button @click="emailQuotation" class="btn-primary">Send Email</button>
                                <button @click="markSent" class="btn-secondary">Mark Sent</button>
                            </div>
                        </DisclosurePanel>
                    </transition>
                </Disclosure>
                <Disclosure as="div" class="mt-2" v-slot="{ open }">
                    <DisclosureButton class="flex w-full justify-between rounded-sm bg-gray-100 px-4 py-2 text-left text-lg font-medium text-gray-900 hover:bg-gray-200 focus:outline-none focus-visible:ring focus-visible:ring-gray-500 focus-visible:ring-opacity-75">
                        <div>
                            <span>Get Paid</span>
                            <div class="text-sm text-gray-600 font-light">Amount Due 
                                <span v-if="quotation.is_paid" class="font-medium font-gray-700 tabular-nums">
                                    RM 0.00
                                </span>
                                <span v-else class="font-medium font-gray-700 tabular-nums">
                                    {{  quotation.total }}
                                </span>
                            </div>
                        </div>
                        <ChevronUpIcon :class="open ? 'rotate-180 transform' : ''" class="h-5 w-5 text-gray-500"/>
                    </DisclosureButton>
                    
                    <transition
                        enter-active-class="transition duration-100 ease-out"
                        enter-from-class="transform scale-95 opacity-0"
                        enter-to-class="transform scale-100 opacity-100"
                        leave-active-class="transition duration-75 ease-out"
                        leave-from-class="transform scale-100 opacity-100"
                        leave-to-class="transform scale-95 opacity-0"
                    >
                        <DisclosurePanel class="px-4 pt-4 pb-2 text-sm text-gray-500">
                            <button v-if="quotation.is_paid" disabled class="btn-primary">Add Payment</button>
                            <button v-else @click="addPayment" class="btn-primary">Add Payment</button>
                            <div class="mt-4">Payment received: 
                                <span v-if="!quotation.is_paid" class="text-base font-medium">No record.</span>
                                <div v-else>Paid on {{  quotation.payment.date }} through {{ quotation.payment.method }}</div>
                            </div>
                        </DisclosurePanel>
                    </transition>
                </Disclosure>
                <Disclosure as="div" class="mt-2" v-slot="{ open }">
                    <DisclosureButton class="flex w-full justify-between rounded-sm bg-gray-100 px-4 py-2 text-left text-lg font-medium text-gray-900 hover:bg-gray-200 focus:outline-none focus-visible:ring focus-visible:ring-gray-500 focus-visible:ring-opacity-75">
                        <span>Share</span>
                        <ChevronUpIcon :class="open ? 'rotate-180 transform' : ''" class="h-5 w-5 text-gray-500"/>
                    </DisclosureButton>
                    
                    <transition
                        enter-active-class="transition duration-100 ease-out"
                        enter-from-class="transform scale-95 opacity-0"
                        enter-to-class="transform scale-100 opacity-100"
                        leave-active-class="transition duration-75 ease-out"
                        leave-from-class="transform scale-100 opacity-100"
                        leave-to-class="transform scale-95 opacity-0"
                    >
                        <DisclosurePanel class="px-4 pt-4 pb-2 text-sm text-gray-500">
                            <div>
                                <a :href="`/lawyer/case-files/${this.case_file.id}/quotation/pdf`" target="_blank" class="btn-primary">Download PDF</a>
                            </div>
                        </DisclosurePanel>
                    </transition>
                </Disclosure>
            </div>
        </div>

        <div class="lg:col-span-2 max-w-3xl relative bg-white rounded-md border border-gray-300 overflow-hidden">
            <div v-if="quotation.is_paid" class="absolute w-44 text-center text-green-500 select-none font-bold top-6 -right-12 bg-green-100 py-2 px-12 rotate-45">
                Paid
            </div>
            <div v-else class="absolute w-44 text-center text-blue-500 select-none font-bold top-6 -right-12 bg-blue-100 py-2 px-12 rotate-45">
                Draft
            </div>
            <div class="p-8 space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Scope of services & legal fees</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">
                        The total amount of the items below is the intial legal fees for this case file.
                    </p>
        
                    <!-- Work Description Table -->
                    <div class="mt-10 w-full overflow-x-auto overflow-y-hidden">
                        <table class="w-full whitespace-nowrap text-left text-sm leading-6">
                            <thead class="border border-gray-300 text-gray-900 bg-gray-100 select-none">
                                <tr>
                                    <th class="w-12 px-4 py-2">No.</th>
                                    <th>Work Description</th>
                                    <th class="w-40 px-4 text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(work_description, index) in quotation.work_descriptions" class="border border-gray-300">
                                    <td class="max-w-0 px-4 py-2 align-top">
                                        {{ index + 1 }}
                                    </td>
                                    <td class="max-w-0 pr-4 py-2 align-top whitespace-normal">
                                        {{ work_description.description }}
                                    </td>
                                    <td class="pr-4 pl-0 py-2 align-top text-right tabular-nums">
                                        {{  work_description.fee }}
                                    </td>
                                </tr>
                                <tr v-if="quotation.work_descriptions.length === 0" class="border-x border-gray-300 bg-gray-100">
                                    <td  class="text-center py-2 text-gray-500" colspan="100">No items in the list</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th scope="row" colspan="2" class="sm:pt-2 sm:text-right font-normal text-gray-700 border-t border-l border-gray-300">Subtotal</th>
                                    <td colspan="1" class="pt-2 px-4 text-right text-gray-900 tabular-nums border-t border-r border-gray-300">{{ quotation.subtotal }}</td>
                                </tr>
                                <tr>
                                    <th scope="row" colspan="2" class="py-2 sm:text-right font-normal text-gray-700 border-b border-l border-gray-300">Tax (0%)</th>
                                    <td colspan="1" class="py-2 px-4 text-right tabular-nums text-gray-900 border-b border-r border-gray-300">{{ quotation.tax }}</td>
                                </tr>
                                <tr>
                                    <th scope="row" colspan="2" class="py-2 sm:text-right font-semibold text-gray-900 border-l border-b border-gray-300 bg-gray-100">Total</th>
                                    <td colspan="1" class="py-2 px-4 text-right font-semibold tabular-nums text-gray-900 border-b border-r border-gray-300 bg-gray-100">{{  quotation.total }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div>
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Initial deposit</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">
                        The deposit amount and the payment instructions for the client.
                    </p>

                    <div class="mt-10 flex space-x-2">
                        <div class="w-1/2">
                            <label class="form-label">Amount</label>
                            {{ quotation.deposit_amount }}
                        </div>

                        <div class="w-1/2">
                            <div class="form-label">Pay To</div>
                            <div class="relative w-full bg-gray-50 border-2 border-gray-200 rounded-md px-6 py-4">
                                <div class="absolute top-0 right-0 p-1">
                                    <div class="px-2 py-1 bg-blue-100 text-blue-500 text-xs rounded-md border border-blue-400">
                                        {{ quotation.bank_account.type }}
                                    </div>
                                </div>
                                <p class="mt-1 h-4 w-full rounded-sm text-sm truncate text-gray-700 font-semibold">{{ quotation.bank_account.label }}</p>
                                <p class="mt-2.5 h-4 w-full rounded-sm truncate text-sm text-gray-600 font-medium"><span class="text-gray-400 select-none">Bank Name</span> {{ quotation.bank_account.bank_name }}</p>
                                <p class="mt-2.5 h-4 w-full rounded-sm truncate text-sm text-gray-600 font-medium"><span class="text-gray-400 select-none">Account Name</span> {{ quotation.bank_account.account_name }}</p>
                                <p class="mt-2.5 h-4 w-full rounded-sm truncate text-sm text-gray-600 font-medium"><span class="text-gray-400 select-none">Account Number</span> {{ quotation.bank_account.account_number }}</p>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <div :class="{'hidden': quotation.is_paid}" class="px-8 py-4 bg-gray-50 border-t border-gray-100">
                <div class="flex flex-row-reverse space-x-2 space-x-reverse items-center justify-start ">
                    <button v-if="quotation.is_paid" disabled class="btn-primary">
                        Edit
                    </button> 
                    <Link v-else as="button" :href="`/lawyer/case-files/${case_file.id}/quotation/edit`" class="btn-primary">
                        Edit
                    </Link> 
                </div>
            </div>
        </div>
    </div>

    <add-payment-modal v-if="!quotation.is_paid" :isOpen="show_add_payment_modal" :caseFileId="case_file.id" :modalProps="modal_props" @close-modal="show_add_payment_modal = false" />
</template>

<script>
import Layout from "../Shared/Layout";
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue'
import { ChevronUpIcon } from "@heroicons/vue/outline"; 
import AddPaymentModal from './Components/AddPaymentModal';

export default { 
    components: { 
        Disclosure,
        DisclosureButton,
        DisclosurePanel,
        ChevronUpIcon,
        AddPaymentModal,
    },
    layout: Layout,
    props: {
        case_file : Object,
        quotation: Object,
        modal_props: Object,
    },
    data() {
        return {
            page_title: 'Quotation',
            breadcrumbs: [
                { link: `/lawyer/dashboard`, label: 'Lawyer'},
                { link: `/lawyer/case-files/`, label: 'My Cases'},
                { link: `/lawyer/case-files/${this.case_file.id}`, label: this.case_file.file_number},
                { link: null, label: 'Quotation'},
            ],
            show_add_payment_modal: false,
        }
    },
    methods: {
        emailQuotation() {
            this.$inertia.post(`/lawyer/case-files/${this.case_file.id}/quotation/email-quotation`);
        },
        markSent() {
            this.$inertia.post(`/lawyer/case-files/${this.case_file.id}/quotation/mark-sent`);
        },
        addPayment() {
            this.show_add_payment_modal = true;
        },
    },
};
</script>