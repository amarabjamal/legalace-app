<template>
    <Head :title="page_title" />

    <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs"/>
    
    <div class="max-w-3xl overflow-hidden">
        <div class="flex items-center justify-between px-2 py-4">
            <div class="flex flex-wrap items-center space-x-2">
                    <h2 class="text-lg font-semibold leading-6 text-gray-700">
                        {{  claim_voucher.number }}
                    </h2>
                    <div v-if="claim_voucher.status === 'Draft'" class="px-1.5 py-1 text-xs font-medium uppercase tracking-wider text-gray-600 border border-gray-600 rounded-lg">
                        {{ claim_voucher.status }}
                    </div>
                    <div v-else-if="claim_voucher.status === 'Sent'" class="px-1.5 py-1 text-xs font-medium uppercase tracking-wider text-green-600 border border-green-600 rounded-lg">
                        {{ claim_voucher.status }}
                    </div>
                </div>
            <div class="flex flex-wrap items-center space-x-2">
                <form v-if="claim_voucher.status === 'Draft'" @submit.prevent="submitClaimVoucher">
                    <button type="submit" class="btn-primary flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-4 w-4 inline-block mr-1"><path d="M1.94631 9.31555C1.42377 9.14137 1.41965 8.86034 1.95706 8.6812L21.0433 2.31913C21.5717 2.14297 21.8748 2.43878 21.7268 2.95706L16.2736 22.0433C16.1226 22.5718 15.8179 22.5901 15.5946 22.0877L12.0002 14.0002L18.0002 6.00017L10.0002 12.0002L1.94631 9.31555Z"></path></svg>
                        Submit
                    </button>
                </form>
            </div>
        </div>

        <div class="flex flex-wrap bg-white rounded-md shadow-lg p-8 py-10">
            <div class="flex flex-wrap w-full border-b border-gray-200 text-sm">
                <div class="flex pb-2 pr-6 w-full lg:w-1/2">
                    <div class="text-gray-500 mr-4 w-26">
                        Approver
                    </div> 
                    <div class="text-gray-700 font-medium mr-16">
                        {{  claim_voucher.approver.name }}
                    </div>
                </div>
                <div class="flex pb-8 pr-6 w-full lg:w-1/2">
                    <div class="text-gray-500 mr-4 w-26">
                        Submission Date
                    </div> 
                    <div class="text-gray-700 font-medium mr-16">
                        {{  claim_voucher.date }}
                    </div>
                </div>
            </div>

            <!-- <div class="flex flex-wrap w-full mt-4 mb-6 text-sm">
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
            </div> -->

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
                        <th scope="row" colspan="2" class="pt-4 sm:text-right font-semibold text-gray-900">Total</th>
                        <td colspan="1" class="pb-0 pl-8 pr-0 pt-4 text-right font-semibold tabular-nums text-gray-900">{{ claim_voucher.amount }}</td>
                    </tr>
                </tfoot>
            </table>

            <!-- <div class="flex flex-col mt-2 w-full">
                <div class="text-gray-900 font-medium mb-3">Notes</div>
                <div class="bg-gray-50 text-gray-500 text-sm px-4 py-6 border border-dashed whitespace-pre-wrap">
                    
                </div>
            </div> -->
        </div>
    </div>
</template>

<script>
import Layout from '../Shared/Layout';

export default { 
    components: { 
    },
    layout: Layout,
    props: {
        claim_voucher: Object,
        items: Object,
    },
    data() {
        return {
            page_title: 'Claim Voucher ' + this.claim_voucher.number,
            breadcrumbs: [
                { link: '/lawyer/dashboard', label: 'Lawyer'},
                { link: '/lawyer/claim-vouchers/', label: 'Claim Vouchers'},
                { link: null, label: this.claim_voucher.number},
            ],
        }
    },
    methods: {
        submitClaimVoucher() {
            if(confirm('Please ensure that all the details are correct before submitting this claim voucher. Are you sure to proceed?')) {
                this.$inertia.post(`/lawyer/claim-vouchers/${this.claim_voucher.id}/submit`);
            }
        },
    },
};
</script>