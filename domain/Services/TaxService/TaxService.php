<?php

namespace domain\Services\TaxService;

use App\Models\PosOrder;
use App\Models\PosOrderTax;
use App\Models\ProductTax;
use App\Models\Tax;
use Illuminate\Support\Facades\Auth;

class TaxService
{
    protected $tax;

    protected $product_tax;

    protected $pos_order_tax;

    protected $pos_order;

    public function __construct()
    {
        $this->tax = new Tax;
        $this->product_tax = new ProductTax;
        $this->pos_order_tax = new PosOrderTax;
        $this->pos_order = new PosOrder;
    }

    /**
     * all
     *
     * @return void
     */
    public function all()
    {
        return $this->tax->get();
    }

    /**
     * get
     *
     * @param  mixed  $tax_id
     * @return void
     */
    public function get(int $tax_id)
    {
        return $this->tax->find($tax_id);
    }

    /**
     * getProductTax
     *
     * @param  mixed  $product_id
     * @return void
     */
    public function getProductTax(int $product_id)
    {
        return $this->product_tax->where('product_id', $product_id)->get();
    }

    /**
     * deleteProductTax
     *
     * @param  mixed  $product_id
     * @param  mixed  $data
     * @return void
     */
    public function deleteProductTax(int $product_id, array $data)
    {
        foreach ($data as $tax) {
            if ($tax['tax_id'] != null && $product_id != null) {
                $this->product_tax->where('product_id', $product_id)->where('tax_id', $tax['tax_id'])->delete();
            }
        }
    }

    /**
     * delete
     *
     * @param  mixed  $tax_id
     * @return void
     */
    public function delete(int $tax_id)
    {
        return $this->tax->find($tax_id)->delete();
    }

    /**
     * store
     *
     * @param  mixed  $data
     * @return void
     */
    public function store(array $data)
    {
        $user = Auth::user();
        $data['name'] = ucwords($data['name']);
        $data['abbreviation'] = strtoupper($data['abbreviation']);
        $date['created_by'] = $user->id;

        $existing_tax = $this->tax->where('name', $data['name'])->first();

        if (! $existing_tax) {
            $this->tax->create($data);
        } else {
            return 'This tax already exists';
        }
    }

    /**
     * storeProductTax
     *
     * @param  mixed  $product_id
     * @param  mixed  $data
     * @return void
     */
    public function storeProductTax(int $product_id, array $data)
    {
        foreach ($data as $tax) {
            if ($tax['tax_id'] != null && $product_id != null) {
                $taxData['product_id'] = $product_id;
                $taxData['tax_id'] = $tax['tax_id'];
                $taxData['tax_name'] = $tax['tax_name'];
                $taxData['tax_abbreviation'] = $tax['tax_abbreviation'];
                $taxData['tax_rate'] = $tax['tax_rate'];
                $this->product_tax->create($taxData);
            }
        }
    }

    /**
     * update
     *
     * @param  mixed  $id
     * @param  mixed  $data
     * @return void
     */
    public function update($id, $data)
    {
        $user = Auth::user();
        $data['name'] = ucwords($data['name']);
        $data['abbreviation'] = strtoupper($data['abbreviation']);
        $date['updated_by'] = $user->id;

        $tax = $this->tax->find($id);

        return $tax->update($this->edit($tax, $data));
    }

    /**
     * edit
     *
     * @param  mixed  $tax
     * @param  mixed  $data
     * @return void
     */
    protected function edit(Tax $tax, array $data)
    {
        return array_merge($tax->toArray(), $data);
    }

    /**
     * getLatestTax
     *
     * @return void
     */
    public function getLatestTax()
    {
        return $this->tax->latest()->first();
    }

    public function addTaxes(array $taxes, $order_id)
    {
        $pos_order_taxes = $this->pos_order_tax->where('order_id', $order_id)->get();
        foreach ($pos_order_taxes as $pos_order_tax) {
            $pos_order_tax->delete();
        }

        foreach ($taxes as $tax_id) {
            $tax = $this->tax->find($tax_id);

            $taxData['order_id'] = $order_id;
            $taxData['tax_id'] = $tax->id;
            $taxData['rate'] = $tax->rate;
            $this->pos_order_tax->create($taxData);
        }

        $this->refreshOrders($order_id);
    }

    public function showTaxes(int $order_id)
    {
        $taxes = $this->pos_order_tax->where('order_id', $order_id)->get();
        foreach ($taxes as $tax) {
            $order = $this->pos_order->where('id', $tax->order_id)->first();
            $tax_amount = ($tax->rate * ($order->sub_total - $order->discount)) / 100;
            $tax->tax_value = $tax_amount;
        }

        return $taxes;
    }

    public function removeTax(int $order_id, int $tax_id)
    {
        $tax = $this->pos_order_tax->where('order_id', $order_id)->where('tax_id', $tax_id)->first();
        if ($tax) {
            $tax->delete();
        }
        $this->refreshOrders($order_id);
    }

    public function refreshOrders($id)
    {
        $taxes = $this->pos_order_tax->where('order_id', $id)->get();

        if ($taxes->isEmpty()) {
            $order = $this->pos_order->where('id', $id)->first();
            $order->total_tax = 0;
            $order->update();
        }

        $tax_amount = 0;
        foreach ($taxes as $tax) {
            $order = $this->pos_order->where('id', $tax->order_id)->first();
            $tax_amount += ($tax->rate * ($order->sub_total - $order->discount)) / 100;
            $order->total_tax = $tax_amount;
            $order->update();
        }
    }
}
