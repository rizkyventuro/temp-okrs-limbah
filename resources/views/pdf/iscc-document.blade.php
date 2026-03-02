<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            width: 100%;
            height: 100%;
            background: #fff;
            font-family: "Times New Roman", serif;
            font-size: 13px;
            line-height: 1.4;
            color: #000;
        }

        .page {
            width: 100%;
            max-width: 210mm;
            margin: 0 auto;
            padding: 40px 32px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            border: 1px solid #000;
            padding: 6px;
            vertical-align: top;
        }

        .title {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .section-title {
            background: #eee;
            font-weight: bold;
        }

        ol {
            padding-left: 20px;
        }

        ol li {
            margin-bottom: 4px;
        }

        .footer {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
            font-size: 11px;
        }
    </style>
</head>

<body>

    <div class="page">

        <p class="title">
            ISCC CORSIA Self-Declaration for Points of Origin
            Generating Used Cooking Oil (UCO)
        </p>

        <table>
            <tr>
                <td colspan="2" class="section-title">
                    Information about the Point of Origin
                </td>
            </tr>

            <tr>
                <td width="35%">Name</td>
                <td>{{ $iscc['poo_name'] }}</td>
            </tr>

            <tr>
                <td>Street address</td>
                <td>{{ $iscc['poo_street'] }}</td>
            </tr>

            <tr>
                <td>Postcode, location</td>
                <td>{{ $iscc['poo_city'] }}</td>
            </tr>

            <tr>
                <td>Country</td>
                <td>{{ $iscc['poo_country'] }}</td>
            </tr>

            <tr>
                <td>Phone number</td>
                <td>{{ $iscc['poo_phone'] }}</td>
            </tr>

            <tr>
                <td>The amount of UCO</td>
                <td><b>{{ $iscc['uco_amount'] }}</b></td>
            </tr>

            <tr>
                <td>Recipient of the UCO</td>
                <td><b>{{ $iscc['recipient'] }}</b></td>
            </tr>
        </table>

        <br>

        <p><b>
                By signing this self-declaration, the signatory acknowledges:
            </b></p>

        <ol>
            <li>UCO refers to used cooking oil.</li>
            <li>Deliveries consist entirely of UCO.</li>
            <li>Waste substances intentionally modified are excluded.</li>
            <li>Applicable waste legislation applies.</li>
            <li>Supplier maintains documentation.</li>
            <li>Auditors may inspect facilities.</li>
            <li>Declaration may be forwarded confidentially.</li>
            <li>ISCC may publish exclusion if requirements fail.</li>
        </ol>

        <table style="margin-top:40px">
            <tr>
                <td width="33%">Place, date</td>
                <td width="33%">Full name and function of signatory</td>
                <td>Signature</td>
            </tr>

            <tr>
                <td height="60">{{ $iscc['place_date'] }}</td>
                <td>{{ $iscc['signatory'] }}</td>
                <td>Digital</td>
            </tr>
        </table>

        <div class="footer">
            <div>
                <b>ISCC System GmbH</b><br>
                Version 2.1 – April 2024
            </div>

            <div>
                Copyright &copy; ISCC System GmbH
            </div>
        </div>

    </div>

</body>

</html>
