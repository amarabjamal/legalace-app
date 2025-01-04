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
                <!--  -->
                <tr
                    class="text-sm text-gray-700 hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="border-t px-6 py-4 whitespace-nowrap">
                        Cash flow from Operating activities
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
                        {{ formatToTwoDecimal(operatingCash) }}
                    </td>
                </tr>
                <tr
                    class="text-sm text-gray-700 hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="border-t px-6 py-4 whitespace-nowrap pl-10">
                        Bank
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap text-right">
                        {{ formatToTwoDecimal(operatingBank) }}
                    </td>
                </tr>
                <tr
                    class="text-sm text-gray-700 hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="border-t px-6 py-4 whitespace-nowrap">
                        Total cash from Operating activities
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap text-right">
                        {{ formatToTwoDecimal(operatingTotal) }}
                    </td>
                </tr>

                <!--  -->
                <tr
                    class="text-sm text-gray-700 hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="border-t px-6 py-4 whitespace-nowrap">
                        Cash flow from Investing activities
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
                    class="text-sm text-gray-700 hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="border-t px-6 py-4 whitespace-nowrap pl-10">
                        Cash
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap text-right">
                        {{ formatToTwoDecimal(InvestingCash) }}
                    </td>
                </tr>
                <tr
                    class="text-sm text-gray-700 hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="border-t px-6 py-4 whitespace-nowrap pl-10">
                        Bank
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap text-right">
                        {{ formatToTwoDecimal(InvestingBank) }}
                    </td>
                </tr>
                <tr
                    class="text-sm text-gray-700 hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="border-t px-6 py-4 whitespace-nowrap">
                        Total cash from Investing activities
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap text-right">
                        {{ formatToTwoDecimal(InvestingTotal) }}
                    </td>
                </tr>

                <!--  -->
                <tr
                    class="text-sm text-gray-700 hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="border-t px-6 py-4 whitespace-nowrap">
                        Cash flow from Financing activities
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap text-right">
                        {{ formatToTwoDecimal(acc_payable) }}
                    </td>
                </tr>
                <tr
                    class="text-sm text-gray-700 hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="border-t px-6 py-4 whitespace-nowrap pl-10">
                        Cash
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap text-right">
                        {{ formatToTwoDecimal(financingCash) }}
                    </td>
                </tr>
                <tr
                    class="text-sm text-gray-700 hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="border-t px-6 py-4 whitespace-nowrap pl-10">
                        Bank
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap text-right">
                        {{ formatToTwoDecimal(financingBank) }}
                    </td>
                </tr>
                <tr
                    class="text-sm text-gray-700 hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="border-t px-6 py-4 whitespace-nowrap">
                        Total cash from Financing activities
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap text-right">
                        {{ formatToTwoDecimal(financingTotal) }}
                    </td>
                </tr>

                <tr
                    class="text-sm text-gray-700 hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="border-t px-6 py-4 whitespace-nowrap">
                        Ending Cash balance
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap text-right">
                        {{ formatToTwoDecimal(endingCashBalance) }}
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
        InvestingCash: String,
        InvestingBank: String,
        assetAcquisition: String,
        InvestingTotal: String,
        operatingCash: String,
        operatingBank: String,
        operatingTotal: String,
        financingCash: String,
        financingBank: String,
        financingTotal: String,
        endingCashBalance: String,
    },
    data() {
        return {
            page_title: "Cash Flow Statement",
            breadcrumbs: [
                {
                    link: "/lawyer/accounting-management",
                    label: "Accounting Management",
                },
                { link: null, label: "Cash Flow Statement" },
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
            if (num == null) {
                return "0.00";
            } else {
                return num.toFixed(2); // Formats the number to 2 decimal places
            }
        },
    },
};
</script>
