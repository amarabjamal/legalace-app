<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice</title>

    <style type="text/css">
        @page {
            margin: 0px;
        }

        body {
            margin: 0px;
        }

        * {
            font-family: Verdana, Arial, sans-serif;
        }

        a {
            color: #fff;
            text-decoration: none;
        }

        table {
            font-size: x-small;
        }

        tfoot tr td {
            font-weight: bold;
            font-size: x-small;
        }

        .invoice table {
            padding: 2rem;
            width: 100%;
        }

        .invoice h3 {
            margin-left: 15px;
        }

        .information {
            background-color: #60A7A6;
            color: #FFF;
        }

        .information .logo {
            margin: 5px;
        }

        .information table {
            padding: 10px;
        }
    </style>

</head>
<body>

<div class="information">
    <table width="100%">
        <tr>
            <td align="left" style="width: 40%;">
                <h3>{{$invoice['client']['name'] }}</h3>
                <pre>{{$invoice['client']['address'] }}</pre>
            </td>
            <td align="center">
                <img src="{{ asset('images/app/logo.png') }}" alt="Logo" width="64" class="logo"/>
            </td>
            <td align="right" style="width: 40%;">

                <h3>{{$invoice['company']['name'] }}</h3>
                <pre>{{$invoice['company']['address'] }}</pre>
            </td>
        </tr>
    </table>
</div>

<div class="invoice">
    <h3>Invoice specification</h3>
    <table>
        <thead>
        <tr>
            <th align="left">Description</th>
            <th align="center">Quantity</th>
            <th align="right">Total</th>
        </tr>
        </thead>
        <tbody>
            @foreach ($items as $index => $item)
        <tr>
            <td>{{ $item['name'] }}</td>
            <td align="center">-</td>
            <td align="right">{{ $item['amount'] }}</td>
        </tr>
            @endforeach
        </tbody>

        <tfoot>
        <tr>
            <td colspan="1"></td>
            <td align="right">Total</td>
            <td align="right" class="gray">{{ $invoice['total'] }}</td>
        </tr>
        </tfoot>
    </table>
</div>

<div class="information" style="position: absolute; bottom: 0; width: 100%">
    <table width="100%">
        <tr>
            <td align="center">
                &copy; {{ date('Y') }} Legal Ace - All rights reserved.
            </td>
        </tr>
    </table>
</div>
</body>
</html>