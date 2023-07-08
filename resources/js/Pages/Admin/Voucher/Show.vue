<template>
    <Head :title="page_title" />

    <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs"/>
    
    <div class="max-w-3xl relative bg-white rounded-md border border-gray-300 overflow-hidden">        
        <div :class="statusClass">
            {{ claim_voucher.status }}
        </div>
        
        <div class="p-8 space-y-12">   
            <h2 class="text-xl font-semibold leading-6 text-gray-700">
                {{ claim_voucher.number }}
            </h2>

            <div class="grid lg:grid-cols-2 gap-4 w-full pb-6 border-b border-gray-200 text-sm">
                <div class="flex">
                    <div class="text-gray-500 mr-4 w-26">
                        Requester
                    </div> 
                    <div class="text-gray-700 font-medium mr-16">
                        {{  claim_voucher.requester.name }}
                    </div>
                </div>
                <div class="flex">
                    <div class="text-gray-500 mr-4 w-26">
                        Submission Date
                    </div> 
                    <div class="text-gray-700 font-medium mr-16">
                        {{  claim_voucher.date }}
                    </div>
                </div>
            </div>

            <div class="w-full overflow-x-auto overflow-y-hidden">
                <table class="w-full whitespace-nowrap text-left text-sm leading-6">
                    <thead class="border border-gray-300 text-gray-900 bg-gray-100 select-none">
                        <tr>
                            <th class="w-12 px-4 py-2">No.</th>
                            <th>Item</th>
                            <th class="w-40 px-4 py-2 text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in items" :key="index" class="border border-gray-300">
                            <td class="max-w-0 px-4 py-2 align-top">{{ index + 1 }}</td>
                            <td class="max-w-0 px-4 py-2 align-top">
                                <div class="truncate font-medium text-gray-900/100">
                                    {{ item.name }}
                                </div>
                                <div class="truncate text-gray-500/100">
                                    {{ item.description }}
                                </div>
                            </td>
                            <td class="px-4 py-2 align-top text-right tabular-nums">{{ item.amount }}</td>
                        </tr>
                        <tr v-if="items.length === 0" class="border-b border-gray-100/100 bg-gray-100">
                            <td class="text-center py-4 text-gray-500" colspan="100">No items in the list</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th scope="row" colspan="2" class="py-2 sm:text-right font-semibold text-gray-900 border-l border-b border-gray-300 bg-gray-100">Total</th>
                            <td colspan="1" class="py-2 px-4 text-right font-semibold tabular-nums text-gray-900 border-b border-r border-gray-300 bg-gray-100">{{ claim_voucher.amount }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div v-if="claim_voucher.approval" class="flex flex-col mt-2 w-full">
                <div class="text-gray-900 font-medium mb-3">Approval Notes</div>
                <div class="bg-gray-50 text-gray-700 text-sm px-4 py-6 border border-gray-300 whitespace-pre-wrap">
                    {{ claim_voucher.approval }}
                </div>
            </div>
        </div>

        <div v-if="claim_voucher.status === 'Submitted'" class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
            <div class="flex items-center space-x-2 justify-center">
                <button class="btn-danger" @click="rejectVoucher">Reject</button>
                <button class="btn-primary" @click="openApproveVoucherModal">Approve</button>
            </div>
        </div>
    </div>

    <approve-voucher-modal v-if="claim_voucher.status === 'Submitted'" :isOpen="showApproveClaimModal" :claim_voucher="claim_voucher" @close-modal="closeApproveVoucherModal"/>
</template>

<script>
import Layout from '../Shared/Layout';
import LoadingButton from '../../../Shared/LoadingButton';
import ApproveVoucherModal from './Components/ApproveVoucherModal';
import { cva } from 'class-variance-authority';

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
        openApproveVoucherModal() {
            this.showApproveClaimModal = true;
        },
        closeApproveVoucherModal() {
            this.showApproveClaimModal = false;
        },
        rejectVoucher() {
            const proceed = confirm("You're going to reject this claim request. Are you sure to continue?");
            if(proceed) {
                this.$inertia.post(`/admin/voucher-requests/${this.claim_voucher.id}/reject`);
            }
        },
    },
    computed: {
        statusClass() {
            return cva("absolute w-44 text-center select-none font-bold top-6 -right-12 py-2 px-12 rotate-45", {
                variants: {
                    status: {
                        Draft:"text-gray-500 bg-gray-100",
                        Submitted:"text-yellow-500 bg-yellow-100",
                        Approved:"text-orange-500 bg-orange-100",
                        Rejected:"text-red-500 bg-red-100",
                        Reimbursed:"text-green-500 bg-green-100",
                    }
                }
            }) ({
                status: this.claim_voucher.status,
            })
        }
    },
};
</script>