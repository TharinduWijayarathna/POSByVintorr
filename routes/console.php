<?php

use App\Models\PosOrderItem;
use App\Models\Product;
use domain\Facades\PosCustomerFacade\PosCustomerFacade;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('pos_order_item_product', function () {
    $pos_order_items = PosOrderItem::all();
    foreach ($pos_order_items as $item) {
        $product = Product::find($item['product_id']);
        $item->product_name = $product['name'];
        $item->description = $product['description'];
        $item->save();
    }
})->purpose('Add product name & description pos_order_items');

Artisan::command('log:clear', function () {
    $logFiles = glob(storage_path('logs/*.log'));
    foreach ($logFiles as $file) {
        // Open the file in write mode ('w'), which truncates the file to zero length
        $f = fopen($file, 'w');
        fclose($f);
    }

    $this->info('Log files have been emptied.');
})->purpose('Clear log');
