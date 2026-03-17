<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Quotation - {{ config('app.name') }}</title>

    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 11px;
            color: #1f2933;
            margin: 0;
            padding: 0;
        }

        .top-header {
            position: relative;
            border-bottom: 2px solid #238254;
            padding-bottom: 10px;
            margin-bottom: 20px;
            height: 55px;
            /* important */
        }

        .top-left {
            position: absolute;
            left: 0;
            top: 0;
            padding-bottom: 10px;
            width: 60%;
        }

        .top-right {
            position: absolute;
            right: 0;
            top: 0;
            width: 40%;
            text-align: right;
        }

        .top-left h1 {
            margin: 0;
            font-size: 20px;
            color: #238254;
            font-weight: 700;
        }


        .top-right h2 {
            margin: 0;
            font-size: 18px;
            color: #1d5d41;
            font-weight: 700;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
        }

        th,
        td {
            border: 1px solid #cfe8dc;
            padding: 5px;
            font-size: 10px;
        }

        th {
            background: #eaf6f1;
            color: #1d5d41;
            text-align: center;
        }

        td {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .area-total-box {
            margin-top: 5px;
            padding: 5px 8px;
            text-align: right;
            font-weight: 700;
            color: #1d5d41;
            border-top: 1px dashed #cfe8dc;
        }

        .party-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        .party-col {
            width: 50%;
            padding: 8px 10px;
            line-height: 1.6;
            vertical-align: top;
            border: none;
        }

        .signature {
            margin-top: 25px;
            text-align: right;
            font-weight: 700;
        }
    </style>
</head>

<body>

    <div class="top-header">
        <div class="top-left">
            <h1>QUOTATION</h1>
            <div>
                {{-- Quotation version : {{ $quotation->version ?? '-' }}<br> --}}
                Date : {{ now()->format('d-m-Y') }}
            </div>
        </div>

        <div class="top-right">
            <h2>{{ config('app.name') }}</h2>
        </div>
    </div>

    <table class="party-table">
        <tr>
            <td class="party-col">
                <strong>Quotation By - {{ config('app.name') }}</strong><br>
                Phone - {{ config('company.phone') ?? '-' }}<br>
                Address - {{ config('company.address') ?? '-' }}
            </td>

            <td class="party-col">
                <strong>Quotation To - {{ optional($project->customer)->name ?? '-' }}</strong><br>
                Phone - {{ optional($project->customer)->phone ?? '-' }}<br>
                Address - {{ optional($project->customer)->address ?? '-' }}
            </td>
        </tr>
    </table>

    {{-- AREA WISE MATERIAL TABLE --}}
    @foreach ($materialsByArea as $area => $refs)
        @php $areaTotal = 0; @endphp

        <div style="margin-top:12px; font-weight:700; color:#1d5d41;">
            AREA : {{ strtoupper($area) }}
        </div>

        @foreach ($refs as $ref => $items)
            <div style="margin-top:8px; font-weight:700;">
                Reference : {{ $ref }}
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Unit</th>
                        <th>Qty</th>
                        <th>MRP</th>
                        <th>Tax %</th>
                        <th>Discount %</th>
                        <th>Sale Rate</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        @php
                            $areaTotal += $item->total ?? 0;
                        @endphp
                        <tr>
                            <td>{{ $item->product?->name }}</td>
                            <td>{{ $item->unit }}</td>
                            <td>{{ $item->qty }}</td>
                            <td class="text-right">{{ number_format($item->rate, 2) }}</td>
                            <td class="text-center">{{ number_format($item->tax_rate, 2) }}</td>
                            <td class="text-center">{{ number_format($item->discount, 2) }}</td>
                            <td class="text-right">{{ number_format($item->sale_rate, 2) }}</td>
                            <td class="text-right">{{ number_format($item->total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach

        {{-- <div class="area-total-box">
            Area Total : ₹ {{ number_format($areaTotal, 2) }}
        </div> --}}
    @endforeach

    {{-- FETCH TOTALS DIRECTLY FROM QUOTATIONS TABLE --}}
    @php
        $subTotal = $quotation->sub_total ?? 0;
        $discount = $quotation->total_discount?? 0;
        $tax = $quotation->total_tax  ?? 0;
        $netTotal = $quotation->grand_total ?? 0;
    @endphp

    <table class="party-table">
        <tr>
          <td class="party-col">
{{-- <strong>Terms & Conditions</strong><br> --}}

@if(isset($term) && $term->description)
<div style="margin-bottom:20px;">
    <h4>Terms & Conditions</h4>

    @foreach(explode("\n", $term->description) as $line)
        @if(trim($line) != '')
            <p style="margin:3px 0;">{{ $line }}</p>
        @endif
    @endforeach

</div>
@endif

</td>

            <td class="party-col">
                <table style="width:100%; border-collapse:collapse;">
                    <tr>
                        <td class="text-right"><strong>Subtotal</strong></td>
                        <td class="text-right">₹ {{ number_format($subTotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="text-right"><strong>Discount</strong></td>
                        <td class="text-right">₹ {{ number_format($discount, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="text-right"><strong>Tax</strong></td>
                        <td class="text-right">₹ {{ number_format($tax, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="text-right"><strong>Total</strong></td>
                        <td class="text-right"><strong>₹ {{ number_format($netTotal, 2) }}</strong></td>
                    </tr>
                </table>

                <div class="signature">
                    Authorized Signature
                </div>
            </td>
        </tr>
    </table>

</body>

</html>
