<template>
    <Head :title="page_title" />

    <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs"/>
    
    <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
        Pending for Approval Vouchers
    </h4>

    <div class="relative overflow-x-auto shadow-md">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-200 uppercase bg-blue-900">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        No.
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Title
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Type
                    </th>
                    <th scope="col" class="px-6 py-3 text-right">
                        Amount(MYR)
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Requestor
                    </th>
                    <th scope="col" class="px-6 py-3">
                        <span>Actions</span>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr class="bg-white border-b">
                    <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        1
                    </td>
                    <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        Disbursement item #5367
                    </td>
                    <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        Claim Voucher
                    </td>
                    <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap text-right">
                        1,300.00
                    </td>
                    <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        Wayne Howe
                    </td>
                    <td class="px-6 py-4">
                        <Link @click="approve(voucher)" as="button" class="font-medium text-blue-600 hover:underline">Approve</Link>
                        <Link @click="reject(voucher)" as="button" class="ml-3 font-medium text-red-600 hover:underline">Reject</Link>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <h4 class="mb-4 mt-6 text-lg font-semibold text-gray-600 dark:text-gray-300">
        Approved Vouchers
    </h4>

    <div class="relative overflow-x-auto shadow-md">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-200 uppercase bg-blue-900">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        No.
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Title
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Type
                    </th>
                    <th scope="col" class="px-6 py-3 text-right">
                        Amount(MYR)
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Requestor
                    </th>
                    <th scope="col" class="px-6 py-3">
                        <span>Status</span>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr class="bg-white border-b">
                    <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        1
                    </td>
                    <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        Disbursement item #5367
                    </td>
                    <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        Claim Voucher
                    </td>
                    <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap text-right">
                        1,300.00
                    </td>
                    <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        Wayne Howe
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                            Approved
                        </span>
                    </td>
                </tr>
                <tr class="bg-white border-b">
                    <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        2
                    </td>
                    <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        Disbursement item #5367
                    </td>
                    <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        Claim Voucher
                    </td>
                    <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap text-right">
                        1,300.00
                    </td>
                    <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        Wayne Howe
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                            Paid
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <h4 class="mb-4 mt-6 text-lg font-semibold text-gray-600 dark:text-gray-300">
        Rejected Vouchers
    </h4>

    <div class="relative overflow-x-auto shadow-md">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-200 uppercase bg-blue-900">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        No.
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Title
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Type
                    </th>
                    <th scope="col" class="px-6 py-3 text-right">
                        Amount(MYR)
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Requestor
                    </th>
                    <th scope="col" class="px-6 py-3">
                        <span>Status</span>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr class="bg-white border-b">
                    <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        1
                    </td>
                    <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        Disbursement item #5367
                    </td>
                    <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        Claim Voucher
                    </td>
                    <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap text-right">
                        1,300.00
                    </td>
                    <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        Wayne Howe
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full dark:text-gray-100 dark:bg-gray-700">
                            Rejected
                        </span>
                    </td>
                </tr>
                <tr class="bg-white border-b">
                    <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        2
                    </td>
                    <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        Disbursement item #5367
                    </td>
                    <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        Claim Voucher
                    </td>
                    <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap text-right">
                        1,300.00
                    </td>
                    <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        Wayne Howe
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full dark:text-gray-100 dark:bg-gray-700">
                            Rejected
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import Layout from "../Shared/Layout";

export default { 
    props: { 
        company: Object,
     },
    components: { 

    },
    layout: Layout,
    data() {
        return {
            page_title: 'Voucher Approvals',
            breadcrumbs: [
                { link: '/admin/dashboard', label: 'Admin'},
                { link: null, label: 'Voucher Approvals'},
            ],
        }
    },
};
</script>