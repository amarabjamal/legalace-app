<template>
    <Head :title="page_title" />

    <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs"/>
    
    <div class="w-full grid lg:grid-cols-3 gap-4 lg:grid-flow-row-dense">
        <div class="lg:col-start-3 lg:row-end-1 w-full max-w-3xl lg:max-w-md p-8 h-fit bg-white rounded-md border border-gray-300 overflow-hidden">
            <div class="mx-auto w-full">
                <Disclosure v-slot="{ open }">
                    <DisclosureButton class="flex w-full justify-between rounded-sm bg-gray-100 px-4 py-2 text-left text-lg font-medium text-gray-900 hover:bg-gray-200 focus:outline-none focus-visible:ring focus-visible:ring-gray-500 focus-visible:ring-opacity-75">
                        <div>
                            <span>Send</span>
                            <div class="text-sm text-gray-600 font-light">Last Sent <span class="font-medium font-gray-700">{{ invoice.sent_at }}</span></div>
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
                                <button @click="emailInvoice" class="btn-primary">Send Email</button>
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
                                <span v-if="invoice.status_value === 4" class="font-medium font-gray-700 tabular-nums">
                                    RM 0.00
                                </span>
                                <span v-else class="font-medium font-gray-700 tabular-nums">
                                    {{ invoice.total  }}
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
                            <button v-if="invoice.status_value === 4" disabled class="btn-primary">Add Payment</button>
                            <Link v-else :href="`/lawyer/case-files/${case_file.id}/invoices/${invoice.id}/payment/create`" as="button" class="btn-primary">
                                Add Payment
                            </Link>
                            <div class="mt-4">Payment received: 
                                <span v-if="invoice.status_value !== 4" class="text-base font-medium">No record.</span>
                                <div v-else>
                                    <div>
                                        Paid on {{ invoice.payment.date }} through {{ invoice.payment.method }}
                                    </div>
                                    <div class="space-x-2">
                                        <a  :href="`/lawyer/case-files/${case_file.id}/invoices/${invoice.id}/payment/receipt`" target="_blank"
                                            class="text-sm font-medium leading-6 text-blue-500 hover:underline"
                                        >
                                            Payment Proof
                                        </a>
                                        <Link v-if="invoice.has_receipt" :href="`/lawyer/case-files/${case_file.id}/invoices/${invoice.id}/receipt`" as="button" 
                                            class="text-sm font-medium leading-6 text-blue-500 hover:underline"
                                        >
                                            Send Receipt
                                        </Link>
                                        <Link v-else :href="`/lawyer/case-files/${case_file.id}/invoices/${invoice.id}/receipt/create`" as="button" 
                                            class="text-sm font-medium leading-6 text-blue-500 hover:underline"
                                        >
                                            Convert to Receipt
                                        </Link>
                                    </div>
                                </div>
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
                                <a :href="`/lawyer/case-files/${this.case_file.id}/invoices/${invoice.id}/pdf`" target="_blank" class="btn-primary">Download PDF</a>
                            </div>
                        </DisclosurePanel>
                    </transition>
                </Disclosure>
            </div>
        </div>

        <div class="lg:col-span-2 max-w-3xl relative bg-white rounded-md border border-gray-300 overflow-hidden">
            <!-- Status -->
            <div :class="statusClass(invoice.status_value)">
                {{ invoice.status_label }}
            </div>

            <div class="p-8 space-y-12">
                <!-- Invoice Details -->
                <div class="grid sm:grid-cols-2 gap-x-6 gap-y-2 w-full pb-6 border-b border-gray-300 text-sm">
                    <div class="flex space-x-4">
                        <div class="text-gray-500 w-28">
                            Invoice Number
                        </div> 
                        <div class="text-gray-900 font-semibold">
                            {{  invoice.number }}
                        </div>
                    </div>
                    <div class="flex space-x-4">
                        <div class="text-gray-500 w-28">
                            File Number
                        </div> 
                        <div class="text-gray-700 font-medium">
                            {{  case_file.file_number }}
                        </div>
                    </div>
                    <div class="flex space-x-4">
                        <div class="text-gray-500 w-28">
                            Issued on
                        </div> 
                        <div class="text-gray-700 font-medium">
                            {{  invoice.invoice_date }}
                        </div>
                    </div>
                    <div class="flex space-x-4">
                        <div class="text-gray-500 w-28">
                            Due on
                        </div> 
                        <div class="text-gray-700 font-medium">
                            {{  invoice.due_date }}
                        </div>
                    </div>
                </div>

                <!-- Addresses -->
                <div class="grid sm:grid-cols-2 gap-x-6 w-full text-sm">
                    <!-- Company Address -->
                    <div class="flex flex-col flex-wrap">
                        <div class="font-semibold text-gray-900">
                            From
                        </div>
                        <div class="flex flex-col mt-3 text-gray-500">
                            <div class="font-medium text-gray-900 mb-2">
                                {{ invoice.company.name }}
                            </div>
                            <div class="whitespace-pre-wrap">{{ invoice.company.address }}</div>
                        </div>  
                    </div>

                    <!-- Client Address -->
                    <div class="flex flex-col flex-wrap">
                        <div class="font-semibold text-gray-900">
                            To
                        </div>
                        <div class="flex flex-col mt-3 text-gray-500">
                            <div class="font-medium text-gray-900 mb-2">
                                {{ invoice.client.name }}
                            </div>
                            <div class="whitespace-pre-wrap">{{ invoice.client.address }}</div>
                        </div>  
                    </div>
                </div>
                
                <table class="w-full whitespace-nowrap text-left text-sm leading-6">
                    <thead class="border-b border-gray-200 text-gray-900">
                        <tr>
                            <th class="w-12">No.</th>
                            <th>Item</th>
                            <th class="w-40 text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in items" :key="index" class="border-b border-gray-100">
                            <td class="max-w-0 px-0 py-5 align-top">{{ index + 1 }}</td>
                            <td class="max-w-0 px-0 py-5 align-top">
                                <div class="truncate font-medium text-gray-900/100">
                                    {{ item.name }}
                                </div>
                                <div class="truncate text-gray-500/100">
                                    {{ item.description }}
                                </div>
                            </td>
                            <td class="pr-0 pl-8 py-5 align-top text-right tabular-nums">{{ item.amount }}</td>
                        </tr>
                        <tr v-if="items.length === 0" class="border-b border-gray-100/100 bg-gray-100">
                            <td class="text-center py-4 text-gray-500" colspan="100">No items in the list</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th scope="row" colspan="2" class="px-0 pb-0 sm:pt-6 sm:text-right font-normal text-gray-700">Subtotal</th>
                            <td colspan="1" class="pb-0 pl-8 pr-0 pt-6 text-right text-gray-900 tabular-nums">{{ invoice.subtotal }}</td>
                        </tr>
                        <tr>
                            <th scope="row" colspan="2" class="pt-4 sm:text-right font-normal text-gray-700">Tax (0%)</th>
                            <td colspan="1" class="pb-0 pl-8 pr-0 pt-4 text-right tabular-nums text-gray-900">{{ invoice.tax }}</td>
                        </tr>
                        <tr>
                            <th scope="row" colspan="2" class="pt-4 sm:text-right font-semibold text-gray-900">Total</th>
                            <td colspan="1" class="pb-0 pl-8 pr-0 pt-4 text-right font-semibold tabular-nums text-gray-900">{{ invoice.total }}</td>
                        </tr>
                    </tfoot>
                </table>

                <div>
                    <div class="text-gray-900 font-medium mb-3">Notes</div>
                    <div class="text-gray-700 text-sm py-2 whitespace-pre-wrap">
                        <span v-if="invoice.notes">{{ invoice.notes }}</span>
                        <span v-else class="text-gray-400 italic">No notes provided.</span>
                        
                    </div>
                </div>
            </div>

            <div v-if="invoice.status_value >= 1 && invoice.status_value <= 3" class="px-8 py-4 bg-gray-50 border-t border-gray-100">
                <div class="flex flex-row-reverse space-x-2 space-x-reverse items-center justify-start ">
                    <!-- <form @submit.prevent="setInvoiceToOpen">
                        <button type="submit" class="btn-primary">
                            Change to Open
                        </button>
                    </form> -->
                    <Link :href="`/lawyer/case-files/${case_file.id}/invoices/${invoice.id}/edit`" as="button" class="btn-primary">
                        Edit
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Layout from '../Shared/Layout';
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue'
import { ChevronUpIcon } from "@heroicons/vue/outline"; 
import { cva } from 'class-variance-authority';

export default { 
    components: { 
        Disclosure,
        DisclosureButton,
        DisclosurePanel,
        ChevronUpIcon,
    },
    layout: Layout,
    props: {
        case_file: Object,
        invoice: Object,
        items: Object,
    },
    data() {
        return {
            page_title: 'Invoice',
            breadcrumbs: [
                { link: '/lawyer/dashboard', label: 'Lawyer'},
                { link: '/lawyer/case-files/', label: 'My Cases'},
                { link: `/lawyer/case-files/${this.case_file.id}`, label: this.case_file.file_number},
                { link: `/lawyer/case-files/${this.case_file.id}/invoices`, label: 'Invoices'},
                { link: null, label: this.invoice.number},
            ],
        }
    },
    methods: {
        setInvoiceToOpen() {
            if(confirm('The invoice will be set to open and you no longer can edit the invoice. Are you sure to proceed?')) {
                this.$inertia.post(`/lawyer/case-files/${this.case_file.id}/invoices/${this.invoice.id}/set-open`);
            }
        },
        emailInvoice() {
            const proceed = confirm("You're about to send the invoice to client's email. Are you sure to continue?");
            
            if(proceed) {
                this.$inertia.post(`/lawyer/case-files/${this.case_file.id}/invoices/${this.invoice.id}/email-invoice`);
            }
        },
        markSent() {
            const proceed = confirm("You're marking this invoice as sent. Are you sure to continue?");

            if(proceed) {
                this.$inertia.post(`/lawyer/case-files/${this.case_file.id}/invoices/${this.invoice.id}/mark-sent`);
            }
        },
        statusClass(status) {
            return cva("absolute w-44 text-center select-none font-bold top-6 -right-12 py-2 px-12 rotate-45", {
                variants: {
                    status: {
                        1: "text-blue-500 bg-blue-100", //Draft
                        2: "text-cyan-500 bg-cyan-100", //Open
                        3: "text-teal-500 bg-teal-100", //Sent
                        4: "text-green-500 bg-green-100", //Paid
                        5: "text-red-500 bg-red-100", //Overdue
                        6: "text-gray-500 bg-gray-100", //Void
                        7: "text-rose-500 bg-rose-100", //Uncollactable
                    }
                }
            }) ({
                status: status,
            })
        }
    },
};
</script>