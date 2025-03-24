<?php

namespace App\Http\Controllers;

use App\Exports\InvoicesExport;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Reports/ReportsList', [

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function generateReport(Request $request)
    {
        $month = $request->month;
        $year = $request->year;
        $date = $year . '-' . strtolower($month) . '-';
        $invoices = Invoice::
            with(['patientVisit.patient'])
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->orderBy('created_at', 'asc')
            ->get();

        // Group invoices by date
        $groupedInvoices = $invoices->groupBy(function ($invoice) {
            return $invoice->created_at->format('Y-m-d');
        });

        $summary = $groupedInvoices->map(function ($invoices, $date) {
            $income = $invoices->filter(function ($invoice) {
                return $invoice->is_paid == 1
                    && $invoice->patientVisit->patient_type === 'Walk In';
            })->sum('amount_payable');
            $accounts_receivable = $invoices->where('is_paid', '==', 0)->sum('amount_payable');
            $sendOutPayments = $invoices->filter(function ($invoice) {
                return $invoice->is_paid == 1
                    && $invoice->patientVisit->patient_type === 'Send Out';
            })->sum('amount_payable');

            $total = $income + $accounts_receivable + $sendOutPayments;
            return [
                'date' => $date,
                'or_number' => $invoices->first()->or_number . ' - ' . $invoices->last()->or_number,
                'remarks' => '',
                'income' => $income,
                'loan_payments' => 0,
                'send_out_payments' => $sendOutPayments,
                'accounts_receivable' => $accounts_receivable,
                'total' => $total,
            ];
        });

        $data = array_merge([
            ['Date', 'OR Number', 'Remarks', 'Income', 'Loan Payments', 'Send Out Payments', 'Accounts Receivable', 'Total']
        ], $summary->values()->toArray());

        dd($data);

        return Excel::download(new InvoicesExport($data),  $date . 'mir.xlsx');
    }

}
