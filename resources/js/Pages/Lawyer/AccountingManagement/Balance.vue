<template>
    <Head :title="page_title" />

    <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs" />
    <p class="py-2 text-l font-semibold">
        Period: {{ startDate }} - {{ endDate }}
    </p>
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
            style="
                appearance: none;
                background-image: url('data:image/svg+xml;utf8,<svg xmlns=&quot;http://www.w3.org/2000/svg&quot; width=&quot;14&quot; height=&quot;12&quot; viewBox=&quot;0 0 14 12&quot;>&lt;polyline points=&quot;1 7 7 12 13 7&quot;/&gt;&lt;/svg&gt;');
                background-position: right 10px top 50%;
                background-repeat: no-repeat;
                padding-right: 20px;
            "
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
            Balance Sheet Statement for the period of {{ startDate }} to
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
                        Current Asset
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap text-right">
                        -
                    </td>
                </tr>
                <tr
                    class="text-sm text-gray-700 hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="border-t px-6 py-4 whitespace-nowrap pl-10">
                        Cash
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap text-right">
                        {{ formatToTwoDecimal(cash) }}
                    </td>
                </tr>
                <tr
                    class="text-sm text-gray-700 hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="border-t px-6 py-4 whitespace-nowrap pl-10">
                        Bank
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap text-right">
                        {{ formatToTwoDecimal(bank) }}
                    </td>
                </tr>
                <tr
                    class="text-sm text-gray-700 hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="border-t px-6 py-4 whitespace-nowrap pl-10">
                        Account receivable
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap text-right">
                        {{ formatToTwoDecimal(acc_receivable) }}
                    </td>
                </tr>
                <tr
                    class="text-sm text-gray-700 hover:bg-gray-100 focus-within:bg-gray-100 bg-slate-100 font-bold"
                >
                    <td class="border-t px-6 py-4 whitespace-nowrap">
                        Total For Current Asset
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap text-right">
                        {{ formatToTwoDecimal(total_curr_asset) }}
                    </td>
                </tr>

                <!-- Other Asset -->
                <tr
                    class="text-sm text-gray-700 hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="border-t px-6 py-4 whitespace-nowrap">
                        Other Asset
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap text-right">
                        -
                    </td>
                </tr>
                <tr
                    class="text-sm text-gray-700 hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="border-t px-6 py-4 whitespace-nowrap pl-10">
                        Asset Acquisition
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap text-right">
                        {{ formatToTwoDecimal(assetAcquisition) }}
                    </td>
                </tr>
                <tr
                    class="text-sm text-gray-700 hover:bg-gray-100 focus-within:bg-gray-100 bg-slate-100 font-bold"
                >
                    <td class="border-t px-6 py-4 whitespace-nowrap pl-10">
                        Total For Other Asset
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap text-right">
                        {{ formatToTwoDecimal(assetAcquisition) }}
                    </td>
                </tr>
                <tr
                    class="text-sm text-gray-700 hover:bg-gray-100 focus-within:bg-gray-100 bg-slate-100 font-bold"
                >
                    <td class="border-t px-6 py-4 whitespace-nowrap pl-10">
                        Total For All Asset
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap text-right">
                        {{
                            formatToTwoDecimal(
                                assetAcquisition + total_curr_asset,
                            )
                        }}
                    </td>
                </tr>

                <!-- LIABILITIES -->
                <tr
                    class="text-sm text-gray-700 hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="border-t px-6 py-4 whitespace-nowrap">
                        Current Liabilities
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap text-right">
                        -
                    </td>
                </tr>
                <tr
                    class="text-sm text-gray-700 hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="border-t px-6 py-4 whitespace-nowrap pl-10">
                        Account Payable
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap text-right">
                        {{ formatToTwoDecimal(acc_payable) }}
                    </td>
                </tr>
                <tr
                    class="text-sm text-gray-700 hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="border-t px-6 py-4 whitespace-nowrap pl-10">
                        Long term liabilities
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap text-right">
                        0.00
                    </td>
                </tr>

                <tr
                    class="text-sm text-gray-700 hover:bg-gray-100 focus-within:bg-gray-100 bg-slate-100 font-bold"
                >
                    <td class="border-t px-6 py-4 whitespace-nowrap">
                        Total For Current Liabilities
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap text-right">
                        {{ formatToTwoDecimal(total_curr_liabilities) }}
                    </td>
                </tr>

                <!-- EQUITIES -->
                <tr
                    class="text-sm text-gray-700 hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="border-t px-6 py-4 whitespace-nowrap">
                        Equities
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap text-right">
                        -
                    </td>
                </tr>
                <tr
                    class="text-sm text-gray-700 hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="border-t px-6 py-4 whitespace-nowrap pl-10">
                        Owner's Contribution
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap text-right">
                        {{ formatToTwoDecimal(equities) }}
                    </td>
                </tr>
                <tr
                    class="text-sm text-gray-700 hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="border-t px-6 py-4 whitespace-nowrap pl-10">
                        Current Year Earnings
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap text-right">
                        {{ formatToTwoDecimal(profit_and_loss["netProfit"]) }}
                    </td>
                </tr>

                <tr
                    class="text-sm text-gray-700 hover:bg-gray-100 focus-within:bg-gray-100 bg-slate-100 font-bold"
                >
                    <td class="border-t px-6 py-4 whitespace-nowrap">
                        Total For Equities
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap text-right">
                        {{ formatToTwoDecimal(total_equities) }}
                    </td>
                </tr>

                <tr
                    class="text-sm text-gray-700 hover:bg-gray-100 focus-within:bg-gray-100 bg-slate-200 font-bold"
                >
                    <td class="border-t px-6 py-4 whitespace-nowrap">
                        Total For Liabilities and Equities
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap text-right">
                        {{ formatToTwoDecimal(total_liabities_and_equities) }}
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
        cash: String,
        bank: String,
        acc_receivable: String,
        total_curr_asset: String,
        acc_payable: String,
        total_curr_liabilities: String,
        equities: String,
        profit_and_loss: String,
        total_equities: String,
        total_liabities_and_equities: String,
        assetAcquisition: String,
        selectedPeriod: String,
        startDate: String,
        endDate: String,
    },
    data() {
        const selectedPeriod = "this_year";
        return {
            page_title: "Balance Sheet",
            breadcrumbs: [
                { link: "/lawyer", label: "Lawyer" },
                {
                    link: "/lawyer/accounting-management",
                    label: "Accounting Management",
                },
                { link: null, label: "Balance Sheet" },
            ],
            selectedPeriod: selectedPeriod,
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
                "/lawyer/accounting-management/balance",
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
