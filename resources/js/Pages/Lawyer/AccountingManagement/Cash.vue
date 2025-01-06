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
                    <td class="border-t px-6 py-4 whitespace-nowrap pl-10">
                        Net Profit
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap text-right">
                        {{ formatToTwoDecimal(netProfit) }}
                    </td>
                </tr>
                <tr
                    class="text-sm text-gray-700 hover:bg-gray-100 focus-within:bg-gray-100 bg-slate-100 font-bold"
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

                <!-- <tr
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
                </tr> -->
                <tr
                    class="text-sm text-gray-700 hover:bg-gray-100 focus-within:bg-gray-100 bg-slate-100 font-bold"
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
                        <!-- {{ formatToTwoDecimal(acc_payable) }} -->
                        -
                    </td>
                </tr>
                <tr
                    class="text-sm text-gray-700 hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="border-t px-6 py-4 whitespace-nowrap pl-10">
                        Owner' Contribution - Cash
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap text-right">
                        {{ formatToTwoDecimal(financingCash) }}
                    </td>
                </tr>
                <tr
                    class="text-sm text-gray-700 hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="border-t px-6 py-4 whitespace-nowrap pl-10">
                        Owner' Contribution - Bank
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap text-right">
                        {{ formatToTwoDecimal(financingBank) }}
                    </td>
                </tr>
                <tr
                    class="text-sm text-gray-700 hover:bg-gray-100 focus-within:bg-gray-100 bg-slate-100 font-bold"
                >
                    <td class="border-t px-6 py-4 whitespace-nowrap">
                        Total cash from Financing activities
                    </td>
                    <td class="border-t px-6 py-4 whitespace-nowrap text-right">
                        {{ formatToTwoDecimal(financingTotal) }}
                    </td>
                </tr>

                <tr
                    class="text-sm text-gray-700 hover:bg-gray-100 focus-within:bg-gray-100 bg-slate-200 font-bold"
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
        netProfit: String,
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

        /*************  ✨ Codeium Command ⭐  *************/
        /**
         * Prints the content of the element with the id of "printArea" by opening a new window,
         * extracting the styles applied to the rows or other elements, writing a new document with
         * the extracted styles and print content, printing the new window, closing the print window
         * after printing, and restoring the original content.
         */
        /******  736df33d-5ca6-45a5-ab5c-c1e3cd8a3d6b  *******/
        printDiv2() {
            const divId = "printArea";
            const printContent = document.getElementById(divId).innerHTML;
            const originalContent = document.body.innerHTML;

            // Create a style element with the necessary Tailwind CSS classes
            const style = document.createElement("style");
            style.innerHTML = `
        .text-sm { font-size: 0.875rem; line-height: 1.25rem; }
        .text-gray-700 { color: #374151; }
        .hover\\:bg-gray-100:hover { background-color: #f3f4f6; }
        .focus-within\\:bg-gray-100:focus-within { background-color: #f3f4f6; }
        .bg-slate-100 { background-color: #f1f5f9; }
        .bg-slate-200 { background-color: #e2e8f0; }
        .font-bold { font-weight: 700; }
        .border-t { border-top-width: 1px; }
        .px-6 { padding-left: 1.5rem; padding-right: 1.5rem; }
        .py-4 { padding-top: 1rem; padding-bottom: 1rem; }
        .whitespace-nowrap { white-space: nowrap; }
        .text-right { text-align: right; }
    `;

            // Create a new window for printing
            const printWindow = window.open("", "", "height=600,width=800");
            printWindow.document.write("<html><head><title>Print</title>");
            printWindow.document.write("</head><body>");
            printWindow.document.write(style.outerHTML); // Include the styles
            printWindow.document.write(printContent); // Include the content
            printWindow.document.write("</body></html>");
            printWindow.document.close();
            printWindow.print();
            printWindow.close();

            // Restore the original content
            document.body.innerHTML = originalContent;
            window.location.reload(); // Ensures everything returns to normal
        },
    },
};
</script>
