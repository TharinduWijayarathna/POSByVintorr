<?php

namespace App\Http\Controllers;

use App\Exports\Reports\ProfitAndLossReportExport;
use App\Models\BusinessDetail;
use domain\Facades\ProfitAndLossReportFacade\ProfitAndLossReportFacade;
use Illuminate\Http\Request;
use Inertia\Inertia;
use PDF;
use Maatwebsite\Excel\Facades\Excel;

class ProfitAndLossReportController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        return Inertia::render('Reports/ProfitAndLoss/index');
    }


    /**
     * all
     *
     * @param  mixed $request
     * @return void
     */
    public function all(Request $request)
    {
        // Check if any filters are set
        $filtersSet = $request->has('search_data_currency') || $request->has('search_data_from_date');

        if (!$filtersSet) {
            // Return an appropriate response if no filters are set
            return response()->json([
                'sales_on_cash' => '0',
                'sales_on_credit' => '0',
                'positive_manual_transactions' => '0',
                'negative_manual_transactions' => '0',
                'returns' => '0',
                'payroll' => '0',
                'otherExpenses' => '0',
                'profit' => '0',
                'profit_percentage' => '0'
            ]);
        }

        // Fetch data using the facade
        $data = ProfitAndLossReportFacade::fetchData($request);

        // Return the fetched data as JSON
        return response()->json($data);
    }

    /**
     * print
     *
     * @param  mixed $request
     * @return void
     */

    public function print(Request $request)
    {

        // Validate the request
        $search_data_from_date = $request->input('search_data_from_date');
        $search_data_to_date = $request->input('search_data_to_date', now()->toDateString());
        $currency = $request->input('search_data_currency');
        $sales_on_cash = $request->input('sales_on_cash');
        $sales_on_credit = $request->input('sales_on_credit');
        $positive_manual_transactions = $request->input('positive_manual_transactions');
        $negative_manual_transactions = $request->input('negative_manual_transactions');
        $returns = $request->input('returns');
        $payroll = $request->input('payroll');
        $otherExpenses = $request->input('otherExpenses');
        $profit = $request->input('profit');
        $profit_percentage = $request->input('profit_percentage');

        // Get the business details
        $config = BusinessDetail::orderBy('id', 'desc')->first();

        // Prepare the data to be passed to the PDF view
        $data = [
            'search_data_from_date' => $search_data_from_date,
            'search_data_to_date' => $search_data_to_date,
            'sales_on_cash' => $sales_on_cash,
            'sales_on_credit' => $sales_on_credit,
            'positive_manual_transactions' => $positive_manual_transactions,
            'negative_manual_transactions' => $negative_manual_transactions,
            'returns' => $returns,
            'payroll' => $payroll,
            'otherExpenses' => $otherExpenses,
            'profit' => $profit,
            'profit_percentage' => $profit_percentage,
            'currency' => $currency,
            'config' => $config,
        ];

        // Generate the PDF
        $pdf = PDF::loadView('print.pages.Reports.ProfitAndLossReport.report', $data);
        return $pdf->stream("ProfitAndLossReport.pdf", ["Attachment" => false]);
    }


    /**
     * export
     *
     * @param  mixed $request
     * @return void
     */
    public function export(Request $request)
    {
        // Validate the request
        $search_data_from_date = $request->input('search_data_from_date');
        $search_data_to_date = $request->input('search_data_to_date', now()->toDateString());
        $currency = $request->input('search_data_currency');
        $sales_on_cash = $request->input('sales_on_cash');
        $sales_on_credit = $request->input('sales_on_credit');
        $positive_manual_transactions = $request->input('positive_manual_transactions');
        $negative_manual_transactions = $request->input('negative_manual_transactions');
        $returns = $request->input('returns');
        $payroll = $request->input('payroll');
        $otherExpenses = $request->input('otherExpenses');
        $profit = $request->input('profit');
        $profit_percentage = $request->input('profit_percentage');

        // Prepare the data to be passed to the excel view
        $data = [
            'search_data_from_date' => $search_data_from_date,
            'search_data_to_date' => $search_data_to_date,
            'sales_on_cash' => $sales_on_cash,
            'sales_on_credit' => $sales_on_credit,
            'positive_manual_transactions' => $positive_manual_transactions,
            'negative_manual_transactions' => $negative_manual_transactions,
            'returns' => $returns,
            'payroll' => $payroll,
            'otherExpenses' => $otherExpenses,
            'profit' => $profit,
            'profit_percentage' => number_format($profit_percentage, 2),
            'currency' => $currency,
        ];

        // Generate the Excel and store it in the storage then return the path
        $fileName = 'ProfitAndLossReport-' . date('Y-m-d') . '-' . time() . '.xlsx';
        $filePath = 'exports/Reports/' . $fileName;
        $profit_and_loss_export = new ProfitAndLossReportExport();
        Excel::store($profit_and_loss_export->export($data), $filePath, 'public');

        // Generate the URL to the stored file
        $path = asset('storage/' . $filePath);

        return response()->json(['path' => $path]);
    }
}
