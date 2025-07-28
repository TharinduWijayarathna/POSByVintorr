<?php

namespace App\Models;

use App\Models\Unit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    const TYPE = [
        'product' => 1,
        'service' => 2,
    ];

    const PRODUCT_TYPE = [
        'stockable' => 1,
        'non-stockable' => 0,
    ];

    const IS_IMPORTED_BY_IBLIZ = [
        'yes' => 1,
        'no' => 0,
    ];

    const VISIBILITY = [
        'visible' => 0,
        'hide' => 1,
    ];

    protected $fillable = [
        'code',
        'name',
        'barcode',
        'introduction',
        'description',
        'type',
        'unit',
        'unit_name',
        'cost',
        'selling',
        'status',
        'visibility',
        'product_id',
        'expense_category_id',
        'expense_sub_category_id',
        'supplier_id',
        'remark',
        'quantity',
        'amount',
        'product_category_id',
        'image_id',
        'product_type',
        'stock_quantity',
        'rol',
        'order_no',
    ];

    protected $appends = [
        'image_url',
        'unit_name',
        'category_name',
        'type_title',
        'total_qty',
        'total_price',
        'formatted_stock_qty',
        'formatted_rol',
        'formatted_product_price',
        'formatted_selling_price',
    ];

    public function getImageUrlAttribute()
    {
        return $this->image->name ?? null;
    }

    public function getUnitNameAttribute()
    {
        return Unit::where('id', $this->unit)->first()->title ?? 'N/A';
    }

    public function getCategoryNameAttribute()
    {
        return ProductCategory::where('id', $this->productCategory)->first()->name ?? 'N/A';
    }

    public function unit()
    {
        return $this->hasOne(Unit::class, 'id', 'unit');
    }

    public function productCategory()
    {
        return $this->hasOne(ProductCategory::class, 'id', 'product_category_id');
    }

    public function costs()
    {
        return $this->hasMany(ProductCost::class, 'product_id', 'id');
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class, 'product_id', 'id');
    }

    public function image()
    {
        return $this->hasOne(Image::class, 'id', 'image_id');
    }

    public function getTypeTitleAttribute()
    {
        return $this->type == self::TYPE['product'] ? 'Product' : 'Service';
    }

    public function getTotalQtyAttribute()
    {
        $qty = $this->items->sum('quantity');
        return $qty;
    }

    public function getTotalPriceAttribute()
    {
        $qty = $this->items->sum('quantity');
        $price = $this->selling;
        $total = $qty * $price;
        return $total;
    }

    public function search($query)
    {
        $payload = $this->where('status', 0);

        $payload = $payload->where(function ($payload) use ($query) {
            $payload->where('name', 'like', '%' . $query . '%')
                ->orWhere('code', 'like', '%' . $query . '%')
                ->orWhere('type', 'like', '%' . $query . '%')
                ->orWhere('introduction', 'like', '%' . $query . '%')
                ->orWhere('description', 'like', '%' . $query . '%');
        });


        return $payload->limit(10)->get();
    }

    public function getFinishGoodByNameBarcode(string $name, int $product_category_id, int $order_id)
    {

        $query = Product::where(function (Builder $query) use ($name) {
            $query->where('name', 'like', "%{$name}%")
                ->orWhere('code', 'like', "%{$name}%")
                ->orWhere('barcode', 'like', "%{$name}%");
        });

        if ($product_category_id !== 0) {
            $query->where('product_category_id', $product_category_id);
        }

        return $query->orderByRaw('order_no IS NULL, order_no ASC')->get();
    }

    public function finishGoodByNameBarcodeInspector(string $name, int $product_category_id, int $order_id)
    {

        $query = Product::where(function (Builder $query) use ($name) {
            $query->where('visibility', $this::VISIBILITY['visible'])
                ->where(function (Builder $query) use ($name) {
                    $query->where('name', 'like', "%{$name}%")
                        ->orWhere('code', 'like', "%{$name}%")
                        ->orWhere('barcode', 'like', "%{$name}%");
                });
        });

        if ($product_category_id !== 0) {
            $query->where('product_category_id', $product_category_id);
        }

        return $query->orderByRaw('order_no IS NULL, order_no ASC')->get();
    }

    public function getById(int $product_id)
    {
        return $this->withTrashed()->where('id', $product_id)->first();
    }

    public function getFormattedProductPriceAttribute()
    {
        return number_format($this->cost, 2);
    }

    public function getFormattedSellingPriceAttribute()
    {
        return number_format($this->selling, 2);
    }

    public function getFormattedStockQtyAttribute()
    {
        return number_format($this->stock_quantity, 0, '.', ',');
    }

    public function getFormattedRolAttribute()
    {
        return number_format($this->rol, 0, '.', ',');
    }
}
