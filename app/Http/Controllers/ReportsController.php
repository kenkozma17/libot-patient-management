<?php

namespace App\Http\Controllers;

use App\Exports\InvoicesExport;
use App\Exports\LoanExport;
use App\Models\Invoice;
use App\Models\PatientLoan;
use Carbon\Carbon;
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

        return Excel::download(new InvoicesExport($data),  $date . 'mir.xlsx');
    }

    public function generateLoanReport($loanId) {
        $loan = PatientLoan::with(['payments' => function($query) {
            $query->orderBy('payment_date', 'asc');
        }])->find($loanId);

        if($loan) {
            $totalAmountPaid = $loan->payments->sum('amount');
            $loanPayments = $loan->payments;

            $loanReport = [
                ['DETAILS OF LOAN PAYMENTS'],
                ['Name: ' . $loan->patient->full_name],
                ['AMOUNT OF LOAN: P' . number_format($loan->amount,2 ), 'MONTHLY AMORTIZATION: P' . $loan->monthly_payment],
                ['DURATION OF PAYMENT: ' . Carbon::parse($loan->start_date)->format('F Y') . ' - ' . Carbon::parse($loan->end_date)->format('F Y')],
                ['', '', '', '', ''],
                ['Duration of Month', 'Date of Payment', 'Due Date', 'OR Number', 'Amount Paid', 'Penalty', 'Remarks'],
            ];
            $loanReport[] = $loanPayments->map(function($payment) {
                return [
                    'duration_of_month' => Carbon::parse($payment->payment_date)->format('F Y'),
                    'date_of_payment' => Carbon::parse($payment->payment_date)->format('m/d/Y'),
                    'due_date' => '30th',
                    'or_number' => $payment->or_number,
                    'amount_paid' => number_format($payment->amount, 2),
                ];
            })->toArray();

            array_push($loanReport, ['', '', '', 'Total', number_format($totalAmountPaid, 2)]);

            return Excel::download(new LoanExport($loanReport),  $loan->patient->full_name . ' Loan Report.xlsx');
        }
    }

}
