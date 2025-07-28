<?php

namespace App\Http\Controllers;

use App\Models\BusinessDetail;
use App\Models\PosOrder;
use domain\Facades\BillPaymentFacade\BillPaymentFacade;
use domain\Facades\InvoiceFacade\InvoiceFacade;
use domain\Facades\PosOrderFacade\PosOrderFacade;
use domain\Facades\PosOrderItemFacade\PosOrderItemFacade;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use PDF;

class PublicViewInvoiceController extends Controller
{
    public function viewInvoicePage(string $invoice_key)
    {
        $invoice_id = InvoiceFacade::getPublicInvoice($invoice_key);
        $order = PosOrder::find($invoice_id);
        $response['order'] = PosOrderFacade::get($invoice_id);
        $response['order_items'] = PosOrderItemFacade::all($invoice_id);
        $response['created_at'] = $response['order']['created_at'];
        $response['print_type'] = 'invoice';
        $response['bill'] = BillPaymentFacade::get($invoice_id);
        $response['config'] = BusinessDetail::latest()->first();

        if ($order->type == 0) {
            $pdf = PDF::loadView('print.pages.Bill.payment', $response)->setPaper([0, 0, 300, 700], 'portrait');
        } else {
            $response['custom_fields'] = InvoiceFacade::parametersGetForPrint($invoice_id);
            $response['footer_fields'] = InvoiceFacade::footerParametersGetForPrint($invoice_id);
            $pdf = PDF::loadView('print.pages.Invoice.invoice', $response)->setPaper('a4');
        }

        $pdfPath = 'invoices/'.$invoice_key.'.pdf';
        Storage::disk('public')->put($pdfPath, $pdf->output());
        $pdfUrl = Storage::url($pdfPath);

        $businessDetails = $response['config'];

        return Inertia::render('PublicArea/Invoice/view', [
            'pdfUrl' => $pdfUrl, 'businessDetails' => $businessDetails,
        ]);
    }
}
