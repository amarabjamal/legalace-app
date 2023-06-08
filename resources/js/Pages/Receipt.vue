<template>
    <!-- <div class="overflow-hidden" style="width: 210mm; height: 297mm; padding: 25mm 19mm"> -->
    <div class="overflow-hidden">
        <div class="flex flex-wrap">
            <div class="flex flex-wrap w-full text-sm">
                <div class="company-details w-1/2">
                    <img :src="'/images/app/logo.png'" alt="company logo" class="logo overflow-hidden max-h-24 max-w-24">
                </div>
                <div class="receipt-details w-1/2 flex flex-col space-y-4 items-end">
                    <h2 class="text-3xl font-semibold uppercase text-gray-900">
                        Receipt
                    </h2>
                    <dl class="flex flex-col space-y-1">
                        <div class="flex text-right">
                            <dt class="w-28 font-semibold text-gray-700">
                                Receipt No. :
                            </dt>
                            <dd class="w-32 text-sm text-gray-600">
                                123445786
                            </dd>
                        </div>
                        <div class="flex text-right">
                            <dt class="w-28 font-semibold text-gray-700">
                                Ref. Invoice No. :
                            </dt>
                            <dd class="w-32 text-sm text-gray-600">
                                INV-00023
                            </dd>
                        </div>
                        <div class="flex text-right">
                            <dt class="w-28 font-semibold text-gray-700">
                                Ref. Case No. :
                            </dt>
                            <dd class="w-32 text-sm text-gray-600">
                                AAJ00004
                            </dd>
                        </div>
                        <div class="flex text-right">
                            <dt class="w-28 font-semibold text-gray-700">
                                Date :
                            </dt>
                            <dd class="w-32 text-sm text-gray-600">
                                12/02/2023
                            </dd>
                        </div>
                    </dl>
                    <div class="py-1.5 px-4 text-sm font-semibold uppercase tracking-wider text-green-600 border-2 border-green-600 rounded-lg">
                        Paid
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap w-full mt-6 mb-6 pt-4 text-sm">
                <div class="flex flex-col flex-wrap w-full lg:w-1/2">
                    <div class="font-semibold text-gray-900">
                        From
                    </div>
                    <div class="flex flex-col mt-3 text-gray-500">
                        <div class="font-medium text-gray-900 mb-2">
                            invoice.company.name
                        </div>
                        <div>
                            invoice.company.address
                        </div>
                    </div>  
                </div>
                <div class="flex flex-col flex-wrap w-full lg:w-1/2">
                    <div class="font-semibold text-gray-900">
                        To
                    </div>
                    <div class="flex flex-col mt-3 text-gray-500">
                        <div class="font-medium text-gray-900 mb-2">
                            invoice.client.name
                        </div>
                        <div>
                            invoice.client.address
                        </div>
                    </div>  
                </div>
            </div>

            <table class="w-full mt-4 whitespace-nowrap text-left text-sm leading-6">
                <thead class="bg-gray-900 border border-gray-900 text-gray-100">
                    <tr>
                        <th class="w-12 p-3">No.</th>
                        <th class="p-3">Item</th>
                        <th class="w-40 p-3 text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="max-w-0 px-3 py-5 align-top border border-gray-900">#</td>
                        <td class="max-w-0 px-3 py-5 align-top border border-gray-900">
                            <div class="truncate font-medium text-gray-900">
                                item.name
                            </div>
                            <div class="truncate text-gray-500">
                                item.description
                            </div>
                        </td>
                        <td class="px-3 py-5 align-top text-right tabular-nums border border-gray-900">item.amount</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th scope="row" colspan="2" class="border border-gray-900 p-3 text-right font-normal text-gray-700">Subtotal</th>
                        <td colspan="1" class="border border-gray-900 p-3 text-right text-gray-900 tabular-nums">invoice.subtotal</td>
                    </tr>
                    <tr>
                        <th scope="row" colspan="2" class="border border-gray-900 p-3 text-right font-normal text-gray-700">Tax (0%)</th>
                        <td colspan="1" class="border border-gray-900 p-3 text-right tabular-nums text-gray-900">invoice.tax</td>
                    </tr>
                    <tr>
                        <th scope="row" colspan="2" class=" border border-gray-900 p-3 text-right font-semibold text-gray-900">Total</th>
                        <td colspan="1" class="border border-gray-900 p-3 text-right font-semibold tabular-nums text-gray-900">invoice.total</td>
                    </tr>
                </tfoot>
            </table>

            <div class="w-full mb-6 text-sm text-gray-800">
                <span class="text-red-500">*</span>Paid through invoice.payment.method on invoice.payment.date
            </div>

            <div class="flex flex-col mt-2 w-full">
                <div class="text-gray-900 font-medium mb-3">Notes</div>
                <div class="text-gray-600 text-sm whitespace-pre-wrap">
                    receipt.notes
                </div>
            </div>
        </div>
    </div>
</template>