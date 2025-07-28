<?php

namespace App\Http\Controllers;

use App\Http\Requests\PosAdvanceReceipt\CreatePosAdvanceReceiptRequest;
use App\Http\Resources\DataResource;
use App\Models\PosAdvanceReceipt;
use domain\Facades\PosAdvanceReceiptFacade\PosAdvanceReceiptFacade;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use PDF;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PosAdvanceReceiptController extends ParentController
{
    /**
     * index
     * Load Customer Order index page
     *
     * @return void
     */
    public function index()
    {
        if (Auth::user()->can('access_advance_receipt')) {
            return Inertia::render('AdvanceReceipt/index');
        } else {
            $response['alert-danger'] = 'You do not have permission to advance receipt.';

            return redirect()->route('dashboard')->with($response);
        }
    }

    /**
     * store
     * Store Customer Order details
     *
     * @return void
     */
    public function store(int $order_id, CreatePosAdvanceReceiptRequest $req)
    {
        if (Auth::user()->can('access_advance_receipt')) {
            return PosAdvanceReceiptFacade::store($order_id, $req->all());
        } else {
            $response['alert-danger'] = 'You do not have permission to create advance receipt.';

            return redirect()->route('advanceReceipt.index')->with($response);
        }
    }

    /**
     * all
     * Get all Customer Order details. If there is request, filter will be worked
     *
     * @return void
     */
    public function all(Request $request)
    {
        $query = PosAdvanceReceipt::orderBy('id', 'desc');
        $payload = QueryBuilder::for($query)
            ->allowedSorts(['id'])
            ->allowedFilters(
                AllowedFilter::callback('search', function ($query, $value) {
                    $query->whereHas('order', function (Builder $query) use ($value) {
                        $query->where('order_no', 'like', "%{$value}%");
                    });
                    $query->orWhere('receipt_no', $value);
                    $query->orWhere('amount', $value);
                    $query->orWhere('date', $value);
                    $query->orWhere('remark', 'like', "%{$value}%");
                })
            )
            ->paginate(request('per_page', config('basic.pagination_per_page')));

        return DataResource::collection($payload);
    }

    /**
     * get
     * Get single Customer Order details using Customer Order id
     *
     * @return void
     */
    public function get(int $order_id)
    {
        return PosAdvanceReceiptFacade::get($order_id);
    }

    /**
     * update
     * Update Customer Order detail according to the request
     *
     * @return void
     */
    public function update(CreatePosAdvanceReceiptRequest $request, int $receipt_id)
    {
        if (Auth::user()->can('access_advance_receipt')) {
            return PosAdvanceReceiptFacade::update($request->all(), $receipt_id);
        } else {
            $response['alert-danger'] = 'You do not have permission to update advance receipt.';

            return redirect()->route('advanceReceipt.index')->with($response);
        }
    }

    /**
     * print
     *
     * @return void
     */
    public function print(int $order_id)
    {
        $response['receptDetails'] = PosAdvanceReceiptFacade::getById($order_id);
        $response['total'] = PosAdvanceReceiptFacade::getTotalByOrderId($order_id);
        $pdf = PDF::loadView('print.pages.receipt', $response)->setPaper([0, 0, 300, 700], 'portrait');

        return $pdf->stream('Receipt.pdf', ['Attachment' => false]);
    }

    /**
     * printPaymentWise
     *
     * @return void
     */
    public function printPaymentWise(int $order_id, Request $receipt)
    {
        $response['receptDetails'] = PosAdvanceReceiptFacade::getById($order_id);
        $response['total'] = PosAdvanceReceiptFacade::getTotalByOrderId($order_id);
        $response['receipt'] = $receipt;
        $pdf = PDF::loadView('print.pages.receipt-payment-wise', $response)->setPaper([0, 0, 300, 700], 'portrait');

        return $pdf->stream('Receipt.pdf', ['Attachment' => false]);
    }

    /**
     * list
     *
     * @param  mixed  $order_id
     * @return void
     */
    public function list(int $order_id)
    {
        return PosAdvanceReceiptFacade::list($order_id);
    }
}
