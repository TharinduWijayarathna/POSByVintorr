<?php

namespace App\Imports;

use App\Http\Requests\Supplier\CreateSupplierRequest;
use App\Models\Supplier;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToCollection;

class SupplierImport implements SkipsOnError, ToCollection
{
    use SkipsErrors;

    private $supplier;

    private $expectedColumns = ['Name', 'Company', 'Address', 'Email', 'Phone Number', 'Website'];

    public function __construct()
    {
        $this->supplier = new Supplier;
    }

    public function collection(Collection $rows)
    {
        $count_row = 0;
        $errors = [];
        $existingDataCount = 0;
        $invalidDetailsCount = 0;
        $missingDetailsCount = 0;

        if ($rows->isEmpty()) {
            $this->flashError('Invalid Data Format: The file is empty.');

            return;
        }

        $headerRow = $rows->first()->toArray();

        if (! $this->validateHeader($headerRow)) {
            $this->flashError('Invalid Data Format: The file does not match the expected columns.');

            return;
        }

        $dataRows = $rows->slice(1);

        foreach ($dataRows as $key => $row) {
            if ($row->filter()->isEmpty()) {
                $errors[] = 'Row '.($key + 1).": Rows can't be empty.";
                $missingDetailsCount++;

                continue;
            }

            if (! isset($row[0])) {
                $errors[] = 'Row '.($key + 1).": Supplier Name can't be empty.";
                $missingDetailsCount++;

                continue;
            }

            $data = $this->prepareData($row);

            $validator = Validator::make($data, (new CreateSupplierRequest)->rules());

            if ($validator->fails()) {
                $errors[] = 'Row '.($key + 1).': '.implode(', ', $validator->errors()->all());
                $invalidDetailsCount++;

                continue;
            }

            if (isset($data['email']) && $this->isExistingSupplier($data)) {
                $errors[] = 'Row '.($key + 1).': This email is already registered.';
                $existingDataCount++;

                continue;
            }

            Supplier::create($data);
            $count_row++;
        }

        $this->flashMessages($count_row, $existingDataCount, $invalidDetailsCount, $missingDetailsCount, $errors);
    }

    private function validateHeader(array $headerRow): bool
    {
        $cleanedHeaderRow = array_filter($headerRow, fn ($value) => ! is_null($value) && $value !== '');

        return $cleanedHeaderRow === $this->expectedColumns;
    }

    private function prepareData($row): array
    {
        return [
            'name' => $row[0],
            'company' => $row[1],
            'address' => $row[2],
            'email' => $row[3],
            'contact' => $row[4],
            'website' => $row[5],
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ];
    }

    private function isExistingSupplier(array $data): bool
    {
        return $this->supplier->where('email', $data['email'])->exists();
    }

    private function flashMessages(int $importedCount, int $existingDataCount, int $invalidDetailsCount, int $missingDetailsCount, array $errors)
    {
        $importMessage = "Import Supplier\n";

        if ($importedCount > 0) {
            $importMessage .= "$importedCount rows were imported successfully.\n";
        }

        if ($existingDataCount > 0) {
            $importMessage .= "$existingDataCount rows contain existing data.\n";
        }

        if ($invalidDetailsCount > 0) {
            $importMessage .= "$invalidDetailsCount rows contain invalid details.\n";
        }

        if ($missingDetailsCount > 0) {
            $importMessage .= "$missingDetailsCount rows are missing some required detail.\n";
        }

        Session::flash('imported', $importMessage);
        Session::flash('import_errors', $errors);
    }

    private function flashError(string $message)
    {
        Session::flash('import_errors', [$message]);
    }
}
