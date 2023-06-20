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
                {{-- <img src="{{asset('images/app/logo.png')}}" alt="company logo" class="logo overflow-hidden max-h-16 max-w-16"> --}}
            </div>
            <div class="receipt-details w-1/2 flex flex-col items-end">
                <h2 class="mb-4 text-3xl font-semibold uppercase text-gray-900">
                    Invoice
                </h2>
                <dl class="flex flex-col">
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
                            Case No. Ref. :
                        </dt>
                        <dd class="w-32 text-sm text-gray-600">
                            {{ $case_file['number'] }}
                        </dd>
                    </div>
                    <div class="flex text-right">
                        <dt class="w-28 font-semibold text-gray-700">
                            Invoice Date :
                        </dt>
                        <dd class="w-32 text-sm text-gray-600">
                            {{ $invoice['invoice_date'] }}
                        </dd>
                    </div>
                    <div class="flex text-right">
                        <dt class="w-28 font-semibold text-gray-700">
                            Due Date :
                        </dt>
                        <dd class="w-32 text-sm text-gray-600">
                            {{ $invoice['due_date'] }}
                        </dd>
                    </div>
                </dl>
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
                    <div class="whitespace-pre-wrap">{{$invoice['company']['address'] }}</div>
                </div>  
            </div>
            <div class="flex flex-col w-1/2">
                <div class="font-semibold text-gray-900">
                    To
                </div>
                <div class="flex flex-col mt-3 text-gray-500">
                    <div class="font-medium text-gray-900 mb-2">
                        {{$case_file['client']['name'] }}
                    </div>
                    <div class="whitespace-pre-wrap">{{$case_file['client']['address'] }}</div>
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

        @if ($invoice['notes'] !== null)
            <div class="flex flex-col mt-10 w-full">
                <div class="text-gray-900 font-medium mb-3">Notes</div>
                <div class="text-gray-600 text-sm whitespace-pre-wrap">{{ $invoice['notes'] }}</div>
            </div>    
        @endif

        <div class="mt-16 w-full text-gray-500 text-xs p-2 border-t">
            This is an electronically generated invoice. No signature is required.
            <div class="text-center mt-4 font-bold">Generated by Legal Ace &copy; 2023</div>
        </div>
    </div>
</body>
</html>