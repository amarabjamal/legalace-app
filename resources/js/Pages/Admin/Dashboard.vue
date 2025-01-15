<template>
    <Head title="Dashboard" />

    <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs" />

    <!-- Cards -->
    <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">
        <!-- Card -->
        <div
            class="flex items-center p-4 bg-white rounded-lg shadow dark:bg-gray-800"
        >
            <div
                class="p-3 mr-4 text-orange-500 bg-orange-100 rounded-full dark:text-orange-100 dark:bg-orange-500"
            >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"
                    ></path>
                </svg>
            </div>
            <div>
                <p
                    class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400"
                >
                    Total Employees
                </p>
                <p
                    class="text-lg font-semibold text-gray-700 dark:text-gray-200"
                >
                    {{ total_users }}
                </p>
            </div>
        </div>
        <!-- Card -->
        <div
            class="flex items-center p-4 bg-white rounded-lg shadow dark:bg-gray-800"
        >
            <div
                class="p-3 mr-4 text-orange-500 bg-orange-100 rounded-full dark:text-orange-100 dark:bg-orange-500"
            >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"
                    ></path>
                </svg>
            </div>
            <div>
                <p
                    class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400"
                >
                    Total clients
                </p>
                <p
                    class="text-lg font-semibold text-gray-700 dark:text-gray-200"
                >
                    {{ total_clients }}
                </p>
            </div>
        </div>
        <!-- Card -->
        <div
            class="flex items-center p-4 bg-white rounded-lg shadow dark:bg-gray-800"
        >
            <div
                class="p-3 mr-4 text-green-500 bg-green-100 rounded-full dark:text-green-100 dark:bg-green-500"
            >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        fill-rule="evenodd"
                        d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                        clip-rule="evenodd"
                    ></path>
                </svg>
            </div>
            <div>
                <p
                    class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400"
                >
                    Total Voucher
                </p>
                <p
                    class="text-lg font-semibold text-gray-700 dark:text-gray-200"
                >
                    {{ total_vouchers }}
                </p>
            </div>
        </div>
        <!-- Card -->
        <div
            class="flex items-center p-4 bg-white rounded-lg shadow dark:bg-gray-800"
        >
            <div
                class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full dark:text-blue-100 dark:bg-blue-500"
            >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"
                    ></path>
                </svg>
            </div>
            <div>
                <p
                    class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400"
                >
                    Total Accounts
                </p>
                <p
                    class="text-lg font-semibold text-gray-700 dark:text-gray-200"
                >
                    {{ total_accounts }}
                </p>
            </div>
        </div>
    </div>

    <!-- Account Summary Side by Side -->
    <div class="flex gap-6 mb-8">
        <!-- Card 1 -->
        <div class="card flex items-center p-8 bg-white rounded-lg w-1/2">
            <div class="w-full h-64">
                <p class="mb-2 text-sm font-medium">Account Summary</p>
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">Account Name</th>
                            <th scope="col" class="px-6 py-3">Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="item in bankAccount.data"
                            class="bg-white border-b"
                        >
                            <th
                                scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"
                            >
                                {{ item.label }}
                            </th>
                            <th
                                scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"
                            >
                                MYR {{ formatToTwoDecimal(item.balance) }}
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- Paginator -->
            <Pagination
                :links="bankAccount.links"
                :total="bankAccount.total"
                :from="bankAccount.from"
                :to="bankAccount.to"
            />
        </div>

        <!-- Card 2 -->
        <div class="card flex items-center p-8 bg-white rounded-lg w-1/2">
            <div class="w-full h-64">
                <p class="mb-2 text-sm font-medium">Client List</p>
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">Client Name</th>
                            <th scope="col" class="px-6 py-3">Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="item in clientProfile.data"
                            class="bg-white border-b"
                        >
                            <th
                                scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"
                            >
                                {{ item.name }}
                            </th>
                            <th
                                scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"
                            >
                                {{ item.email }}
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- Paginator -->
            <Pagination
                :links="clientProfile.links"
                :total="clientProfile.total"
                :from="clientProfile.from"
                :to="clientProfile.to"
            />
        </div>
    </div>
</template>

<script>
import Layout from "./Shared/Layout";
import PageHeading from "../../Shared/PageHeading";
import Pagination from "../../Shared/Pagination.vue";

export default {
    components: {
        PageHeading,
        Pagination,
    },
    layout: Layout,
    props: {
        total_users: Number,
        total_clients: Number,
        total_vouchers: Number,
        total_accounts: Number,
        bankAccount: Object,
        clientProfile: Object,
    },
    data() {
        return {
            page_title: "Dashboard",
            breadcrumbs: [
                { link: "/admin/dashboard", label: "Admin" },
                { link: null, label: "Dashboard" },
            ],
        };
    },
    methods: {
        formatString(str) {
            return str
                .split("_") // Split by underscores
                .map((word) => word.charAt(0).toUpperCase() + word.slice(1)) // Capitalize the first letter of each word
                .join(" "); // Join the words with spaces
        },
        formatToTwoDecimal(num) {
            if (num == null) {
                return "0.00";
            } else {
                return num.toFixed(2); // Formats the number to 2 decimal places
            }
        },
    },
};
</script>
