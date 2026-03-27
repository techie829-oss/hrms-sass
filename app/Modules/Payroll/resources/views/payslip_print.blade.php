<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payslip - {{ $payslip->payslip_number }}</title>
    <style>
        body { font-family: 'Inter', sans-serif; color: #1f2937; margin: 0; padding: 20px; font-size: 12px; line-height: 1.5; }
        .container { max-width: 800px; margin: 0 auto; border: 1px solid #e5e7eb; padding: 40px; background: #fff; }
        .header { display: flex; justify-content: space-between; align-items: start; border-bottom: 2px solid #3b82f6; padding-bottom: 20px; margin-bottom: 30px; }
        .company-info h1 { margin: 0; color: #3b82f6; font-size: 24px; letter-spacing: -0.025em; }
        .company-info p { margin: 2px 0; opacity: 0.7; }
        .payslip-title { text-align: right; }
        .payslip-title h2 { margin: 0; font-size: 20px; color: #111827; }
        .payslip-title p { margin: 4px 0; font-weight: bold; color: #3b82f6; }
        
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-bottom: 30px; }
        .info-block h3 { font-size: 10px; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; margin-bottom: 8px; border-bottom: 1px solid #f3f4f6; padding-bottom: 4px; }
        .info-row { display: flex; justify-content: space-between; margin-bottom: 4px; }
        .info-label { font-weight: bold; width: 120px; }
        .info-value { flex: 1; text-align: right; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th { text-align: left; background: #f9fafb; padding: 10px; border-bottom: 1px solid #e5e7eb; font-size: 10px; text-transform: uppercase; color: #6b7280; }
        td { padding: 10px; border-bottom: 1px solid #f3f4f6; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        
        .totals-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0; border: 1px solid #e5e7eb; }
        .total-section { padding: 0; }
        .total-row { display: flex; justify-content: space-between; padding: 10px 15px; border-bottom: 1px solid #f3f4f6; }
        .total-row:last-child { border-bottom: none; }
        .grand-total { background: #eff6ff; color: #1e40af; border-top: 1px solid #bfdbfe; }
        
        .footer { margin-top: 50px; text-align: center; border-top: 1px solid #e5e7eb; padding-top: 20px; font-size: 10px; opacity: 0.5; }
        
        @media print {
            body { padding: 0; }
            .container { border: none; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="max-width: 800px; margin: 0 auto 10px auto; text-align: right;">
        <button onclick="window.print()" style="padding: 8px 16px; background: #3b82f6; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Print Payslip</button>
    </div>

    <div class="container">
        <div class="header">
            <div class="company-info">
                <h1>{{ saas_tenant('name') ?? 'HRMS SaaS' }}</h1>
                <p>Corporate Office Address</p>
                <p>support@hrms.com | +91 1234567890</p>
            </div>
            <div class="payslip-title">
                <h2>PAYSLIP</h2>
                <p>{{ \Carbon\Carbon::createFromDate($payslip->year, $payslip->month, 1)->format('F Y') }}</p>
            </div>
        </div>

        <div class="info-grid">
            <div class="info-block">
                <h3>Employee Details</h3>
                <div class="info-row"><span class="info-label">Name:</span> <span class="info-value">{{ $payslip->employee->full_name }}</span></div>
                <div class="info-row"><span class="info-label">Employee ID:</span> <span class="info-value">{{ $payslip->employee->employee_id }}</span></div>
                <div class="info-row"><span class="info-label">Designation:</span> <span class="info-value">{{ $payslip->employee->designation?->name ?? 'N/A' }}</span></div>
                <div class="info-row"><span class="info-label">Department:</span> <span class="info-value">{{ $payslip->employee->department?->name ?? 'N/A' }}</span></div>
            </div>
            <div class="info-block">
                <h3>Attendance Summary</h3>
                <div class="info-row"><span class="info-label">Working Days:</span> <span class="info-value">{{ $payslip->working_days }}</span></div>
                <div class="info-row"><span class="info-label">Present Days:</span> <span class="info-value">{{ $payslip->present_days }}</span></div>
                <div class="info-row"><span class="info-label">LO P Days:</span> <span class="info-value text-error">{{ $payslip->absent_days }}</span></div>
                <div class="info-row"><span class="info-label">Leave Days:</span> <span class="info-value">{{ $payslip->leave_days }}</span></div>
            </div>
        </div>

        <div style="display: flex; gap: 20px;">
            <div style="flex: 1;">
                <table>
                    <thead>
                        <tr>
                            <th>Earnings</th>
                            <th class="text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payslip->earnings_breakdown as $item)
                        <tr>
                            <td>{{ $item['component_code'] }}</td>
                            <td class="text-right">₹{{ number_format($item['amount'], 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="flex: 1;">
                <table>
                    <thead>
                        <tr>
                            <th>Deductions</th>
                            <th class="text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payslip->deductions_breakdown as $item)
                        <tr>
                            <td>{{ $item['component_code'] }} {{ isset($item['days']) ? '('.$item['days'].'d)' : '' }}</td>
                            <td class="text-right">₹{{ number_format($item['amount'], 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="totals-grid">
            <div class="total-section">
                <div class="total-row"><span class="font-bold">Total Earnings:</span> <span class="font-bold">₹{{ number_format($payslip->gross_earnings, 2) }}</span></div>
            </div>
            <div class="total-section">
                <div class="total-row"><span class="font-bold">Total Deductions:</span> <span class="font-bold">₹{{ number_format($payslip->total_deductions, 2) }}</span></div>
            </div>
        </div>
        
        <div class="total-row grand-total" style="margin-top: -1px; padding: 15px;">
            <span class="font-bold" style="font-size: 14px;">NET PAYABLE:</span>
            <span class="font-bold" style="font-size: 18px;">₹{{ number_format($payslip->net_salary, 2) }}</span>
        </div>

        <div style="margin-top: 30px;">
            <p style="font-size: 10px;"><strong>Net Payable (In Words):</strong> {{ ucwords((new NumberFormatter('en_IN', NumberFormatter::SPELLOUT))->format($payslip->net_salary)) }} Rupees Only</p>
        </div>

        <div class="footer">
            <p>This is a computer generated payslip and does not require a signature.</p>
            <p>Generated on {{ now()->format('M d, Y H:i') }}</p>
        </div>
    </div>
</body>
</html>
