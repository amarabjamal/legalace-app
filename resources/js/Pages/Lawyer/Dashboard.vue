<template>
    <Head title="Dashboard" />

    <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs" />

    <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-2">
        <!-- Card -->
        <div class="card flex items-center p-8 bg-white rounded-lg">
            <div class="w-full h-64">
                <Line
                    id="my-line-chart-id"
                    :options="lineChartOptions"
                    :data="lineChartData"
                />
            </div>
        </div>
        <!-- Card -->
        <div class="card flex items-center p-8 bg-white rounded-lg">
            <div>
                <!-- <p class="mb-2 text-sm font-medium">
                Firm Account balance
            </p> -->
                <Pie
                    id="my-pie-chart-id"
                    :options="pieChartData"
                    :data="pieChartData"
                />
            </div>
        </div>
        <!-- Card -->
        <div class="card flex items-center p-8 bg-white rounded-lg">
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
                            v-for="item in bankAccount"
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
                                MYR {{ item.opening_balance.amount }}
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Card -->
        <div class="card flex items-center p-8 bg-white rounded-lg">
            <div class="w-full h-64">
                <p class="mb-2 text-sm font-medium">Case File</p>
                <div class="overflow-x-auto overflow-y-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead
                            class="text-xs text-gray-700 uppercase bg-gray-50"
                        >
                            <tr>
                                <th scope="col" class="px-6 py-3">No</th>
                                <th scope="col" class="px-6 py-3">Matter</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(item, index) in caseFile"
                                :key="index"
                                class="bg-white border-b"
                            >
                                <th
                                    scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"
                                >
                                    {{ index + 1 }}
                                </th>
                                <th
                                    scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"
                                >
                                    {{ item.matter }}
                                </th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Layout from "./Shared/Layout";
import { Bar, Line, Pie } from "vue-chartjs";
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    ArcElement,
} from "chart.js";

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    ArcElement,
);

export default {
    components: {
        Bar,
        Line,
        Pie,
    },
    layout: Layout,
    props: {
        date: Object,
        expenseData: Object,
        incomeDate: Object,
        incomeData: Object,
        bankAccount: Object,
        topExpense: Object,
        caseFile: Object,
    },
    data() {
        return {
            page_title: "Dashboard",
            breadcrumbs: [
                { link: "/lawyer/dashboard", label: "Lawyer" },
                { link: null, label: "Dashboard" },
            ],
            chartData: {
                labels: ["January", "February", "March"],
                datasets: [{ data: [40, 20, 12] }],
            },
            chartOptions: {
                responsive: true,
            },
            lineChartData: {
                labels: this.date,
                datasets: [
                    {
                        label: "Expense",
                        backgroundColor: "#f87979",
                        data: this.expenseData,
                    },
                    {
                        label: "Income",
                        backgroundColor: "#0000FF",
                        data: this.incomeData,
                    },
                ],
            },
            lineChartOptions: {
                responsive: true,
                maintainAspectRatio: false, // Allows chart to fill the container
                plugins: {
                    legend: {
                        display: true,
                        position: "top",
                    },
                },
            },
            pieChartData: {
                labels: this.topExpense.map((item) =>
                    this.formatString(item.description),
                ),
                datasets: [
                    {
                        backgroundColor: [
                            "#41B883",
                            "#E46651",
                            "#00D8FF",
                            "#DD1B16",
                        ],
                        data: this.topExpense.map((item) => item.total),
                    },
                ],
            },
            pieChartOptions: {
                responsive: true,
                maintainAspectRatio: false, // Allows chart to fill the container
                plugins: {
                    legend: {
                        display: true,
                        position: "top",
                    },
                },
            },
        };
    },
    methods: {
        formatString(str) {
            return str
                .split("_") // Split by underscores
                .map((word) => word.charAt(0).toUpperCase() + word.slice(1)) // Capitalize the first letter of each word
                .join(" "); // Join the words with spaces
        },
    },
};
</script>
