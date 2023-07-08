<template>
    <Head :title="page_title" />

    <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs"/>

    <!-- <div class="mb-4 border-b border-gray-200">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" role="tablist">
            <li class="mr-2" role="presentation">
                <button class="inline-block p-4 pt-0 border-b-2 rounded-t-lg text-blue-600 hover:text-blue-600 border-blue-600" type="button" role="tab" aria-controls="pending" aria-selected="false">Pending</button>
            </li>
            <li class="mr-2" role="presentation">
                <Link as="button" href="/admin/voucher-requests?status=approved" class="inline-block p-4 pt-0 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300" role="tab" aria-controls="approved" aria-selected="false">Approved</Link>
            </li>
            <li class="mr-2" role="presentation">
                <Link as="button" href="/admin/voucher-requests?status=rejected" class="inline-block p-4 pt-0 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300"  role="tab" aria-controls="rejected" aria-selected="false">Rejected</Link>
            </li>
        </ul>
    </div>
    
    <h4 class="my-4 text-sm font-medium text-gray-700">
        Pending approval
    </h4> -->

    <div class="bg-white rounded-md shadow overflow-x-auto">
        <table class="w-full whitespace-no-wrap">
        <thead class="table-head">
            <tr class="table-head-row">
                <th class="table-head-column w-40">Ticket Number</th>
                <th class="table-head-column w-44">Submission Date</th>
                <th class="table-head-column w-28">Status</th>
                <th class="table-head-column">Requester</th>
                <th class="table-head-column w-44 text-right">Amount</th>
                <th class="table-head-column w-16">
                    <span class="sr-only">Actions</span>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="voucher_request in voucher_requests.data" :key="voucher_request.id" class="table-body-row">
                <td class="table-body-data">
                   {{ voucher_request.ticket_number }}
                </td>
                <td class="table-body-data">
                    {{ voucher_request.submission_date }}
                </td>
                <td class="table-body-data">
                    <span :class="statusClass(voucher_request.status)">
                        {{ voucher_request.status }}
                    </span>
                </td>
                <td class="table-body-data">
                    {{ voucher_request.requester }}
                </td>
                <td class="table-body-data text-right tabular-nums">
                    {{ voucher_request.amount }}
                </td>
                <td class="table-body-data">
                    <Link :href="`/admin/voucher-requests/${voucher_request.id}`" class="font-medium hover:text-blue-600">
                        <icon name="cheveron-right" class="block w-5 h-5 fill-gray-400" />
                    </Link>
                </td>
            </tr>
            <tr v-if="voucher_requests.data.length === 0">
                    <td class="px-6 py-4 border-t text-center text-slate-500 bg-slate-100" colspan="100%">No records found.</td>
                </tr>
        </tbody>
        </table>
    </div>

    <pagination v-if="voucher_requests.data.length > 0"  :links="voucher_requests.links" :total="voucher_requests.total" :from="voucher_requests.from" :to="voucher_requests.to"/>
</template>

<script>
import Layout from "../Shared/Layout";
import Pagination from '../../../Shared/Pagination';
import Icon from '../../../Shared/Icon';
import SearchFilter from '../../../Shared/SearchFilter';
import throttle from 'lodash/throttle';
import pickBy from 'lodash/pickBy'
import mapValues from 'lodash/mapValues';
import { cva } from "class-variance-authority";

export default { 
    components: { 
        Pagination, 
        Icon,
        SearchFilter,
    },
    layout: Layout,
    props: { 
        voucher_requests: Object,
     },
    data() {
        return {
            page_title: 'Voucher Requests',
            breadcrumbs: [
                { link: '/admin/dashboard', label: 'Admin'},
                { link: null, label: 'Voucher Requests'},
            ],
        }
    },
    methods: {
        statusClass(status) {
            return cva("p-1.5 text-xs font-medium uppercase tracking-wider rounded-sm bg-opacity-50", {
                variants: {
                    status: {
                        Draft:"text-gray-800 bg-gray-200",
                        Submitted:"text-yellow-800 bg-yellow-200",
                        Approved:"text-orange-800 bg-orange-200",
                        Rejected:"text-red-800 bg-red-200",
                        Reimbursed:"text-green-800 bg-green-200",
                    }
                }
            }) ({
                status: status,
            })
        }
    },
};
</script>