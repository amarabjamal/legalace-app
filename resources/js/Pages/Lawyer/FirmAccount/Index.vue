<template>

    <Head :title="page_title" />

    <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs" />

    <div v-for="acc in firmAccountList.data" :key="acc.id"
    class="grid gap-6 mb-8 md:grid-cols-3 mt-4">

        <div v-on:click="navigateToDetails(acc.id)" class="min-w-0 p-4 bg-white  shadow-xs dark:bg-gray-800 border border-gray-300 rounded-md">
            <!-- <div v-for="bankAccount in bankAccounts" class="min-w-0 p-4 bg-white  shadow-xs dark:bg-gray-800"> -->
            <h4 class="mb-4 font-semibold text-gray-600 dark:text-gray-300">
                {{ acc.account_name }}
            </h4>
            <p class="text-gray-600 dark:text-gray-400">
            <table class="border-separate border-spacing-2 dark:text-gray-400">
                <tr>
                    <td width="150px">
                        {{ acc.bank_name }}
                    </td>
                    <td class="font-bold">
                        <!-- <span class="font-bold">{{ bankAccount.bank_name }}</span> -->
                    </td>
                </tr>
                <tr>
                    <td>
                        {{ acc.account_name }}
                    </td>
                    <td class="font-bold">
                        <!-- {{ bankAccount.account_number }} -->
                    </td>
                </tr>
                <tr>
                    <td>
                        {{ acc.account_number }}
                    </td>
                    <td class="font-bold">
                        <!-- {{ bankAccount.bank_address }} -->
                    </td>
                </tr>
                <tr>
                    <td>
                        {{ acc.swift_code }}
                    </td>
                    <td class="font-bold">
                        <!-- {{ bankAccount.swift_code }} -->
                    </td>
                </tr>
                <tr>
                    <td>
                        RM {{ acc.opening_balance }}
                    </td>
                    <td class="font-bold">
                        <!-- {{ bankAccount.account_type.name }} -->
                    </td>
                </tr>
            </table>
            <div class="flex justify-end mt-3 p-2 pr-4">
                <!-- <Link :href="`/bank-accounts/${ bankAccount.id }/edit`">Edit</Link>
                                <Link @click="deleteBankAccount(bankAccount)" as="button" class="ml-3 font-medium text-red-600 hover:underline">Delete</Link> -->
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