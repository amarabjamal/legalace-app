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
                            <div class="text-sm text-gray-600 font-light">Last Sent <span class="font-medium font-gray-700"> </span></div>
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
                            <button v-else @click="addPayment" class="btn-primary">Add Payment</button>
                            <div class="mt-4">Payment received: 
                                <span v-if="invoice.status_value !== 4" class="text-base font-medium">No record.</span>
                                <div v-else>
                                    <div>
                                        Paid on {{ invoice.payment.date }} through {{ invoice.payment.method }}
                                    </div>
                                    <a  :href="`/lawyer/case-files/${case_file.id}/invoices/${invoice.id}/payment/receipt`" target="_blank"
                                        class="text-sm font-medium leading-6 text-blue-500 hover:underline"
                                    >
                                        Payment Proof
                                    </a>
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

            <div class="px-8 py-4 bg-gray-50 border-t border-gray-100">
                <div v-if="invoice.status_value === 1" class="flex flex-row-reverse space-x-2 space-x-reverse items-center justify-start ">
                    <form @submit.prevent="setInvoiceToOpen">
                        <button type="submit" class="btn-primary">
                            Change to Open
                        </button>
                    </form>
                    <Link :href="`/lawyer/case-files/${case_file.id}/invoices/${invoice.id}/edit`" as="button" class="btn-secondary">
                        Edit
                    </Link>
                </div>

                <div v-else-if="invoice.status_value === 2" class="flex flex-row-reverse space-x-2 space-x-reverse items-center justify-start ">
                    <form @submit.prevent="emailInvoice">
                        <button type="submit" class="btn-primary">
                            Send Email
                        </button>
                    </form>
                    <action-dropdown :status_code="invoice.status_value" :case_file_id="case_file.id" :invoice_id="invoice.id" />
                </div>
                <div v-else-if="invoice.status_value === 3" class="flex flex-row-reverse space-x-2 space-x-reverse items-center justify-start ">
                    <Link :href="`/lawyer/case-files/${case_file.id}/invoices/${invoice.id}/payment/create`" as="button" class="btn-primary">
                        Add Payment
                    </Link>
                    <action-dropdown :status_code="invoice.status_value" :case_file_id="case_file.id" :invoice_id="invoice.id" @send-email="emailInvoice" />
                </div>
                <div v-else-if="invoice.status_value === 4" class="flex flex-row-reverse space-x-2 space-x-reverse items-center justify-start ">
                    <Link v-if="invoice.has_receipt" :href="`/lawyer/case-files/${case_file.id}/invoices/${invoice.id}/receipt`" as="button" class="btn-primary">
                        Receipt
                    </Link>
                    <Link v-else :href="`/lawyer/case-files/${case_file.id}/invoices/${invoice.id}/receipt/create`" as="button" class="btn-primary">
                        Convert to Receipt
                    </Link>
                    <action-dropdown :status_code="invoice.status_value" :case_file_id="case_file.id" :invoice_id="invoice.id" />
                </div>
                
                <div v-else-if="invoice.status_value === 5" class="flex flex-row-reverse space-x-2 space-x-reverse items-center justify-start "></div>
                <div v-else-if="invoice.status_value === 6" class="flex flex-row-reverse space-x-2 space-x-reverse items-center justify-start "></div>
                <div v-else-if="invoice.status_value === 7" class="flex flex-row-reverse space-x-2 space-x-reverse items-center justify-start "></div>
            </div>
        </div>
        
        <!-- <div v-if="invoice.status_value === 4"  class="w-full max-w-3xl h-fit xl:max-w-xs bg-white border border-gray-300 rounded-md overflow-hidden">
            <dl class="flex flex-wrap">
                <div class="flex-auto pl-6 pt-6">
                    <dt class="text-sm font-semibold leading-6 text-gray-900">Amount</dt>
                    <dd class="mt-1 text-base font-semibold leading-6 text-gray-900">{{ invoice.payment.amount }}</dd>
                </div>
                <div class="mt-6 flex w-full flex-none gap-x-4 border-t border-gray-900/[.05] px-6 pt-6">
                    <dt class="flex-none">
                        <span class="sr-only">Payment date</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="h-6 w-5 text-gray-400"><path d="M5.25 12a.75.75 0 01.75-.75h.01a.75.75 0 01.75.75v.01a.75.75 0 01-.75.75H6a.75.75 0 01-.75-.75V12zM6 13.25a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 00.75-.75V14a.75.75 0 00-.75-.75H6zM7.25 12a.75.75 0 01.75-.75h.01a.75.75 0 01.75.75v.01a.75.75 0 01-.75.75H8a.75.75 0 01-.75-.75V12zM8 13.25a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 00.75-.75V14a.75.75 0 00-.75-.75H8zM9.25 10a.75.75 0 01.75-.75h.01a.75.75 0 01.75.75v.01a.75.75 0 01-.75.75H10a.75.75 0 01-.75-.75V10zM10 11.25a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 00.75-.75V12a.75.75 0 00-.75-.75H10zM9.25 14a.75.75 0 01.75-.75h.01a.75.75 0 01.75.75v.01a.75.75 0 01-.75.75H10a.75.75 0 01-.75-.75V14zM12 9.25a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 00.75-.75V10a.75.75 0 00-.75-.75H12zM11.25 12a.75.75 0 01.75-.75h.01a.75.75 0 01.75.75v.01a.75.75 0 01-.75.75H12a.75.75 0 01-.75-.75V12zM12 13.25a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 00.75-.75V14a.75.75 0 00-.75-.75H12zM13.25 10a.75.75 0 01.75-.75h.01a.75.75 0 01.75.75v.01a.75.75 0 01-.75.75H14a.75.75 0 01-.75-.75V10zM14 11.25a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 00.75-.75V12a.75.75 0 00-.75-.75H14z"></path><path fill-rule="evenodd" d="M5.75 2a.75.75 0 01.75.75V4h7V2.75a.75.75 0 011.5 0V4h.25A2.75 2.75 0 0118 6.75v8.5A2.75 2.75 0 0115.25 18H4.75A2.75 2.75 0 012 15.25v-8.5A2.75 2.75 0 014.75 4H5V2.75A.75.75 0 015.75 2zm-1 5.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h10.5c.69 0 1.25-.56 1.25-1.25v-6.5c0-.69-.56-1.25-1.25-1.25H4.75z" clip-rule="evenodd"></path></svg>
                    </dt>
                    <dd class="text-sm leading-6 text-gray-500">
                        Paid on <time class="font-medium text-gray-900">{{ invoice.payment.date }}</time>
                    </dd>
                </div>
                <div class="mt-4 flex w-full flex-none gap-x-4 px-6">
                    <dt class="flex-none">
                        <span class="sr-only">Payment method</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="h-6 w-5 text-gray-400"><path fill-rule="evenodd" d="M2.5 4A1.5 1.5 0 001 5.5V6h18v-.5A1.5 1.5 0 0017.5 4h-15zM19 8.5H1v6A1.5 1.5 0 002.5 16h15a1.5 1.5 0 001.5-1.5v-6zM3 13.25a.75.75 0 01.75-.75h1.5a.75.75 0 010 1.5h-1.5a.75.75 0 01-.75-.75zm4.75-.75a.75.75 0 000 1.5h3.5a.75.75 0 000-1.5h-3.5z" clip-rule="evenodd"></path></svg>
                    </dt>
                    <dd class="text-sm leading-6 text-gray-500">Through <span class="font-medium text-gray-900">{{ invoice.payment.method }}</span></dd>
                </div>
                <div class="mt-4 flex w-full flex-none gap-x-4 px-6">
                    <dt class="flex-none">
                        <span class="sr-only">Recorded by</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="h-6 w-5 text-gray-400"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-5.5-2.5a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0zM10 12a5.99 5.99 0 00-4.793 2.39A6.483 6.483 0 0010 16.5a6.483 6.483 0 004.793-2.11A5.99 5.99 0 0010 12z" clip-rule="evenodd"></path></svg>
                    </dt>
                    <dd class="text-sm leading-6 text-gray-500">Recorded by <span class="font-medium text-gray-900">{{ invoice.payment.created_by.name }}</span></dd>
                </div>
            </dl>

            <div class="mt-6 border-t border-gray-900/[.05] p-6">
                <a 
                    :href="`/lawyer/case-files/${case_file.id}/invoices/${invoice.id}/payment/receipt`" 
                    target="_blank"
                    class="text-sm font-semibold leading-6 text-gray-900"
                >
                    Download receipt <span aria-hidden="true">→</span>
                </a>
            </div>
        </div> -->
    </div>
</template>

<script>
import Layout from '../Shared/Layout';
import ActionDropdown from './Components/ActionDropdown';
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue'
import { ChevronUpIcon } from "@heroicons/vue/outline"; 
import { cva } from 'class-variance-authority';

export default { 
    components: { 
        ActionDropdown,
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
            if(confirm('The invoice will be send to the client registered email address. Are you sure to proceed?')) {
                this.$inertia.post(`/lawyer/case-files/${this.case_file.id}/invoices/${this.invoice.id}/email-invoice`);
            }
        },
        statusClass(status) {
            return cva("absolute w-44 text-center select-none font-bold top-6 -right-12 py-2 px-12 rotate-45 z-30", {
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