<template>
    <Head :title="page_title" />

    <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs"/>   
    
    <display-receipt-modal :isOpen="show_display_receipt_modal" :url="receipt_url" @close-modal="show_display_receipt_modal = false" />

    <div class="max-w-3xl bg-white rounded-md border border-gray-300 overflow-hidden">
        <dl>
            <div class="bg-white border-b px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Record Type</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ disbursement_item.record_type }}
                </dd>
            </div>
            <div class="bg-white border-b px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Name</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ disbursement_item.name }}
                </dd>
            </div>
            <div class="bg-white border-b px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Date</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ disbursement_item.date }}
                </dd>
            </div>
            <div class="bg-white border-b px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Description</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <span v-if="disbursement_item.description">
                        {{ disbursement_item.description }}
                    </span>
                    <span v-else class="text-gray-400 italic">No description provided.</span>
                </dd>
            </div>
            <div class="bg-white border-b px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Amount</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ disbursement_item.amount }}
                </dd>
            </div>
            <div class="bg-white border-b px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Fund Type</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ disbursement_item.fund_type }}
                </dd>
            </div>
            <div class="bg-white border-b px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Receipt</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <button v-if=" disbursement_item.receipt" @click="loadReceipt" class="text-blue-500 hover:underline">
                        {{ disbursement_item.receipt }}
                    </button>
                    <span v-else class="text-gray-400 italic">No receipt provided.</span>
                </dd>
            </div>
            <div class="bg-white border-b px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Creator</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ disbursement_item.creator }}
                </dd>
            </div>
        </dl>
        
        <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
            <Link as="button" :href="`/lawyer/case-files/${this.case_file.id}/disbursement-items/${this.disbursement_item.id}/edit`" class="btn-primary">
                Edit
            </Link> 
        </div>
    </div>
</template>

<script>
import Layout from '../Shared/Layout';
import DisplayReceiptModal from './Components/DisplayReceiptModal';

export default {
    components: {
        DisplayReceiptModal,
    },
    layout: Layout,
    props: {
        case_file: Object,
        disbursement_item: Object,
    },
    data() {
        return {
            page_title: this.disbursement_item.name,
            breadcrumbs: [
                { link: '/lawyer/dashboard', label: 'Lawyer'},
                { link: '/lawyer/case-files/', label: 'My Cases'},
                { link: `/lawyer/case-files/${this.case_file.id}`, label: this.case_file.file_number},
                { link: `/lawyer/case-files/${this.case_file.id}/disbursement-items`, label: 'Items'},
                { link: null, label: this.disbursement_item.name},
            ],
            show_display_receipt_modal: false,
            receipt_url: `/lawyer/case-files/${this.case_file.id}/disbursement-items/${this.disbursement_item.id}/receipt`,
        }
    },
    methods: {
        loadReceipt() {
            this.show_display_receipt_modal = true;
        },
    },
}

</script>