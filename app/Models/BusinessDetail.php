<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessDetail extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'address',
        'mobile',
        'email',
        'footer',
        'image_id',
        'bill_image_id',
        'invoice_image_id',
        'welcome_heading',
        'sub_heading',
        'public_image_id',
        'facebook_link',
        'instagram_link',
        'twitter_link',
        'whatsapp_link',
        'status',
        'currency_id',
        'color_code',
        'is_product_code',
        'is_product_description',
        'is_line_number',
        'weekly_target',
        'monthly_target',
        'yearly_target',
        'transaction_balance',
        'account_api_key',
        'is_imported_products',
        'is_imported_customers',
        'is_imported_suppliers',
        'is_imported_expense_categories',
        'is_imported_expenses',
        'is_imported_quotations',
        'is_imported_invoices',
        'is_imported_transactions',

        // image ratios columns
        'business_logo_ratio',
        'bill_logo_ratio',
        'invoice_logo_ratio',
    ];

    protected $appends = [
        'image_url',
        'public_image_url',
        'bill_logo_url',
        'invoice_logo_url',
        'currency_name',
    ];

    public function image()
    {
        return $this->hasOne(Image::class, 'id', 'image_id');
    }

    public function getImageUrlAttribute()
    {
        return $this->image->name ?? null;
    }

    public function publicImage()
    {
        return $this->hasOne(Image::class, 'id', 'public_image_id');
    }

    public function getPublicImageUrlAttribute()
    {
        return $this->publicImage->name ?? null;
    }

    public function billLogo()
    {
        return $this->hasOne(Image::class, 'id', 'bill_image_id');
    }

    public function getBillLogoUrlAttribute()
    {
        return $this->billLogo->name ?? null;
    }

    public function invoiceLogo()
    {
        return $this->hasOne(Image::class, 'id', 'invoice_image_id');
    }

    public function getInvoiceLogoUrlAttribute()
    {
        return $this->invoiceLogo->name ?? null;
    }

    public function currency()
    {
        return $this->hasOne(Currency::class, 'id', 'currency_id');
    }

    public function getCurrencyNameAttribute()
    {
        return $this->currency->code ?? null;
    }
}
