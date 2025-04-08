<?php

namespace App\Http\Controllers;

use App\Exports\InvoicesExport;
use App\Exports\LoanExport;
use App\Models\Invoice;
use App\Models\LoanPayment;
use App\Models\PatientLoan;
use App\Models\PatientVisit;
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
        $walkInVisits = PatientVisit::whereMonth('visit_date', $month)
            ->whereYear('visit_date', $year)
            ->where('patient_type', 'Walk In')
            ->with('invoice')
            ->whereHas('invoice', function($query) {
                $query->where('is_paid', 1);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        $sendOutVisits = PatientVisit::whereMonth('visit_date', $month)
            ->whereYear('visit_date', $year)
            ->where('patient_type', 'Send Out')
            ->with('invoice')
            ->whereHas('invoice', function($query) {
                $query->where('is_paid', 1);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        $loanPayments = LoanPayment::whereMonth('payment_date', $month)
            ->whereYear('payment_date', $year)
            ->orderBy('payment_date', 'asc')
            ->get();

        // Group invoices by date
        $combinedItems = $walkInVisits->concat($sendOutVisits)->concat($loanPayments);

        $groupedItems = $combinedItems->groupBy(function ($item) {
            return Carbon::parse($item->visit_date ?? $item->payment_date)->format('Y-m-d');
        })->map(function ($itemsPerDate) {
            return $itemsPerDate->groupBy(function ($item) {
                return $item->visit_date ? 'visits' : 'loans';
            });
        })->sortKeys();
        // $visitsAndLoans = array_merge($groupedLoanPayments, $groupedVisits);

        $exportData = [];
        $totalWalkInIncome = 0;
        $totalSendOutIncome = 0;
        $totalLoanPayments = 0;
        $groupedItems->each(function ($items, $date) use (&$exportData, &$totalWalkInIncome, &$totalLoanPayments, &$totalSendOutIncome) {
            if(isset($items['visits'])) {
                $visits = $items['visits'];
                $walkInIncome = $visits->map(function($visit) {
                    if($visit->patient_type === 'Walk In') {
                        return $visit->invoice->amount_payable;
                    }
                });

                $sendOutIncome = $visits->map(function($visit) {
                    if($visit->patient_type === 'Send Out') {
                        return $visit->invoice->amount_payable;
                    }
                });

                $totalWalkInIncome = $totalWalkInIncome + $walkInIncome->sum();
                $totalSendOutIncome = $totalSendOutIncome + $sendOutIncome->sum();
                $exportData[] = [
                    'date' => $date,
                    'or_number' => $visits->first()->invoice->or_number . ' - ' . $visits->last()->invoice->or_number,
                    'remarks' => '',
                    'income' => number_format($walkInIncome->sum(), 2),
                    'loan_payments' => '',
                    'send_out_payments' => number_format($sendOutIncome->sum(), 2),
                    'accounts_receivable' => '',
                    'total' => number_format($walkInIncome->sum() + $sendOutIncome->sum(), 2),
                ];
            }

            if(isset($items['loans'])) {
                $loanPayments = $items['loans'];
                $totalLoanPayments += $loanPayments->sum('amount');
                $exportData[] = [
                    'date' => $date,
                    'or_number' => $loanPayments->first()->or_number . ' - ' . $loanPayments->last()->or_number,
                    'remarks' => '',
                    'income' => '',
                    'loan_payments' => number_format($loanPayments->sum('amount'), 2),
                    'send_out_payments' => '',
                    'accounts_receivable' => '',
                    'total' => number_format($loanPayments->sum('amount'), 2),
                ];
            }
        });
        $grandTotal = number_format($totalWalkInIncome + $totalLoanPayments + $totalSendOutIncome, 2);
        $lastLine = [
            '',
            '',
            'Total',
            number_format($totalWalkInIncome, 2),
            number_format($totalLoanPayments, 2),
            number_format($totalSendOutIncome, 2),
            '',
            $grandTotal
        ];

        $data = array_merge([
            ['Date', 'OR Number', 'Remarks', 'Income', 'Loan Payments', 'Send Out Payments', 'Accounts Receivable', 'Total']
        ], [$exportData, $lastLine]);

        return Excel::download(new InvoicesExport($data),  $date . 'mir.xlsx');
    }

    public function generateDailyCollectionReport(Request $request) {
        $date = $request->date;

        // Fetch first and last OR number for the date for income (walk ins)
        $visits = PatientVisit::whereDate('visit_date', $date)
            ->where('patient_type', 'Walk In')
            ->whereHas('invoice', function($query) {
                $query->where('is_paid', 1);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        // Get Loan Payments for today
        $loanPayments = LoanPayment::whereDate('payment_date', $date)
            ->get();

        $totalIncome = 0;
        $firstLine = [];
        if($visits->isNotEmpty()) {
            $firstVisit = $visits->first();
            $lastVisit = $visits->last();

            // Get income from walk ins
            $income = $visits->map(callback: function($visit) {
                return $visit->invoice->amount_payable;
            });
            $totalIncome = $income->sum();

            $firstLine = [
                $firstVisit->invoice->or_number . ' - ' . $lastVisit->invoice->or_number,
                number_format($totalIncome, 2),
                '',
                '',
                number_format($totalIncome, 2)
            ];
        }

        $totalLoanPayments = 0;
        $secondLine = [];
        if($loanPayments->isNotEmpty()) {
            $totalLoanPayments = $loanPayments->sum('amount');
            $secondLine = [
                $loanPayments->first()->or_number . ' - ' . $loanPayments->last()->or_number,
                '',
                number_format($totalLoanPayments, 2),
                '',
                number_format($totalLoanPayments, 2),
            ];
        }

        $grandTotal = $totalIncome + $totalLoanPayments;
        $lastLine = [
            'Total',
            number_format($totalIncome, 2),
            number_format($totalLoanPayments, 2),
            '',
            number_format($grandTotal, 2)
        ];

        $data = array_merge([
            ['DAILY CASH COLLECTION REPORT'],
            ['Particulars/OR Number', 'Income', 'Loan Receivables', 'Account Receivables', 'Total']
        ], [$firstLine, $secondLine, $lastLine]);

        return Excel::download(new InvoicesExport($data),  $date . '-daily-cash-collection.xlsx');
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
