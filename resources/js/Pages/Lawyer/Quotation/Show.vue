<template>
    <Head :title="page_title" />

    <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs"/>

    <div class="max-w-3xl bg-white rounded-md border border-gray-300 overflow-hidden">
        <div class="flex flex-wrap px-8 py-6">
            <h3 class="font-semibold leading-6 text-gray-800 text-lg tracking-tight mb-4">Scope of services & legal fees</h3>

            <!-- Work Description Table -->
            <div class="w-full overflow-x-auto overflow-y-hidden mb-10">
                <table class="w-full whitespace-nowrap text-left text-sm leading-6">
                    <thead class="border-b-2 border-gray-300 text-gray-600 select-none">
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
                            <td class="max-w-0 pr-4 py-2 align-top">
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

            <div class="w-full">
                <h3 class="font-semibold leading-6 text-gray-800 text-lg mb-4">Initial deposit</h3>

                <div class="flex space-x-2">
                    <div class="w-1/2">
                        <label class="form-label">Amount</label>
                        {{ quotation.deposit_amount }}
                    </div>

                    <div class="w-1/2">
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
    </div>
</template>

<script>
import Layout from "../Shared/Layout";

export default { 
    components: { 
    },
    layout: Layout,
    props: {
        case_file : Object,
        quotation: Object,
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
        }
    },
};
</script>