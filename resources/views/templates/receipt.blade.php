<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
    <style>
        {{ file_get_contents(public_path('css/app.css')) }}

        body {
            font-family: Arial, Helvetica, sans-serif;
        }
    </style>
</head>
<body>
    <div class="flex flex-wrap w-full">
        <div class="flex flex-wrap w-full text-sm">
            <div class="company-details w-1/2">
                <img src="{{asset('images/app/logo.png')}}" alt="company logo" class="logo overflow-hidden max-h-16 max-w-16">
            </div>
            <div class="receipt-details w-1/2 flex flex-col items-end">
                <h2 class="mb-4 text-3xl font-semibold uppercase text-gray-900">
                    Receipt
                </h2>
                <dl class="flex flex-col">
                    <div class="flex text-right">
                        <dt class="w-28 font-semibold text-gray-700">
                            Receipt No. :
                        </dt>
                        <dd class="w-32 text-sm text-gray-600">
                            {{ $receipt['number'] }}
                        </dd>
                    </div>
                    <div class="flex text-right">
                        <dt class="w-28 font-semibold text-gray-700">
                            Invoice No. :
                        </dt>
                        <dd class="w-32 text-sm text-gray-600">
                            {{ $invoice['number'] }}
                        </dd>
                    </div>
                    <div class="flex text-right">
                        <dt class="w-28 font-semibold text-gray-700">
                            Case No. :
                        </dt>
                        <dd class="w-32 text-sm text-gray-600">
                            {{ $case_file['number'] }}
                        </dd>
                    </div>
                    <div class="flex text-right">
                        <dt class="w-28 font-semibold text-gray-700">
                            Date :
                        </dt>
                        <dd class="w-32 text-sm text-gray-600">
                            {{ $receipt['date'] }}
                        </dd>
                    </div>
                </dl>
                <div class="mt-4 py-1.5 px-4 text-sm font-semibold uppercase tracking-wider text-green-600 border-2 border-green-600 rounded-lg">
                    Paid
                </div>
            </div>
        </div>

        <div class="flex flex-wrap w-full mt-6 mb-6 pt-4 text-sm">
            <div class="flex flex-col w-1/2">
                <div class="font-semibold text-gray-900">
                    From
                </div>
                <div class="flex flex-col mt-3 text-gray-500">
                    <div class="font-medium text-gray-900 mb-2">
                        {{$invoice['company']['name'] }}
                    </div>
                    <div>
                        {{$invoice['company']['address'] }}
                    </div>
                </div>  
            </div>
            <div class="flex flex-col w-1/2">
                <div class="font-semibold text-gray-900">
                    To
                </div>
                <div class="flex flex-col mt-3 text-gray-500">
                    <div class="font-medium text-gray-900 mb-2">
                        {{$invoice['client']['name'] }}
                    </div>
                    <div>
                        {{$invoice['client']['address'] }}
                    </div>
                </div>  
            </div>
        </div>

        <table class="w-full mt-4 whitespace-nowrap text-left text-sm leading-6">
            <thead class="bg-gray-900 border border-gray-900 text-gray-100">
                <tr>
                    <th class="w-12 px-3 py-2">No.</th>
                    <th class="px-3 py-2">Item</th>
                    <th class="w-40 px-3 py-2 text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $index => $item)
                <tr>
                    <td class="max-w-0 px-3 py-2 align-top border border-gray-900">{{ $index + 1 }}</td>
                    <td class="max-w-0 px-3 py-2 align-top border border-gray-900">
                        <div class="truncate font-medium text-gray-900">
                            {{ $item['name'] }}
                        </div>
                        <div class="truncate text-gray-500">
                            {{ $item['description'] }}
                        </div>
                    </td>
                    <td class="px-3 py-2 align-top text-right tabular-nums border border-gray-900">
                        {{ $item['amount'] }}
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th scope="row" colspan="2" class="border border-gray-900 px-3 py-2 text-right font-normal text-gray-700">
                        Subtotal
                    </th>
                    <td colspan="1" class="border border-gray-900 px-3 py-2 text-right text-gray-900 tabular-nums">
                        {{ $invoice['subtotal'] }}
                    </td>
                </tr>
                <tr>
                    <th scope="row" colspan="2" class="border border-gray-900 px-3 py-2 text-right font-normal text-gray-700">
                        Tax (0%)
                    </th>
                    <td colspan="1" class="border border-gray-900 px-3 py-2 text-right tabular-nums text-gray-900">
                        {{ $invoice['tax'] }}
                    </td>
                </tr>
                <tr>
                    <th scope="row" colspan="2" class=" border border-gray-900 px-3 py-2 text-right font-semibold text-gray-900">
                        Total
                    </th>
                    <td colspan="1" class="border border-gray-900 px-3 py-2 text-right font-semibold tabular-nums text-gray-900">
                        {{ $invoice['total'] }}
                    </td>
                </tr>
            </tfoot>
        </table>

        <div class="w-full mt-1 mb-6 text-sm text-gray-800">
            <span class="text-red-500">*</span>Paid through {{ $payment['method'] }} on {{ $payment['date'] }}
        </div>

        @if ($receipt['notes'] !== null)
            <div class="flex flex-col mt-2 w-full">
                <div class="text-gray-900 font-medium mb-3">Notes</div>
                <div class="text-gray-600 text-sm whitespace-pre-wrap">{{ $receipt['notes'] }}</div>
            </div>    
        @endif
    </div>
</body>
</html>