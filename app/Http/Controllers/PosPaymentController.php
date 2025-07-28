<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use PDF;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\DataResource;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Database\Eloquent\Builder;
use domain\Facades\MaterialFacade\MaterialFacade;
use domain\Facades\PosOrderFacade\PosOrderFacade;
use domain\Facades\PosPaymentFacade\PosPaymentFacade;
use domain\Facades\ReturnBillFacade\ReturnBillFacade;
use domain\Facades\GiftVoucherFacade\GiftVoucherFacade;
use domain\Facades\PosCustomerFacade\PosCustomerFacade;
use App\Http\Requests\PosPayment\CreatePosPaymentRequest;
use App\Models\BillPayment;
use App\Models\BusinessDetail;
use App\Models\PosOrder;
use domain\Facades\BillPaymentFacade\BillPaymentFacade;
use domain\Facades\PosOrderItemFacade\PosOrderItemFacade;
use domain\Facades\ConsignmentItemLogFacade\ConsignmentItemLogFacade;
use domain\Facades\InvoiceFacade\InvoiceFacade;

/**
 * PosPayment Controller
 * php version 8
 *
 * @category Controller
 * @author   EmergentSpark <contact@emergentspark.com>
 * @license  https://emergentspark.com Config
 * @link     https://emergentspark.com
 * */
class PosPaymentController extends ParentController
{

    /**
     * print
     *
     * @param  int $order_id
     * @return void
     */
    public function print(int $order_id)
    {
        $response['order'] = PosOrderFacade::get($order_id);
        $response['order_items'] = PosOrderItemFacade::all($order_id);
        $response['created_at'] = $response['order']['created_at'];
        $response['print_type'] = "invoice";
        $response['bill'] = BillPaymentFacade::get($order_id);
        $response['config'] = BusinessDetail::orderBy('id', 'desc')->first();

        if ( $response['order']->type == PosOrder::TYPE['CART']) {
            $pdf = PDF::loadView('print.pages.Bill.payment', $response)->setPaper([0, 0, 300, 700], 'portrait');
            return $pdf->stream("Payment.pdf", array("Attachment" => false));
        } else {
            $response['custom_fields'] = InvoiceFacade::parametersGetForPrint($order_id);
            $response['footer_fields'] = InvoiceFacade::footerParametersGetForPrint($order_id);
            $pdf = PDF::loadView('print.pages.Invoice.invoice', $response)->setPaper('a4');
            return $pdf->stream("Payment.pdf", array("Attachment" => false));
        }
    }

    /**
     * Print receipt
     * @param int $receipt_id
     * @return mixed
     */
    public function printReceipt(int $receipt_id)
    {
        $receipt = BillPayment::where('id', $receipt_id)->first();

        $response['receipt'] = $receipt;
        $response['order'] = PosOrderFacade::get($receipt->order_id);
        $response['created_at'] = $response['receipt']['created_at'];
        $response['print_type'] = "receipt";
        $response['config'] = BusinessDetail::orderBy('id', 'desc')->first();

        $pdf = PDF::loadView('print.pages.Receipt.receipt', $response)->setPaper('a4');
        return $pdf->stream("Payment.pdf", array("Attachment" => false));
    }

    /**
     * Delete receipt
     * @param int $receipt_id
     * @return bool|mixed|null
     */
    public function deleteReceipt(int $receipt_id)
    {
        return BillPaymentFacade::deleteReceipt($receipt_id);
    }

    /**
     * Return order print
     * @param int $order_id
     * @return mixed
     */
    public function returnPrint(int $order_id)
    {
        $response['order'] = PosOrderFacade::get($order_id);
        $response['order_items'] = PosOrderItemFacade::all($order_id);
        $response['config'] = BusinessDetail::orderBy('id', 'desc')->first();
        $pdf = PDF::loadView('print.pages.Return.return', $response)->setPaper([0, 0, 300, 700], 'portrait');
        return $pdf->stream("Return.pdf", array("Attachment" => false));
    }

    /**
     * get
     *
     * @param  int $order_id
     * @return void
     */
    public function get(int $order_id)
    {
        return PosPaymentFacade::getByOrderId($order_id);
    }
}
