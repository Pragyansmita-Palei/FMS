<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Receipt</title>

    <style>
        body{
            font-family: DejaVu Sans, sans-serif;
            font-size:12px;
            color:#000;
        }
        .title{
            text-align:center;
            font-size:18px;
            font-weight:bold;
            margin-bottom:30px;
        }
        .row{
            width:100%;
            margin-bottom:10px;
        }
        .left{
            float:left;
            width:50%;
        }
        .right{
            float:right;
            width:50%;
            text-align:right;
        }
        .clear{
            clear:both;
        }
        table{
            width:100%;
            border-collapse:collapse;
            margin-top:25px;
        }
        th,td{
            padding:8px 6px;
            border-bottom:1px solid #ccc;
        }
        th{
            text-align:left;
            font-weight:bold;
        }
        .total{
            text-align:right;
            margin-top:20px;
            font-weight:bold;
        }
        .words{
            text-align:right;
            margin-top:6px;
            font-size:11px;
        }
    </style>
</head>
<body>

<div class="title">Payment Receipt</div>

<div class="row">
   <div class="left">
        <strong>From:</strong><br>
        {{ $project->customer->name ?? '-' }}<br>
        Phone: {{ $project->customer->phone ?? '-' }}
    </div>

    <div class="right">
      <strong>Order ID :</strong> {{ $project->order_id }}<br>
        <strong>Payment Date :</strong>
        {{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}
    </div>
</div>

<div class="clear"></div>

<br>

<div class="row">
    <div class="left">
        <strong>To:</strong><br>
        {{ config('app.name') }}
               <br>

    </div>
</div>

<div class="clear"></div>

<table>
    <thead>
        <tr>
            <th>Description</th>
            <th>Payment Mode</th>
            <th style="text-align:right">Amount</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                {{ $payment->remarks ?? 'N/A' }}
            </td>
            <td>
                {{ $payment->payment_mode ?? '-' }}
            </td>
            <td style="text-align:right">
                ₹ {{ number_format($payment->amount,2) }}
            </td>
        </tr>
    </tbody>
</table>

<div class="total">
    Total Amount Received : ₹ {{ number_format($payment->amount, 2) }}
</div>

<div class="words">
    In Words:
    {{ \App\Support\NumberToWords::convert($payment->amount) }} only.
</div>

</body>
</html>
