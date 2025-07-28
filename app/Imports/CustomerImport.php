<?php

namespace App\Imports;

use App\Http\Requests\Customer\CreateCustomerRequest;
use App\Models\Customer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToCollection;

class CustomerImport implements SkipsOnError, ToCollection
{
    use SkipsErrors;

    private $customer;

    private $expectedColumns = ['Name', 'Address', 'Email', 'Phone Number', 'Website'];

    public function __construct()
    {
        $this->customer = new Customer;
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
                $errors[] = 'Row '.($key + 1).": Customer Name can't be empty.";
                $missingDetailsCount++;

                continue;
            }

            $data = $this->prepareData($row);

            $validator = Validator::make($data, (new CreateCustomerRequest)->rules());

            if ($validator->fails()) {
                $errors[] = 'Row '.($key + 1).': '.implode(', ', $validator->errors()->all());
                $invalidDetailsCount++;

                continue;
            }

            if (isset($data['email']) && $this->isExistingCustomer($data)) {
                $errors[] = 'Row '.($key + 1).': This email is already registered.';
                $existingDataCount++;

                continue;
            }

            Customer::create($data);
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
            'address' => $row[1],
            'email' => $row[2],
            'contact' => $row[3],
            'website' => $row[4],
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ];
    }

    private function isExistingCustomer(array $data): bool
    {
        return $this->customer->where('email', $data['email'])->exists();
    }

    private function flashMessages(int $importedCount, int $existingDataCount, int $invalidDetailsCount, int $missingDetailsCount, array $errors)
    {
        $importMessage = "Import Customer\n";

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
