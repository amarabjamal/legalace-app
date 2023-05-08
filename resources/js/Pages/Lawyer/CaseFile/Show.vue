<template>
    <Head title="View Case File" />

    <div class="flex flex-col flex-1">
        <main class="h-full pb-16 overflow-y-auto">          
            <div class="container px-6 mx-auto grid">
                <Link class="my-6" href="/lawyer/casefiles"><i class="uil uil-arrow-left"></i> Back to all case files</Link>
    
                <!-- Main Content Start -->
                <div v-if="$page.props.flash.message" class="flex p-4 mb-4 bg-green-100 rounded-lg" role="alert">
                    <svg class="flex-shrink-0 w-5 h-5 text-green-700" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                    <div class="ml-3 text-sm font-medium text-green-700">
                        {{ $page.props.flash.message }}
                    </div>
                    <button type="button" @click="$page.props.flash.message = ''" class="ml-auto -mx-1.5 -my-1.5 bg-green-100 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex h-8 w-8">
                        <span class="sr-only">Close</span>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>

                <div class="card">
                    <div class="card-header flex justify-between">
                        <div class="card-header-title font-size-lg text-capitalize font-weight-normal">
                            Case File Information
                        </div>
                        <div>
                            <Link :href="`/lawyer/casefiles/${case_file.id}/edit`">Edit</Link>
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
                        <Link :href="`/lawyer/casefiles/${case_file.id}/quotation`"><button class="btn">Generate Quotation</button></Link>
                    </div>

                    <!-- <div class="text-center d-block p-3 card-footer">
                        Footer
                    </div> -->
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="card-header-title font-size-lg text-capitalize font-weight-normal">
                            Disbursement Items
                        </div>
                    </div>

                    <div class="card-body">
                        None
                    </div>

                    <!-- <div class="text-center d-block p-3 card-footer">
                        Footer
                    </div> -->
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="card-header-title font-size-lg text-capitalize font-weight-normal">
                            Invoices
                        </div>
                    </div>

                    <div class="card-body">
                        None
                    </div>

                    <!-- <div class="text-center d-block p-3 card-footer">
                        Footer
                    </div> -->
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

                    <!-- <div class="text-center d-block p-3 card-footer">
                        Footer
                    </div> -->
                </div>
          </div>
        </main>
    </div>
</template>

<script>
import { Head, Link } from "@inertiajs/inertia-vue3";
import Layout from "../Shared/Layout";
import Pagination from "../Shared/Pagination";

export default { 
    components: { Head, Pagination, Link },
    props: {
        'case_file' : Object,
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