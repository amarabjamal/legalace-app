<template>
    <Head :title="page_title" />

    <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs" />
    <p>Period: {{ startDate }} - {{ endDate }}</p>
    <p>Total Operating Income: {{ totalOperatingIncome }}</p>
    <div
        class="flex flex-row-reverse space-x-2 space-x-reverse items-center justify-start"
    >
        <Link class="btn btn-primary" @click="printDiv()" as="button"
            >Print/Save</Link
        >
        <select
            v-model="selectedPeriod"
            @change="filterByPeriod"
            class="m-1 px-4 py-2 border rounded-md hover:cursor-pointer"
        >
            <option value="this_year">Current Year</option>
            <option value="this_month">This Month</option>
            <option value="last_month">Last Month</option>
            <option value="last_3_months">Last 3 Months</option>
            <option value="last_6_months">Last 6 Months</option>
            <option value="last_year">Last Year</option>
            <!-- <option value="next_month">Next Month</option>
            <option value="next_3_months">Next 3 Months</option>
            <option value="next_6_months">Next 6 Months</option>
            <option value="next_year">Next Year</option> -->
        </select>
        <h4 class="py-2 text-sm uppercase font-semibold text-gray-500 truncate">
            Period:
        </h4>
    </div>

    <div class="bg-white rounded-md shadow overflow-x-auto mt-4" id="printArea">
        <h2 class="text-center text-lg font-bold mb-4 mt-4">
            Profit and Loss Statement for the period of {{ startDate }} to
            {{ endDate }}
        </h2>
        <table class="w-full whitespace-nowrap">
            <thead class="bg-gray-50 border-b-2 border-gray-200">
                <tr class="text-left text-sm tracking-wide font-semibold">
                    <th scope="col" class="py-4 px-6 w-24">Account</th>
                    <th scope="col" class="py-4 px-6 w-32 text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    class="text-sm text-gray-700 hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="border-t px-6 py-4 whitespace-nowrap">
                        Operating Income
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap text-right">
                        -
                    </td>
                </tr>
                <tr
                    v-for="item in listGroupOperatingIncome"
                    class="text-sm text-gray-700 hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="border-t px-6 py-4 whitespace-nowrap pl-10">
                        {{ item.label }}
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap text-right">
                        {{ formatToTwoDecimal(item.debit) }}
                    </td>
                </tr>
                <tr
                    class="text-sm text-gray-700 hover:bg-gray-100 focus-within:bg-gray-100 bg-slate-100 font-bold"
                >
                    <td class="border-t px-6 py-4 whitespace-nowrap pl-10">
                        Total for Operating Income
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap text-right">
                        {{ formatToTwoDecimal(totalOperatingIncome) }}
                    </td>
                </tr>
                <tr
                    class="text-sm text-gray-700 hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="border-t px-6 py-4 whitespace-nowrap">
                        Operating Expense
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap text-right">
                        -
                    </td>
                </tr>
                <tr
                    v-for="item in listOperatingExpense"
                    class="text-sm text-gray-700 hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="border-t px-6 py-4 whitespace-nowrap pl-10">
                        {{ formatString(item.details) }}
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap text-right">
                        {{ formatToTwoDecimal(item.amount) }}
                    </td>
                </tr>
                <!-- <tr
                    class="text-sm text-gray-700 hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="border-t px-6 py-4 whitespace-nowrap pl-10">
                        Employee's Salary
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap text-right">
                        {{ formatToTwoDecimal(totalEmployeeSalary) }}
                    </td>
                </tr> -->
                <tr
                    class="text-sm text-gray-700 hover:bg-gray-100 focus-within:bg-gray-100 bg-slate-100 font-bold"
                >
                    <td class="border-t px-6 py-4 whitespace-nowrap pl-10">
                        Total for Operating Expense
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap text-right">
                        {{ formatToTwoDecimal(totalOperatingExpense) }}
                    </td>
                </tr>
                <tr
                    class="text-sm text-gray-700 hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="border-t px-6 py-4 whitespace-nowrap">
                        Non-operating Income
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap text-right">
                        -
                    </td>
                </tr>
                <tr
                    class="text-sm text-gray-700 hover:bg-gray-100 focus-within:bg-gray-100 bg-slate-100 font-bold"
                >
                    <td class="border-t px-6 py-4 whitespace-nowrap pl-10">
                        Total for Non-operating Income
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap text-right">
                        {{ formatToTwoDecimal(totalNonOperatingIncome) }}
                    </td>
                </tr>
                <tr
                    class="text-sm text-gray-700 hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="border-t px-6 py-4 whitespace-nowrap">
                        Non-operating Expense
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap text-right">
                        -
                    </td>
                </tr>
                <tr
                    class="text-sm text-gray-700 hover:bg-gray-100 focus-within:bg-gray-100 bg-slate-100 font-bold"
                >
                    <td class="border-t px-6 py-4 whitespace-nowrap pl-10">
                        Total for Non-operating Expense
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap text-right">
                        {{ formatToTwoDecimal(totalNonOperatingExpense) }}
                    </td>
                </tr>
                <tr
                    class="text-sm text-gray-700 hover:bg-gray-100 focus-within:bg-gray-100 bg-slate-200 font-bold"
                >
                    <td class="border-t px-6 py-4 whitespace-nowrap">
                        Net Profit/Loss
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap text-right">
                        {{ formatToTwoDecimal(netProfit) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import Layout from "../Shared/Layout";
import { Inertia } from "@inertiajs/inertia";
import { Head } from "@inertiajs/inertia-vue3";

export default {
    layout: Layout,
    props: {
        totalOperatingIncome: String,
        listGroupOperatingIncome: Object,
        totalEmployeeSalary: String,
        listOperatingExpense: Object,
        totalOperatingExpense: String,
        totalNonOperatingIncome: String,
        totalNonOperatingExpense: String,
        netProfit: String,
        selectedPeriod: String,
        startDate: String,
        endDate: String,
    },
    data() {
        const selectedPeriod = "this_year";
        return {
            page_title: "Profit and Loss statement",
            breadcrumbs: [
                { link: "/lawyer", label: "Lawyer" },
                {
                    link: "/lawyer/accounting-management",
                    label: "Accounting Management",
                },
                { link: null, label: "Profit and Loss" },
            ],
            // selectedPeriod: this.selectedPeriod || "this_year",
            selectedPeriod: selectedPeriod,
            // startDate: "",
            // endDate: "",
        };
    },
    methods: {
        printDiv() {
            const divId = "printArea";
            const printContent = document.getElementById(divId).innerHTML;
            const originalContent = document.body.innerHTML;

            document.body.innerHTML = printContent;
            window.print();
            document.body.innerHTML = originalContent;
            window.location.reload(); // Ensures everything returns to normal
        },
        formatToTwoDecimal(num) {
            return num.toFixed(2); // Formats the number to 2 decimal places
        },
        formatString(str) {
            return str
                .split("_") // Split by underscores
                .map((word) => word.charAt(0).toUpperCase() + word.slice(1)) // Capitalize the first letter of each word
                .join(" "); // Join the words with spaces
        },
        filterByPeriod() {
            // Send the selected period value to the backend
            Inertia.get(
                "/lawyer/accounting-management/profit",
                { period: this.selectedPeriod },
                {
                    preserveState: true,
                    replace: true,
                },
            );
        },
    },
};
</script>
