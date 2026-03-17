<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Measurement Sheet - {{ config('app.name') }}</title>

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
        }

        .top-left {
            position: absolute;
            left: 0;
            top: 0;
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

        .text-center {
            text-align: center;
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

        .area-title {
            margin-top: 12px;
            font-weight: 700;
            color: #1d5d41;
        }

        .footer {
            margin-top: 35px;
            font-size: 10px;
            text-align: center;
            color: #6b7280;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <div class="top-header">
        <div class="top-left">
            <h1>MEASUREMENT SHEET</h1>
            Ref : MS-{{ $project->id }}<br>
            Date : {{ now()->format('d-m-Y') }}
        </div>

        <div class="top-right">
            <h2>{{ config('app.name') }}</h2>
        </div>
    </div>

    <!-- CLIENT DETAILS -->
    {{-- <table class="party-table">
        <tr>
            <td class="party-col">
                <strong>Measurement By - {{ config('app.name') }}</strong><br>
                Phone - {{ config('company.phone') ?? '-' }}<br>
                Address - {{ config('company.address') ?? '-' }}
            </td>

            <td class="party-col">
                <strong>Measurement For - {{ optional($project->customer)->name ?? '-' }}</strong><br>
                Phone - {{ optional($project->customer)->phone ?? '-' }}<br>
                Address - {{ optional($project->customer)->address ?? '-' }}<br>
                Project ID - PROJ-{{ $project->id }}<br>
                Deadline -
                {{ $project->project_deadline ? \Carbon\Carbon::parse($project->project_deadline)->format('d-m-Y') : '-' }}
            </td>
        </tr>
    </table> --}}

    <!-- AREA WISE MEASUREMENTS -->
    @foreach ($grouped as $areaName => $rows)
        <div class="area-title">
            AREA : {{ strtoupper($areaName) }}
        </div>

        <table>
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="15%">Reference</th>
                    <th width="15%">Product</th>
                    <th width="8%">L</th>
                    <th width="8%">B</th>
                    <th width="8%">W</th>
                    <th width="8%">H</th>
                    <th width="10%">Unit</th>
                    <th width="8%">Qty</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($rows as $row)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $row->reference_name ?? '-' }}</td>
                        <td>{{ $row->product->name ?? '-' }}</td>
                        <td class="text-center">{{ $row->length ?? '-' }}</td>
                        <td class="text-center">{{ $row->breadth ?? '-' }}</td>
                        <td class="text-center">{{ $row->width ?? '-' }}</td>
                        <td class="text-center">{{ $row->height ?? '-' }}</td>
                        <td class="text-center">{{ $row->unit ?? '-' }}</td>
                        <td class="text-center">{{ $row->qty ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

    <!-- FOOTER -->
    {{-- <div class="footer">
        This is a computer generated measurement sheet.<br>
        Thank you for your business.
    </div> --}}

</body>

</html>
