<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $sale->slip_no }}</title>
    <style>
        @page { size: 80mm auto; margin: 4mm; }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
            width: 100%;
        }
        .receipt { max-width: 500px; margin: 0 auto; }
        .receipt h1 { font-size: 15px; margin: 0 0 2px; text-align: center; }
        .center { text-align: center; }
        .meta { margin: 6px 0; }
        .meta div { display: flex; justify-content: space-between; }
        table { width: 100%; border-collapse: collapse; margin: 6px 0; }
        th { border-bottom: 1px dashed #000; text-align: left; padding: 2px 0; }
        td { padding: 2px 0; vertical-align: top; }
        .right { text-align: right; }
        .totals td { border-top: 1px dashed #000; }
        .grand { font-weight: bold; font-size: 14px; }
        .divider { border-top: 1px dashed #000; margin: 6px 0; }
        .note { font-size: 11px; text-align: center; margin-top: 10px; }
        .print-btn { display: block; margin: 12px auto; padding: 8px 22px; font-family: Arial; font-size: 13px; }
        @media print { .print-btn { display: none; } }
    </style>
</head>
<body>
    <div class="receipt">
        <h1>{{ get_setting('website_name') ?: config('app.name') }}</h1>
        <div class="center">{{ get_setting('address', '') }}</div>
        <div class="center">{{ get_setting('phone', '') }}</div>

        <div class="divider"></div>
        <table>
            <tr><td>Invoice No</td><td class="right"><strong>{{ $sale->slip_no }}</strong></td></tr>
            <tr><td>Date</td><td class="right">{{ date('d-m-Y h:i A', strtotime($sale->created_at)) }}</td></tr>
            <tr><td>Customer</td><td class="right">{{ $sale->customer ? $sale->customer->name : '' }}</td></tr>
            @if($sale->customer && $sale->customer->mobile)
                <tr><td>Mobile</td><td class="right">{{ $sale->customer->mobile }}</td></tr>
            @endif
            <tr><td>Cashier</td><td class="right">{{ $cashier ? $cashier->name : '' }}</td></tr>
        </table>

        <div class="divider"></div>
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th class="right">Rate</th>
                    <th class="right">Qty</th>
                    <th class="right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->items as $item)
                    <tr>
                        <td>{{ $item->name }}{{ $item->variant ? ' (' . $item->variant . ')' : '' }}</td>
                        <td class="right">{{ number_format($item->price, 2) }}</td>
                        <td class="right">{{ rtrim(rtrim(number_format($item->qty, 2, '.', ''), '0'), '.') }}</td>
                        <td class="right">{{ number_format($item->total, 2) }}</td>
                    </tr>
                    @if($item->discount_amount > 0)
                        <tr>
                            <td class="right" colspan="4">Discount: -{{ number_format($item->discount_amount, 2) }}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>

        <table class="totals">
            <tr><td>Subtotal</td><td class="right">{{ number_format($sale->subtotal, 2) }}</td></tr>
            @if($sale->discount_amount > 0)
                <tr><td>Discount</td><td class="right">-{{ number_format($sale->discount_amount, 2) }}</td></tr>
            @endif
            @if($sale->other_charges > 0)
                <tr><td>Other Charges</td><td class="right">{{ number_format($sale->other_charges, 2) }}</td></tr>
            @endif
            @if($sale->round_off != 0)
                <tr><td>Round Off</td><td class="right">{{ number_format($sale->round_off, 2) }}</td></tr>
            @endif
            <tr class="grand"><td>Grand Total</td><td class="right">{{ number_format($sale->grand_total, 2) }}</td></tr>
            <tr><td>Paid ({{ $sale->payment_method }})</td><td class="right">{{ number_format($sale->paid_amount, 2) }}</td></tr>
            @if($sale->change_return > 0)
                <tr><td>Change Return</td><td class="right">{{ number_format($sale->change_return, 2) }}</td></tr>
            @endif
            <tr><td>Previous Due</td><td class="right">{{ number_format(max(0, $previous_sales_total), 2) }}</td></tr>
            <tr><td>Customer Due</td><td class="right">{{ number_format(max(0, $customer_due), 2) }}</td></tr>
        </table>

        @if($sale->note)
            <div>Note: {{ $sale->note }}</div>
        @endif

        <div class="divider"></div>
        <div class="note">Thank you for your purchase!</div>
        <button class="print-btn" onclick="window.print()">Print Receipt</button>
    </div>
    <script>window.print();</script>
</body>
</html>
