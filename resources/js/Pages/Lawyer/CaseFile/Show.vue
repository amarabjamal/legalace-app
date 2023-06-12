<template>
    <Head title="View Case File" />

    <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs"/>

    <div class="card">
        <div class="card-header flex justify-between">
            <div class="card-header-title font-size-lg text-capitalize font-weight-normal">
                Case File Information
            </div>
            <div>
                <Link :href="`/lawyer/case-files/${case_file.id}/edit`">Edit</Link>
            </div>
        </div>

        <div class="card-body">
            <div>
                File Number: {{ case_file.file_number }}
            </div>
            <div>
                Matter: {{ case_file.matter }}
            </div>
            <div>
                Type: {{ case_file.type }}
            </div>
            <div>
                No Conflict: 
                <span v-if="case_file.no_conflict_checked === 1" class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                    Verified
                </span>

                <span v-else class="px-2 py-1 font-semibold leading-tight text-orange-700 bg-orange-100 rounded-full dark:text-white dark:bg-orange-600"> 
                    Pending
                </span>
            </div>
            <div>
                Client: {{ case_file.client.name }}
            </div>
            <div>
                File Owner: {{ case_file.created_by.name }} <span v-if="case_file.created_by.name == $page.props.auth.user.name" class="text-sm text-gray-400">(You)</span>
            </div>
        </div>

        <!-- <div class="text-center d-block p-3 card-footer">
            Footer
        </div> -->
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-header-title font-size-lg text-capitalize font-weight-normal">
                Quotation
            </div>
        </div>

        <div class="card-body">
            <Link :href="`/lawyer/case-files/${case_file.id}/quotation`"><button class="btn">View Quotation</button></Link>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-header-title font-size-lg text-capitalize font-weight-normal">
                Disbursement Items
            </div>
        </div>

        <div class="card-body">
            <Link :href="`/lawyer/case-files/${case_file.id}/disbursement-items`"><button class="btn">View Disbursement Items</button></Link>
        </div>

    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-header-title font-size-lg text-capitalize font-weight-normal">
                Invoices
            </div>
        </div>

        <div class="card-body">
            <Link :href="`/lawyer/case-files/${case_file.id}/invoices`"><button class="btn">View Invoices</button></Link>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-header-title font-size-lg text-capitalize font-weight-normal">
                Receipts
            </div>
        </div>

        <div class="card-body">
            None
        </div>
    </div>

</template>

<script>
import Layout from "../Shared/Layout";

export default { 
    components: { 
    },
    props: {
        'case_file' : Object,
    },
    data() {
        return {
            page_title: 'Case File: ' + this.case_file.file_number,
            breadcrumbs: [
                { link: '/lawyer/dashboard', label: 'Lawyer'},
                { link: '/lawyer/case-files/', label: 'Case Files'},
                { link: null, label: this.case_file.file_number},
            ],
        }
    },
    layout: Layout,
};
</script>

<style scope>
.card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 1px solid rgba(0, 0, 0, .125);
    border-radius: .25rem;
    margin-bottom: 30px;
}

.card-header {
    padding: .75rem 1.25rem;
    margin-bottom: 0;
    color: inherit;
    background-color: rgba(0, 0, 0, .03);
    border-bottom: 1px solid rgba(0, 0, 0, .125)
}

.card-body {
    padding: .75rem 1.25rem;
    margin-bottom: 0;
}

.card-footer {
    padding: .75rem 1.25rem;
    background-color: rgba(0, 0, 0, .03);
    border-top: 1px solid rgba(0, 0, 0, .125)
}
</style>