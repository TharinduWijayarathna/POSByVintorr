<?php

namespace App\Http\Controllers;

use App\Models\BusinessDetail;
use domain\Facades\QuotationFacade\QuotationFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use PDF;

class PublicViewQuotationController extends Controller
{
    public function viewQuotationPage(string $quotation_key)
    {
        $quotation_id = QuotationFacade::getPublicQuotation($quotation_key);
        $response['quotation'] = QuotationFacade::get($quotation_id);
        $response['quotation_items'] = QuotationFacade::allItems($quotation_id);
        $response['created_at'] = $response['quotation']['created_at'];
        $response['print_type'] = "quotation";
        $response['config'] = BusinessDetail::orderBy('id', 'desc')->first();
        $response['custom_fields'] = QuotationFacade::parametersGetForPrint($quotation_id);
        $response['footer_fields'] = QuotationFacade::footerParametersGetForPrint($quotation_id);
        $pdf = PDF::loadView('print.pages.Quotation.quotation', $response)->setPaper('a4');

        $pdfPath = 'quotations/' . $quotation_key . '.pdf';
        Storage::disk('public')->put($pdfPath, $pdf->output());
        $pdfUrl = Storage::url($pdfPath);

        $businessDetails = $response['config'];
        return Inertia::render('PublicArea/Quotation/view', [
            'pdfUrl' => $pdfUrl, 'businessDetails' => $businessDetails,
        ]);
    }
}
