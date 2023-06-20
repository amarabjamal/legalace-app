<template>
    <Head :title="page_title" />
    
    <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs"/>

    <div class="flex items-center justify-between mb-6">
        <search-filter v-model="form.search" class="mr-4 w-full max-w-md"  @reset="reset"></search-filter>
        <Link class="btn-primary" :href="`/lawyer/claim-vouchers/create`">
            <span>Create</span>
            <span class="hidden md:inline">&nbsp;Voucher</span>
        </Link>
    </div>

    <div class="bg-white rounded-md shadow overflow-x-auto">
        <table class="w-full whitespace-nowrap">
            <thead class="bg-gray-50 border-b-2 border-gray-200">
                <tr class="text-left text-sm tracking-wide font-semibold">
                    <th scope="col" class="py-4 px-6 w-24">
                        Ticket Number
                    </th>
                    <th scope="col" class="py-4 px-6  w-24">
                        Submission Date
                    </th>
                    <th scope="col" class="py-4 px-6 w-32 text-right">
                        Amount
                    </th>
                    <th scope="col" class="py-4 px-6 w-24">
                        Status
                    </th>
                    <th scope="col" class="py-4 px-6">
                        Approver
                    </th>
                    <th scope="col" class="py-4 px-6 w-px"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="claim_voucher in claim_vouchers.data" :key="claim_voucher.id" class="text-sm text-gray-700 hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t px-6 py-4 whitespace-nowrap">
                        {{ claim_voucher.ticket_number }}
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap">
                        {{ claim_voucher.submission_date }}
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap text-right">
                        {{ claim_voucher.amount }}
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap">
                        <span  :class="statusClass(claim_voucher.status)">
                            {{ claim_voucher.status }}
                        </span>
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap">
                        {{ claim_voucher.approver.name }}
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap">
                        <Link class="flex items-center px-2" :href="`/lawyer/claim-vouchers/${claim_voucher.id}`" tabindex="-1">
                            <icon name="cheveron-right" class="block w-5 h-5 fill-gray-400" />
                        </Link>
                    </td>
                </tr>
                <tr v-if="claim_vouchers.data.length === 0" >
                    <td class="px-6 py-4 border-t text-center text-slate-500 bg-slate-100" colspan="100%">No records found.</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- <pagination :links="case_files.links" :from="case_files.from" :to="case_files.to" :total="case_files.total"/> -->
</template>

<script>
import Layout from "../Shared/Layout";
import SearchFilter from '../../../Shared/SearchFilter';
import Icon from '../../../Shared/Icon';
import Pagination from '../../../Shared/Pagination';
import throttle from 'lodash/throttle';
import pickBy from 'lodash/pickBy';
import mapValues from 'lodash/mapValues';
import { cva } from "class-variance-authority";

export default { 
    components: {
        SearchFilter,
        Icon,
        Pagination,
    },
    layout: Layout,
    props: {
        filters: Object,
        claim_vouchers: Object,
    },
    data() {
        return {
            form: {
                search: this.filters.search,
            },
            page_title: 'Claim Vouchers',
            breadcrumbs: [
                { link: '/lawyer/dashboard', label: 'Lawyer'},
                { link: null, label: 'Claim Vouchers'},
            ],
        }
    },
    watch: {
        form: {
          deep: true,
          handler: throttle(function () {
            // this.$inertia.get(`/lawyer/case-files`, pickBy(this.form), {preserveState: true})
          }, 150),
        },
    },
    methods: {
        reset() {
          this.form = mapValues(this.form, () => null);
        },
        statusClass(status) {
            return cva("p-1.5 text-xs font-medium uppercase tracking-wider rounded-lg bg-opacity-50", {
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