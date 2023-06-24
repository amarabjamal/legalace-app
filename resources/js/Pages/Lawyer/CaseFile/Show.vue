<template>
    <Head :title="page_title" />

    <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs"/>

    <div class="w-full grid lg:grid-cols-3 gap-4">
        <div class="lg:col-span-2 max-w-3xl bg-white rounded-md border border-gray-300 overflow-hidden">
            <dl>
                <div class="bg-white border-b px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">File Number</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ case_file.file_number }}
                    </dd>
                </div>
                <div class="bg-white border-b px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Matter</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ case_file.matter }}
                    </dd>
                </div>
                <div class="bg-white border-b px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Type</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ case_file.type }}
                    </dd>
                </div>
                <div class="bg-white border-b px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Conflict Check</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <span v-if="case_file.no_conflict_checked" class="p-1.5 text-xs font-medium uppercase tracking-wider rounded-sm bg-opacity-50 text-green-800 bg-green-200">
                            Resolved
                        </span>
                        <span v-else class="p-1.5 text-xs font-medium uppercase tracking-wider rounded-sm bg-opacity-50 text-red-800 bg-red-200"> 
                            Pending
                        </span>
                    </dd>
                </div>
                <div class="bg-white border-b px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Client</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ case_file.client }}
                    </dd>
                </div>
                <div class="bg-white border-b px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Creator</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ case_file.creator }}
                    </dd>
                </div>
            </dl>
    
            <div class="px-8 py-4 bg-gray-50 border-t border-gray-100">
                <div v-if="view_as === 'owner'" class="flex flex-row-reverse space-x-2 space-x-reverse items-center justify-start ">
                    <Link as="button" :href="`/lawyer/case-files/${case_file.id}/edit`" class="btn-primary">
                        Edit
                    </Link> 
                    <!-- Only for pending conflict checked -->
                    <form v-if="!case_file.no_conflict_checked" @submit.prevent="resolveNoConflict">
                        <button type="submit" class="btn-secondary bg-white">Resolve as No Conflict</button>
                    </form>
                </div>

                <div v-else-if="view_as === 'viewer'" class="flex justify-end">
                    <form v-if="!case_file.no_conflict_checked" @submit.prevent="reportConflict">
                        <button type="submit" class="btn-danger flex justify-center items-center space-x-2">
                            <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M4.00098 20V14C4.00098 9.58172 7.5827 6 12.001 6C16.4193 6 20.001 9.58172 20.001 14V20H21.001V22H3.00098V20H4.00098ZM6.00098 14H8.00098C8.00098 11.7909 9.79184 10 12.001 10V8C8.68727 8 6.00098 10.6863 6.00098 14ZM11.001 2H13.001V5H11.001V2ZM19.7792 4.80761L21.1934 6.22183L19.0721 8.34315L17.6578 6.92893L19.7792 4.80761ZM2.80859 6.22183L4.22281 4.80761L6.34413 6.92893L4.92991 8.34315L2.80859 6.22183Z"></path></svg>
                            <span>
                                Report Conflict
                            </span>
                        </button>
                    </form>
                    <div v-else class="flex flex-col text-sm font-semibold text-gray-500">
                        Conflict has been resolved.<span class="text-gray-400 font-normal">This case file no longer accepting conflict report</span>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="view_as === 'owner'" class="lg:w-full max-w-3xl h-fit bg-white rounded-md border border-gray-300 overflow-hidden">
            <div class="p-8 flex flex-col justify-center items-start space-y-4">
                <Link as="button" :href="`/lawyer/case-files/${case_file.id}/quotation`">
                    Quotation
                </Link>
                
                <Link as="button" :href="`/lawyer/case-files/${case_file.id}/disbursement-items`">
                    Disbursement Items
                </Link>
    
                <Link as="button" :href="`/lawyer/case-files/${case_file.id}/invoices`">
                    Invoices
                </Link>
                <!-- to be implemented -->
                <Link as="button" :href="`/lawyer/case-files/${case_file.id}/receipts`">
                    Receipts
                </Link>
            </div>
        </div>

        <div class="lg:col-span-2 max-w-3xl bg-white rounded-md border border-gray-300 overflow-hidden">
            <div class="p-6">
                <h3 class="font-medium text-lg text-gray-700">Reported conflicts</h3>

                <ul class="mt-4">
                    <li v-for="conflict_report in conflict_reports.data" class="mb-3">
                        <div class="p-2  rounded-sm border border-gray-300 w-full bg-gray-50">
                            <div class="mb-2 font-semibold text-gray-600">{{ conflict_report.creator }}</div>
                            <p class="text-sm text-gray-500 whitespace-pre-wrap">{{ conflict_report.content }}</p>
                        </div>
                        <span class="ml-2 text-xs text-gray-500">{{ conflict_report.posted_on }}</span>
                    </li>

                    <li v-if="conflict_reports.data.length === 0" class="p-4 text-center text-gray-500 rounded-sm border border-gray-200 w-full bg-gray-100">
                        <span>No reports received.</span>
                    </li>
                    <li v-else class="p-2 text-center text-gray-500 w-full">
                        <button v-if="conflict_reports.next_page_url !== null" class="text-sm text-blue-500 hover:text-blue-700 hover:underline">Show more reports</button>
                        <span v-else class="text-gray-400">No more reports</span>
                    </li>
                </ul>
            </div>

        </div>
    </div>

    <report-conflict-modal :isOpen="show_report_conflict_modal" :caseFileId="case_file.id" v-if="!case_file.no_conflict_checked && view_as === 'viewer'" @close-modal="hideReportConflictModal" />
</template>

<script>
import Layout from "../Shared/Layout";
import ReportConflictModal from "./Components/ReportConflictModal";

export default { 
    components: { 
        ReportConflictModal,
    },
    layout: Layout,
    props: {
        view_as : String,
        case_file : Object,
        conflict_reports: Object,
    },
    data() {
        return {
            page_title: this.case_file.file_number,
            breadcrumbs: [
                { link: '/lawyer/dashboard', label: 'Lawyer'},
                { link: '/lawyer/case-files/', label: 'My Cases'},
                { link: null, label: this.case_file.file_number},
            ],
            show_report_conflict_modal: false,
        }
    },
    methods: {
        resolveNoConflict() {
            const proceed = confirm("Please ensure that the case file is conflict free from the existing case files. Are you sure to proceed?");

            if(proceed) {
                this.$inertia.post(`/lawyer/case-files/${this.case_file.id}/resolve-no-conflict`);
            }
        },
        reportConflict() {
            this.showReportConflictModal();
        },
        showReportConflictModal() {
            this.show_report_conflict_modal = true;
        },
        hideReportConflictModal() {
            this.show_report_conflict_modal = false;
        },
    },
};
</script>