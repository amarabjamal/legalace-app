<template>
    <Head :title="page_title" />

    <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs"/>
    
    <div class="flex flex-col xl:flex-row xl:space-x-6 space-y-6 xl:space-y-0">
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
                        <div v-else-if="invoice.status_value === 3" class="p-1.5 text-sm font-semibold uppercase tracking-wider text-blue-600 border-2 border-blue-600 rounded-lg">
                            {{ invoice.status_label }}
                        </div>
                        <div v-else-if="invoice.status_value === 4" class="p-1.5 text-sm font-semibold uppercase tracking-wider text-green-600 border-2 border-green-600 rounded-lg">
                            {{ invoice.status_label }}
                        </div>
                        <div v-else-if="invoice.status_value === 5" class="p-1.5 text-sm font-semibold uppercase tracking-wider text-red-600 border-2 border-red-600 rounded-lg">
                            {{ invoice.status_label }}
                        </div>
                        <div v-else-if="invoice.status_value === 6" class="p-1.5 text-sm font-semibold uppercase tracking-wider text-gray-600 border-2 border-gray-600 rounded-lg">
                            {{ invoice.status_label }}
                        </div>
                        <div v-else-if="invoice.status_value === 7" class="p-1.5 text-sm font-semibold uppercase tracking-wider text-yellow-600 border-2 border-yellow-600 rounded-lg">
                            {{ invoice.status_label }}
                        </div>
                    </div>
                    <div v-if="invoice.status_value === 1" class="flex flex-wrap items-center space-x-2">
                        <Link 
                            :href="`/lawyer/case-files/${case_file.id}/invoices/${invoice.id}/edit`"
                            as="button" 
                            class="btn-secondary"
                        >
                            Edit
                        </Link>
                        <form @submit.prevent="setInvoiceToOpen">
                            <button type="submit" class="btn-primary">
                                Change to Open
                            </button>
                        </form>
                    </div>
                    <div v-else-if="invoice.status_value === 2" class="flex flex-wrap items-center space-x-2">
                        <form @submit.prevent="emailInvoice">
                            <button type="submit" class="btn-primary">
                                Send Email
                            </button>
                        </form>
                        <action-dropdown :status_code="invoice.status_value" :case_file_id="case_file.id" :invoice_id="invoice.id" />
                    </div>
                    <div v-else-if="invoice.status_value === 3" class="flex flex-wrap items-center space-x-2">
                        <Link :href="`/lawyer/case-files/${case_file.id}/invoices/${invoice.id}/payment/create`" as="button" class="btn-primary">
                            Add Payment
                        </Link>
                        <action-dropdown :status_code="invoice.status_value" :case_file_id="case_file.id" :invoice_id="invoice.id" @send-email="emailInvoice" />
                    </div>
                    <div v-else-if="invoice.status_value === 4" class="flex flex-wrap items-center space-x-2">
                        <Link v-if="invoice.has_receipt" :href="`/lawyer/case-files/${case_file.id}/invoices/${invoice.id}/receipt`" as="button" class="btn-primary">
                            Receipt
                        </Link>
                        <Link v-else :href="`/lawyer/case-files/${case_file.id}/invoices/${invoice.id}/receipt/create`" as="button" class="btn-primary">
                            Convert to Receipt
                        </Link>
                        <action-dropdown :status_code="invoice.status_value" :case_file_id="case_file.id" :invoice_id="invoice.id" />
                    </div>
                </div>

                <div class="flex flex-wrap w-full mt-6 border-b border-gray-200 text-sm">
                    <div class="flex pb-2 pr-6 w-full">
                        <div class="text-gray-500 mr-4 w-20">
                            File Number
                        </div> 
                        <div class="text-gray-700 font-medium mr-16">
                            {{  case_file.file_number }}
                        </div>
                    </div>
                    <div class="flex pb-8 pr-6 w-full lg:w-1/2">
                        <div class="text-gray-500 mr-4 w-20">
                            Issued on
                        </div> 
                        <div class="text-gray-700 font-medium mr-16">
                            {{  invoice.invoice_date }}
                        </div>
                    </div>
                    <div class="flex pb-8 pr-6 w-full lg:w-1/2">
                        <div class="text-gray-500 mr-4 w-20">
                            Due on
                        </div> 
                        <div class="text-gray-700 font-medium mr-16">
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
        
        <div v-if="invoice.status_value === 4"  class="w-full max-w-3xl h-fit xl:max-w-xs bg-white border-1 border-gray-100 shadow ring-gray-900 rounded-md overflow-hidden">
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
        </div>
    </div>
</template>

<script>
import Layout from '../Shared/Layout';
import ActionDropdown from './Components/ActionDropdown';

export default { 
    components: { 
        ActionDropdown,
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
                { link: '/lawyer/case-files/', label: 'Case Files'},
                { link: `/lawyer/case-files/${this.case_file.id}`, label: this.case_file.file_number},
                { link: `/lawyer/case-files/${this.case_file.id}/invoices`, label: 'Invoices'},
                { link: null, label: this.invoice.number},
            ],
            ppm_is_open: false,
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
        downloadPDF() {
            if(confirm('Download invoice PDF. Are you sure to proceed?')) {
                this.$inertia.post(`/lawyer/case-files/${this.case_file.id}/invoices/${this.invoice.id}/pdf`);
            }
        },
    },
};
</script>