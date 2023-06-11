<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quotation</title>

    <style type="text/css">
        * {
            font-family: Verdana, Arial, sans-serif;
        }
        table{
            font-size: x-small;
        }
        tfoot tr td{
            font-weight: bold;
            font-size: x-small;
        }

        .gray {
            background-color: lightgray
        }
    </style>
</head>
<body>

  <table width="100%">
    <tr>
        <td valign="top"><img src="{{ asset('images/app/logo.png')}}" width="150"/></td>
        <td align="right">
            <h3>Company Name</h3>
            <pre>
                Company representative name
                Company address
                Tax ID
                phone
                fax
            </pre>
        </td>
    </tr>

  </table>

  <table width="100%">
    <tr>
        <td>
            Client Name
        </td>
    </tr>
    <tr>
        <td>
            Client Address
        </td>
    </tr>
  </table>

  <table width="100%">
    <tr>
        <td>
            Dear sirs,
        </td>
    </tr>
    <tr>
        <td>Matter :</td>
    </tr>
  </table>

  <table width="100%">
    <tr>
        <td>
        We refer to the above matter.
        </td>
    </tr>
    <tr>
        <td>
        Thank you for considering us for the solicitor works in the above matter. We are pleased to provide our fees structure as follows:-
        </td>
    </tr>
  </table>

  <table width="100%">
    <tr>
        <td>
            <h3>1. SCOPE OF SERVICES & LEGAL FEES</h3>
        </td>
    </tr>
  </table>

  <table width="100%">
    <thead style="background-color: lightgray;">
      <tr>
        <th>#</th>
        <th>Work Description</th>
        <th>Fee (RM)</th>
      </tr>
    </thead>
    <tbody>
        @foreach ($workdescriptions as $index => $workdescription)
            <tr>
                <th scope="row">{{ $index + 1}}</th>
                <td>{{ $workdescription['description'] }}</td>
                <td align="right">{{ $workdescription['fee'] }}</td>
            </tr>
        @endforeach
    </tbody>

    <tfoot>
        <tr>
            <td colspan="1"></td>
            <td align="right">Subtotal</td>
            <td align="right">0</td>
        </tr>
        <tr>
            <td colspan="1"></td>
            <td align="right">Tax</td>
            <td align="right">0</td>
        </tr>
        <tr>
            <td colspan="1"></td>
            <td align="right">Total</td>
            <td align="right" class="gray">0</td>
        </tr>
    </tfoot>
  </table>

  <table width="100%">
    <tr>
        <td>
            <p>The aboce fees structure is quoted based on our current understanding of the case. Final fees charged are subjected to the actual type and amount of work done.</p>
        </td>
    </tr>
  </table>

  <table width="100%">
    <tr>
        <td>
            <h3>2. DISBURSEMENTS</h3>
        </td>
    </tr>
    <tr>
        <td>
            <p>The above quotation does not include any disbursements. Disbursements will be charged on incurred basis.</p>
        </td>
    </tr>
  </table>

  <table width="100%">
    <tr>
        <td>
            <h3>3. TAX</h3>
        </td>
    </tr>
    <tr>
        <td>
            <p>As of the date of this latter, no service tax will be imposed on the legal fees.</p>
        </td>
    </tr>
  </table>

  <table width="100%">
    <tr>
        <td>
            <h3>4. INITIAL DEPOSIT</h3>
        </td>
    </tr>
    <tr>
        <td>
            <p>As a policy of our firm, we will request a sum of deposit towards our fees, and disbursements prior to the commencment of work. For this matter, kindly remit a sum of <strong>RM{{ $deposit_amount }}</strong>  as inital deposit to our clients' accounts as follows:-</p>
        </td>
    </tr>
  </table>


</body>
</html>