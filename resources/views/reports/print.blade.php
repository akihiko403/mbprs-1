<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $reportHeading }}</title>
    <style>
        body {
            margin: 0;
            padding: 10mm 4mm 8mm;
            font-family: Arial, sans-serif;
            color: #000;
            background: #fff;
        }
        .report-sheet-header {
            margin-bottom: 18px;
        }
        .report-sheet-header-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
        }
        .report-sheet-logo {
            width: 58px;
            height: 58px;
            object-fit: contain;
            flex: 0 0 58px;
        }
        .report-sheet-heading {
            text-align: center;
        }
        .report-sheet-title {
            margin: 0;
            font-size: 18px;
            font-weight: 400;
            line-height: 1.2;
        }
        .report-sheet-subtitle {
            margin: 4px 0 0;
            font-size: 12px;
            line-height: 1.2;
        }
        .report-sheet-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .report-sheet-table th,
        .report-sheet-table td {
            border: 1px solid #000;
            padding: 3px 4px;
            font-size: 9px;
            font-weight: 400;
            line-height: 1.15;
            text-align: left;
            vertical-align: top;
            word-break: break-word;
            overflow-wrap: anywhere;
        }
        .report-sheet-table th {
            text-transform: uppercase;
        }
        .report-sheet-table th:first-child,
        .report-sheet-table td:first-child {
            white-space: nowrap;
            word-break: normal;
            overflow-wrap: normal;
        }
        @page {
            size: landscape;
            margin: 6mm 4mm;
        }
    </style>
</head>
<body onload="window.print()">
    <div class="report-sheet-header">
        <div class="report-sheet-header-wrap">
            @if(!empty($reportLogoUrl))
                <img class="report-sheet-logo" src="{{ $reportLogoUrl }}" alt="System Logo">
            @endif
            <div class="report-sheet-heading">
                <h1 class="report-sheet-title">{{ $reportHeading }}</h1>
                <div class="report-sheet-subtitle">{{ $reportSubheading }}</div>
            </div>
        </div>
    </div>

    <table class="report-sheet-table">
        <colgroup>
            <col style="width:10%;">
            <col style="width:14%;">
            <col style="width:10%;">
            <col style="width:13%;">
            <col style="width:12%;">
            <col style="width:12%;">
            <col style="width:12%;">
            <col style="width:9%;">
            <col style="width:8%;">
        </colgroup>
        <thead>
            <tr>
                <th>Permit ID</th>
                <th>Owner</th>
                <th>Building Type</th>
                <th>Building Category</th>
                <th>Barangay</th>
                <th>City/Municipality</th>
                <th>Province</th>
                <th>Status</th>
                <th>Created</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $record)
                <tr>
                    <td>{{ $record->permit_id }}</td>
                    <td>{{ $record->owner_full_name }}</td>
                    <td>{{ $record->buildingType?->name }}</td>
                    <td>{{ $record->buildingCategory?->name }}</td>
                    <td>{{ $record->barangay }}</td>
                    <td>{{ $record->city_municipality }}</td>
                    <td>{{ $record->province }}</td>
                    <td>{{ $record->status }}</td>
                    <td>{{ $record->created_at?->format('M d, Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">No records matched the selected report filters.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
