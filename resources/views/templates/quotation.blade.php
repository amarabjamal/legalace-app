<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quotation</title>
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
                    Quotation
                </h2>
                <dl class="flex flex-col">
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
                            Date :
                        </dt>
                        <dd class="w-32 text-sm text-gray-600">
                            {{ $date }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="flex flex-wrap w-full mt-6 mb-4 pt-4 text-sm">
            <div class="flex flex-col w-1/2 pr-1">
                <div class="font-semibold text-gray-900">
                    From
                </div>
                <div class="flex flex-col mt-3 text-gray-500">
                    <div class="font-medium text-gray-900 mb-2">
                        {{$case_file['company']['name'] }}
                    </div>
                    <div class="whitespace-pre-wrap">{{$case_file['company']['address'] }}</div>
                </div>  
            </div>
            <div class="flex flex-col w-1/2 pl-1">
                <div class="font-semibold text-gray-900">
                    To
                </div>
                <div class="flex flex-col mt-3 text-gray-500">
                    <div class="font-medium text-gray-900 mb-2">
                        {{$case_file['client']['name'] }}
                    </div>
                    <div class="whitespace-pre-wrap">{{$case_file['client']['address'] }} </div>
                </div>  
            </div>
        </div>

        <div class="mt-8">
            <h2 class="mb-6 text-lg font-bold border-b border-gray-500 pb-2 text-start">Matter: {{ $case_file['matter'] }}</h2>
            <p class="mb-4">We refer to the above matter.</p>
            <p>Thank you for considering us for the solicitor works in the above matter. We are pleased to provide our fees structure as follows:-</p>
        </div>

        <div class="mt-6">
            <h3 class="mb-4 font-bold uppercase">1. Scope of services and legal fees</h3>
            <table class="w-full mb-4 whitespace-nowrap text-left text-sm leading-6">
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
                                {{ $item['description'] }}
                            </div>
                        </td>
                        <td class="px-3 py-2 align-top text-right tabular-nums border border-gray-900">
                            {{ $item['fee'] }}
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
                            {{ $subtotal }}
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" colspan="2" class="border border-gray-900 px-3 py-2 text-right font-normal text-gray-700">
                            Tax (0%)
                        </th>
                        <td colspan="1" class="border border-gray-900 px-3 py-2 text-right tabular-nums text-gray-900">
                            {{ $tax }}
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" colspan="2" class=" border border-gray-900 px-3 py-2 text-right font-semibold text-gray-900">
                            Total
                        </th>
                        <td colspan="1" class="border border-gray-900 px-3 py-2 text-right font-semibold tabular-nums text-gray-900">
                            {{ $total }}
                        </td>
                    </tr>
                </tfoot>
            </table>
    
            <p>The aboce fees structure is quoted based on our current understanding of the case. Final fees charged are subjected to the actual type and amount of work done.</p>
        </div>

        
        <div class="mt-6">
            <h3 class="mb-4 font-bold uppercase">2. DISBURSEMENTS</h3>
            <p>The above quotation does not include any disbursements. Disbursements will be charged on incurred basis.</p>
        </div>

        <div class="mt-6">
            <h3 class="mb-4 font-bold uppercase">3. TAX</h3>
            <p>As of the date of this letter, no service tax will be imposed on the legal fees.</p>
        </div>

        <div class="mt-6">
            <h3 class="mb-4 font-bold uppercase">4. INITIAL DEPOSIT</h3>
            <p>As a policy of our firm, we will request a sum of deposit towards our fees, and disbursements prior to the commencment of work. For this matter, kindly remit a sum of <strong>{{ $deposit_amount }}</strong>  as inital deposit to our clients' accounts as follow:-</p>

            <div class="mt-6 w-full mx-16">
                <dl class="flex flex-col flex-wrap">
                    <div class="flex mb-2 ">
                        <dt class="w-52 text-gray-500">Account Name: </dt>
                        <dd class="w-full text-gray-700 font-semibold">{{ $bank_account['account_name'] }}</dd>
                    </div>

                    <div class="flex mb-2">
                        <dt class="w-52 text-gray-500">Bank Name:</dt>
                        <dd class="w-full text-gray-700 font-semibold">{{ $bank_account['bank_name'] }}</dd>
                    </div>

                    <div class="flex mb-2">
                        <dt class="w-52 text-gray-500">Account Number:</dt>
                        <dd class="w-full text-gray-700 font-semibold">{{ $bank_account['account_number'] }}</dd>
                    </div>

                    <div class="flex mb-2">
                        <dt class="w-52 text-gray-500">Bank Address:</dt>
                        <dd class="w-full whitespace-pre-wrap text-gray-700 font-semibold">{{ $bank_account['bank_address'] }}</dd>
                    </div>

                    <div class="flex mb-2">
                        <dt class="w-52 text-gray-500">SWIFT Code:</dt>
                        <dd class="w-full text-gray-700 font-semibold">{{ $bank_account['swift_code'] }}</dd>
                    </div>

                </dl>
            </div>
        </div>

        <div class="w-full mt-20 pt-10 border-t border-dashed border-gray-500">
            <div class="w-full mb-14 flex justify-center">
                <h3 class="uppercase font-bold text-lg text-gray-800">Acceptance</h3>
            </div>

            <div class="mb-10">
                I, <div class="w-56 inline-block border-b border-gray-600" ></div> (NRIC No.: <div class="w-36 inline-block border-b border-gray-600" ></div>​) hereby confirm that I have engaged your firm to act for me in this matter for the above services. I have read and agreed to the above terms and conditions and I undertake to pay the legal fees and disbursements incurred from time to time upon request.
            </div>

            <div class="">
                <div class="w-56 border-b border-gray-600 mb-2"></div>
                <div class="font-semibold">Name: </div>
                <div class="font-semibold">Date: </div>
            </div>
        </div>
        <div class="mt-16 w-full text-gray-500 text-xs p-2 border-t">
            This is an electronically generated quotation. No signature is required.
            <div class="text-center mt-4 font-bold">Generated by Legal Ace &copy; 2023</div>
        </div>
    </div>
</body>
</html>