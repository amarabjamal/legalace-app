<template>
    <Head :title="page_title" />

    <page-heading :page_title="page_title" :breadcrumbs="breadcrumbs" />
    <div
        class="flex flex-row-reverse space-x-2 space-x-reverse items-center justify-start"
    >
        <Link class="btn btn-primary" @click="printDiv()" as="button"
            >Print/Save</Link
        >
    </div>

    <div class="bg-white rounded-md shadow overflow-x-auto mt-4" id="printArea">
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
    },
    data() {
        return {
            page_title: "Balance Sheet",
            breadcrumbs: [
                {
                    link: "/lawyer/accounting-management",
                    label: "Accounting Management",
                },
                { link: null, label: "Balance Sheet" },
            ],
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
    },
};
</script>
