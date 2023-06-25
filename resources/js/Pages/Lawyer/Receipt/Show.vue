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
                                <button @click="emailReceipt" class="btn-primary">Send Email</button>
                                <button @click="markSent" class="btn-secondary">Mark Sent</button>
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
                                <a :href="`/lawyer/case-files/${this.case_file.id}/invoices/${invoice.id}/receipt/pdf`" target="_blank" class="btn-primary">Download PDF</a>
                            </div>
                        </DisclosurePanel>
                    </transition>
                </Disclosure>
            </div>
        </div>

        <div class="lg:col-span-2 max-w-3xl relative bg-white rounded-md border border-gray-300 overflow-hidden">
            <!-- Status -->
            <div v-if="receipt.is_sent" class="absolute w-44 text-center select-none font-bold top-6 -right-12 py-2 px-12 rotate-45 z-30 text-green-500 bg-green-100">
                Sent
            </div>
            <div v-else class="absolute w-44 text-center select-none font-bold top-6 -right-12 py-2 px-12 rotate-45 z-30 text-blue-500 bg-blue-100">
                Pending
            </div>

            <div class="p-8 space-y-12">
                <!-- Receipt Details -->
                <div class="grid sm:grid-cols-2 gap-x-6 gap-y-2 w-full pb-6 border-b border-gray-300 text-sm">
                    <div class="flex space-x-4">
                        <div class="text-gray-500 w-28">
                            Receipt Number
                        </div> 
                        <div class="text-gray-700 font-semibold">
                            {{  receipt.number }}
                        </div>
                    </div>
                    <div class="flex space-x-4">
                        <div class="text-gray-500 w-28">
                            Ref. Invoice
                        </div> 
                        <div class="text-gray-700 font-medium">
                            {{  case_file.file_number }}
                        </div>
                    </div>
                    <div class="flex space-x-4">
                        <div class="text-gray-500 w-28">
                            Date
                        </div> 
                        <div class="text-gray-700 font-medium">
                            {{  receipt.date }}
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
    
                <div>
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
        
                    <div class="w-full mt-2 mb-6 border-t border-gray-400 pt-2 text-sm text-gray-800">
                        <span class="text-red-500">*</span>Paid through {{ invoice.payment.method }} on {{ invoice.payment.date }}
                    </div>
                </div>
    
                <div>
                    <div class="text-gray-900 font-medium mb-3">Notes</div>
                    <div class="text-gray-700 text-sm py-2 whitespace-pre-wrap">
                        <span v-if="receipt.notes">{{ receipt.notes }}</span>
                        <span v-else class="text-gray-400 italic">No notes provided.</span>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Layout from '../Shared/Layout';
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue'
import { ChevronUpIcon } from "@heroicons/vue/outline"; 

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
        items: Array,
        receipt: Object,
    },
    data() {
        return {
            page_title: 'Receipt',
            breadcrumbs: [
                { link: '/lawyer/dashboard', label: 'Lawyer'},
                { link: '/lawyer/case-files/', label: 'My Cases'},
                { link: `/lawyer/case-files/${this.case_file.id}`, label: this.case_file.file_number},
                { link: `/lawyer/case-files/${this.case_file.id}/invoices`, label: 'Invoices'},
                { link: `/lawyer/case-files/${this.case_file.id}/invoices/${this.invoice.id}`, label: this.invoice.number},
                { link: null, label: 'Receipt'},
            ],
        }
    },
    methods: {
        emailReceipt() {
            this.$inertia.post(`/lawyer/case-files/${this.case_file.id}/invoices/${this.invoice.id}/receipt/email-receipt`);
        },
        markSent() {
            this.$inertia.post(`/lawyer/case-files/${this.case_file.id}/invoices/${this.invoice.id}/receipt/mark-sent`);
        },
    },
};
</script>