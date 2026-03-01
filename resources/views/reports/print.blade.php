<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrowing Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            text-align: center;
            color: #2563EB;
        }
        .filters {
            margin: 20px 0;
            padding: 10px;
            background-color: #f3f4f6;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #2563EB;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .status {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-pending { background-color: #DBEAFE; color: #1E40AF; }
        .status-approved { background-color: #FEF3C7; color: #92400E; }
        .status-rejected { background-color: #FEE2E2; color: #991B1B; }
        .status-returned { background-color: #D1FAE5; color: #065F46; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <h1>Equipment Lending System - Borrowing Report</h1>
    
    <div class="filters">
        <strong>Report Filters:</strong><br>
        @if(isset($filters['start_date']) && isset($filters['end_date']))
        Date Range: {{ $filters['start_date'] }} to {{ $filters['end_date'] }}<br>
        @endif
        @if(isset($filters['status']))
        Status: {{ ucfirst($filters['status']) }}<br>
        @endif
        @if(isset($filters['user_id']))
        User ID: {{ $filters['user_id'] }}<br>
        @endif
        Generated on: {{ now()->format('F d, Y H:i:s') }}
    </div>

    <p><strong>Total Records:</strong> {{ $borrowings->count() }}</p>

    <table>
        <thead>
            <tr>
                <th>Borrower</th>
                <th>Equipment</th>
                <th>Category</th>
                <th>Borrow Date</th>
                <th>Return Date</th>
                <th>Status</th>
                <th>Approved By</th>
            </tr>
        </thead>
        <tbody>
            @foreach($borrowings as $borrowing)
            <tr>
                <td>{{ $borrowing->user->username }}</td>
                <td>{{ $borrowing->equipment->name }}</td>
                <td>{{ $borrowing->equipment->category->name }}</td>
                <td>{{ $borrowing->borrow_date->format('M d, Y') }}</td>
                <td>{{ $borrowing->actual_return_date ? $borrowing->actual_return_date->format('M d, Y') : '-' }}</td>
                <td>
                    <span class="status status-{{ $borrowing->status }}">
                        {{ ucfirst($borrowing->status) }}
                    </span>
                </td>
                <td>{{ $borrowing->approver ? $borrowing->approver->username : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button onclick="window.print()" style="background-color: #2563EB; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
            Print Report
        </button>
        <button onclick="window.close()" style="background-color: #6B7280; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
            Close
        </button>
    </div>
</body>
</html>
