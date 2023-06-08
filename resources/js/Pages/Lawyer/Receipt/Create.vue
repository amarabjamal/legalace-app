<template>
    <Head :title="page_title" />

    <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs"/>
    
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
        <form @submit.prevent="store">
            <div class="flex flex-wrap p-8 py-12">
                <div class="flex justify-between w-full">
                    <text-input v-model="form.receipt_number" :error="form.errors.receipt_number" class="pb-8 lg:pr-6 w-full lg:w-1/2" label="Receipt Number" required/>
                    <div class="text-2xl font-medium leading-6 text-gray-500">
                        Receipt
                    </div>
                </div>

                <div class="flex flex-wrap w-full mt-6 border-b border-gray-200 text-sm">
                    <div class="flex pb-2 pr-6 w-full lg:w-1/2">
                        <div class="text-gray-500 mr-4 w-20">
                            Ref. Invoice
                        </div> 
                        <div class="text-gray-700 font-medium mr-16">
                            {{  case_file.file_number }}
                        </div>
                    </div>
                    <div class="flex pb-8 pr-6 w-full lg:w-1/2">
                        <div class="text-gray-500 mr-4 w-20">
                            Date
                        </div> 
                        <div class="text-gray-700 font-medium mr-16">
                            {{  receipt.date }}
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

                <div class="w-full mt-2 mb-6 border-t border-gray-400 pt-2 text-sm text-gray-800">
                    <span class="text-red-500">*</span>Paid through {{ invoice.payment.method }} on {{ invoice.payment.date }}
                </div>

                <textarea-input v-model="form.notes" :error="form.errors.notes" rows="4" class="w-full" label="Notes"/>
            </div>
            <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
                <Link 
                    :href="`/lawyer/case-files/${this.case_file.id}/invoices`"
                    as="button"  
                    class="mr-2 text-gray-500 focus:outline-none hover:text-blue-700 hover:underline focus:z-10 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center"
                    :disabled="form.processing"
                    >
                    Cancel
                </Link>
                <loading-button :loading="form.processing" class="btn-primary" type="submit">Create Receipt</loading-button>
            </div>
        </form>
    </div>
</template>

<script>
import Layout from '../Shared/Layout';
import TextInput from '../../../Shared/TextInput';
import TextareaInput from '../../../Shared/TextareaInput';
import LoadingButton from '../../../Shared/LoadingButton';

export default { 
    components: { 
        TextInput,
        TextareaInput,
        LoadingButton,
    },
    layout: Layout,
    props: {
        case_file: Object,
        invoice: Object,
        items: Array,
        receipt: Object,
    },
    remember: 'form',
    data() {
        return {
            page_title: 'Create Receipt',
            breadcrumbs: [
                { link: '/lawyer', label: 'Dashboard'},
                { link: '/lawyer/case-files/', label: 'Case Files'},
                { link: `/lawyer/case-files/${this.case_file.id}`, label: this.case_file.file_number},
                { link: `/lawyer/case-files/${this.case_file.id}/invoices`, label: 'Invoices'},
                { link: `/lawyer/case-files/${this.case_file.id}/invoices/${this.invoice.id}`, label: this.invoice.number},
                { link: null, label: 'Receipt'},
                { link: null, label: 'Create'},
            ],
            form: this.$inertia.form({
                receipt_number: this.receipt.number,
                notes: null,
            }),
        }
    },
    methods: {
        store() {
            this.form.post(`/lawyer/case-files/${this.case_file.id}/invoices/${this.invoice.id}/receipt`);
        },
    },
};
</script>