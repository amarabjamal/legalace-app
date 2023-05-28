<template>
    <Head title="View Invoice" />

    <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs"/>
    
    <div class="flex flex-col xl:flex-row">
        <div class="max-w-3xl bg-white rounded-md shadow-xl overflow-hidden">
            <div class="flex flex-wrap px-8 py-12">
                <div class="flex justify-between items-center w-full mb-4">
                    <div class="flex flex-wrap items-center space-x-2">
                        <h2 class="text-base font-semibold text-gray-900"><span class="text-gray-500 font-medium">Invoice</span> {{ invoice.number }}</h2>
                        <div v-if="invoice.status_value === 1" class="p-1.5 text-sm font-semibold uppercase tracking-wider text-red-600 border-2 border-red-600 rounded-lg">
                            {{ invoice.status_label }}
                        </div>
                        <div v-else-if="invoice.status_value === 2" class="p-1.5 text-sm font-semibold uppercase tracking-wider text-blue-600 border-2 border-blue-600 rounded-lg">
                            {{ invoice.status_label }}
                        </div>
                        <div v-else-if="invoice.status_value === 3" class="p-1.5 text-sm font-semibold uppercase tracking-wider text-green-600 border-2 border-green-600 rounded-lg">
                            {{ invoice.status_label }}
                        </div>
                        <div v-else-if="invoice.status_value === 4" class="p-1.5 text-sm font-semibold uppercase tracking-wider text-red-600 border-2 border-red-600 rounded-lg">
                            {{ invoice.status_label }}
                        </div>
                        <div v-else-if="invoice.status_value === 5" class="p-1.5 text-sm font-semibold uppercase tracking-wider text-gray-600 border-2 border-gray-600 rounded-lg">
                            {{ invoice.status_label }}
                        </div>
                        <div v-else-if="invoice.status_value === 6" class="p-1.5 text-sm font-semibold uppercase tracking-wider text-yellow-600 border-2 border-yellow-600 rounded-lg">
                            {{ invoice.status_label }}
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <Link 
                            :href="`/lawyer/case-files/${case_file.id}/invoices/${invoice.id}/edit`"
                            as="button" 
                            class="btn-primary"
                            v-if="invoice.is.editable"
                        >
                            Change to Open
                        </Link>
                        <Link 
                            :href="`/lawyer/case-files/${case_file.id}/invoices/${invoice.id}/edit`"
                            as="button" 
                            class="btn-secondary"
                            v-if="invoice.is.editable"
                        >
                            Edit
                        </Link>
                    </div>
                </div>

                <div class="flex flex-wrap w-full mt-6 border-b border-gray-200 text-sm">
                    <div class="flex flex-wrap pb-8 pr-6 w-full lg:w-1/2">
                        <div class="text-gray-500 mr-4">
                            Issued on
                        </div> 
                        <div class="text-gray-700 mr-16">
                            {{  invoice.invoice_date }}
                        </div>
                    </div>
                    <div class="flex flex-wrap pb-8 pr-6 w-full lg:w-1/2">
                        <div class="text-gray-00 mr-4">
                            Due on
                        </div> 
                        <div class="text-gray-700 mr-16">
                            {{  invoice.due_date }}
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap w-full mt-4 mb-6 text-sm">
                    <div class="flex flex-col flex-wrap w-full lg:w-1/2">
                        <div class="font-semibold text-gray-900">
                            From
                        </div>
                        <div class="flex flex-col mt-3 text-gray-500">
                            <div class="font-medium text-gray-900 mb-2">
                                {{ invoice.company.name }}
                            </div>
                            <div>
                                {{ invoice.company.address }}
                            </div>
                        </div>  
                    </div>
                    <div class="flex flex-col flex-wrap w-full lg:w-1/2">
                        <div class="font-semibold text-gray-900">
                            To
                        </div>
                        <div class="flex flex-col mt-3 text-gray-500">
                            <div class="font-medium text-gray-900 mb-2">
                                {{ invoice.client.name }}
                            </div>
                            <div>
                                {{ invoice.client.address }}
                            </div>
                        </div>  
                    </div>
                </div>
                
                <table class="w-full my-4 whitespace-nowrap text-left text-sm leading-6">
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

                <div class="flex flex-col mt-4 w-full">
                    <div class="text-gray-900 font-medium mb-3">Notes</div>
                    <div class="bg-gray-50 text-gray-500 text-sm px-4 py-6 border border-dashed whitespace-pre-wrap">
                        {{ invoice.notes }}
                    </div>
                </div>
            </div>
        </div>
        
        <!-- <div class="w-full max-w-3xl h-fit xl:max-w-xs bg-gray-50 border-1 border-gray-100 shadow ring-gray-900 rounded-md overflow-hidden mt-5 xl:mt-0 xl:ml-5 py-6 px-4">
            <div>
                Invoice status: Draft
                <button>Change status to open</button>
            </div>
        </div> -->
    </div>
</template>

<script>
import Layout from '../Shared/Layout';

export default { 
    components: { 
        
    },
    layout: Layout,
    props: {
        case_file: Object,
        invoice: Object,
        items: Object,
    },
    data() {
        return {
            page_title: 'View Invoice',
            breadcrumbs: [
                { link: '/lawyer', label: 'Dashboard'},
                { link: '/lawyer/case-files/', label: 'Case Files'},
                { link: `/lawyer/case-files/${this.case_file.id}`, label: this.case_file.file_number},
                { link: `/lawyer/case-files/${this.case_file.id}/invoices`, label: 'Invoices'},
                { link: null, label: this.invoice.number},
            ],
        }
    },
    methods: {
        
    },
};
</script>