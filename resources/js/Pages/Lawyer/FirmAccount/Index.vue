<template>

    <Head :title="page_title" />

    <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs" />

    <div v-for="acc in firmAccountList.data" :key="acc.id" class="grid gap-6 mb-8 md:grid-cols-3 mt-4">

        <div v-on:click="navigateToDetails(acc.id)"
            class="min-w-0 bg-white border border-gray-300 rounded-md overflow-hidden ease-in-out duration-300 hover:shadow-md hover:scale-105 hover:-translate-y-5">
            <div class="px-4 mb-2 border-b bg-gray-50 flex justify-between items-center">
                <h4 class="py-2 text-sm uppercase font-semibold text-gray-500 w-1/2 truncate">
                    {{ acc.account_name }}
                </h4>
            </div>

            <p class="text-gray-600 p-2 text-sm">
            <table class="border-separate border-spacing-2">
                <tr>
                    <td width="120px">
                        Bank Name
                    </td>
                    <td width="150px">
                        {{ acc.bank_name }}
                    </td>
                    <td class="font-bold">
                    </td>
                </tr>
                <tr>
                    <td>
                        Account Name
                    </td>
                    <td>
                        {{ acc.account_name }}
                    </td>
                    <td class="font-bold">
                    </td>
                </tr>
                <tr>
                    <td>
                        Account Number
                    </td>
                    <td>
                        {{ acc.account_number }}
                    </td>
                    <td class="font-bold">
                    </td>
                </tr>
                <tr>
                    <td>
                        SWIFT Code
                    </td>
                    <td>
                        {{ acc.swift_code }}
                    </td>
                    <td class="font-bold">
                    </td>
                </tr>
                <tr>
                    <td>
                        Opening Balance
                    </td>
                    <td>
                        RM {{ acc.opening_balance }}
                    </td>
                    <td class="font-bold">
                    </td>
                </tr>
            </table>
            <div class="flex justify-end mt-3 p-2 pr-4">
            </div>
            </p>
        </div>
    </div>
</template>

<script>
import { Head } from "@inertiajs/inertia-vue3";
import SearchFilter from '../../../Shared/SearchFilter';
import Layout from "../Shared/Layout";
import Pagination from "../../../Shared/Pagination.vue";
import Icon from '../../../Shared/Icon';
import { Inertia } from "@inertiajs/inertia";
import throttle from 'lodash/throttle';
import { ref, watch } from "vue";


export default {
    data() {
        return {
            page_title: 'Firm Accounts',
            breadcrumbs: [
                { link: '/lawyer/dashboard', label: 'Lawyer' },
                { link: null, label: 'Firm Accounts' },
            ],
        }
    },
    props: {
        firmAccountList: Object,
    },
    components: { SearchFilter, Icon, Pagination, ref },
    layout: Layout,
    methods: {
        navigateToDetails(accountId) {
            window.location.href = `/lawyer/firm-accounts/${accountId}/detail`;
        }
    },
};
</script>