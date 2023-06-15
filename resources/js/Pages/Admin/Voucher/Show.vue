<template>
    <Head :title="page_title" />

    <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs"/>
    
    <div class="max-w-3xl bg-white rounded-md shadow-lg overflow-hidden">
        <div class="flex items-center justify-between px-8 py-4 bg-gray-50 border-t border-gray-100">
            <h2 class="text-xl font-semibold leading-6 text-gray-700">
                Pending approval
            </h2>
        </div>
        
        <div class="flex flex-wrap p-8 py-8">     
            <div class="flex flex-wrap w-full border-b border-gray-200 text-sm">
                <div class="flex pb-2 pr-6 w-full lg:w-1/2">
                    <div class="text-gray-500 mr-4 w-26">
                        Requester
                    </div> 
                    <div class="text-gray-700 font-medium mr-16">
                        {{  claim_voucher.requester.name }}
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

        </div>
        <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
            <div class="flex items-center space-x-2 justify-center">
                <button class="btn-danger">Reject</button>
                <button class="btn-primary" @click="openApproveVoucherModal">Approve</button>
            </div>
        </div>
    </div>

    <approve-voucher-modal :isOpen="showApproveClaimModal" :claim_voucher="claim_voucher" @close-modal="closeApproveVoucherModal"/>
</template>

<script>
import Layout from '../Shared/Layout';
import LoadingButton from '../../../Shared/LoadingButton';
import ApproveVoucherModal from './Components/ApproveVoucherModal';

export default { 
    components: { 
        LoadingButton,
        ApproveVoucherModal,
    },
    layout: Layout,
    props: {
        claim_voucher: Object,
        items: Object,
    },
    data() {
        return {
            page_title: 'Claim Request ' + this.claim_voucher.number,
            breadcrumbs: [
                { link: '/admin/dashboard', label: 'Admin'},
                { link: '/admin/voucher-requests/', label: 'Voucher Requests'},
                { link: null, label: this.claim_voucher.number},
            ],
            showApproveClaimModal: false,
        }
    },
    methods: {
        submitClaimVoucher() {
            if(confirm('Please ensure that all the details are correct before submitting this claim voucher. Are you sure to proceed?')) {
                this.$inertia.post(`/lawyer/claim-vouchers/${this.claim_voucher.id}/submit`);
            }
        },
        openApproveVoucherModal() {
            this.showApproveClaimModal = true;
        },
        closeApproveVoucherModal() {
            this.showApproveClaimModal = false;
        },
    },
};
</script>