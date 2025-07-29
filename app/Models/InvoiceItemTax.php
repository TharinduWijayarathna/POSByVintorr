<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/*
 * InvoiceItemTax
 * php version 8
 *
 * @category Model
 * @author   Vintorr <contact@Vintorr.com>
 * @license  https://Vintorr.com Config
 * @link     https://Vintorr.com
 * */
class InvoiceItemTax extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'tax_id',
        'invoice_id',
        'name',
        'rate',
        'abbreviation',
        'amount',

    ];

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'invoice_id', 'id');
    }

    public function findTax($tax_id, $invoice_id)
    {
        return $this->where('tax_id', $tax_id)->where('invoice_id', $invoice_id)->first();
    }

    public function findTaxes($invoice_id)
    {
        return $this->where('invoice_id', $invoice_id)->get();
    }
}
