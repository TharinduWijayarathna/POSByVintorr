<?php

namespace App\Imports;

use App\Http\Requests\Product\CreateProductRequest;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToCollection;

class ProductsImport implements ToCollection, SkipsOnError
{
    use SkipsErrors;

    private $product;
    private $expectedColumns = ['Name', 'Stock', 'ROL', 'Product Cost', 'Selling Price']; // Define expected columns


    public function __construct()
    {
        $this->product = new Product();
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        $count_row = 0;
        $errors = [];

        // Check if the file has data
        if ($rows->isEmpty()) {
            $errors[] = "Invalid Data Format: The file is empty.";
            Session::flash('import_errors', $errors);
            return;
        }

        // Get the header row
        $headerRow = $rows->first()->toArray();

        // Check if the header matches the expected columns
        if (!$this->validateHeader($headerRow)) {
            $errors[] = "Invalid Data Format: The file does not match the expected columns.";
            Session::flash('import_errors', $errors);
            return;
        }

        $last_product = $this->product->withTrashed()->latest('code')->first();
        $base_count = $last_product ? substr($last_product->code, 1) : 0;

        foreach ($rows as $key => $row) {

            if ($key > 0) {

                // Skip empty rows
                if ($row->filter()->isEmpty()) {
                    $errors[] = "Row " . ($key + 1) . ": Rows can't be empty.";
                    continue;
                }
                if (!isset($row[0])) {
                    $errors[] = "Row " . ($key + 1) . ": Product name is required.";
                }
                if (!isset($row[4])) {
                    $errors[] = "Row " . ($key + 1) . ": Selling price is required.";
                }

                $product = Product::where('name', $row[0])
                ->where('selling', $row[4])
                    ->first();

                if (isset($row[0]) && isset($row[4])) {

                    $code = 'P' . sprintf('%05d', $base_count + 1);
                    do {
                        $base_count++;
                        $code = 'P' . sprintf('%05d', $base_count);
                        $code_exists = $this->product->withTrashed()->where('code', $code)->exists();
                    } while ($code_exists);

                    $product = [
                        'name' => $row[0],
                        'stock_quantity' => $row[1] ?? 0,
                        'rol' => $row[2] ?? 0,
                        'cost' => $row[3],
                        'selling' => $row[4],
                        'code' => $code,
                        'created_by' => auth()->id(),
                        'updated_by' => auth()->id(),
                    ];

                    // Validate the data
                    $validator = Validator::make($product, (new CreateProductRequest())->rules());

                    if ($validator->fails()) {
                        $errors[] = "Row " . ($key + 1) . ": " . implode(", ", $validator->errors()->all());
                        continue;
                    }

                    Product::create($product);
                    $count_row++;
                }
            }
        }
        if ($count_row > 0) {
            Session::flash('imported', $count_row . " rows imported successfully.");
        } else {
            Session::flash('imported', "No data was imported.");
        }
        Session::flash('import_errors', $errors);
    }


    /**
     * Validate the header row against the expected columns.
     *
     * @param array $headerRow
     * @return bool
     */
    private function validateHeader(array $headerRow): bool
    {
        // Remove any null or empty values from the header row
        $cleanedHeaderRow = array_filter($headerRow, fn ($value) => !is_null($value) && $value !== '');

        // Compare the cleaned header row with the expected columns
        return $cleanedHeaderRow === $this->expectedColumns;
    }
}
