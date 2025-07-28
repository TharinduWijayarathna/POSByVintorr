<?php

namespace App\Imports;

use App\Http\Requests\Employee\CreateEmployeeRequest;
use App\Models\Supplier;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToCollection;

class EmployeeImport implements SkipsOnError, ToCollection
{
    use SkipsErrors;

    private $employee;

    private $expectedColumns = ['Name', 'Address', 'Email', 'Phone Number'];

    public function __construct()
    {
        $this->employee = new Supplier;
    }

    public function collection(Collection $rows)
    {
        $count_row = 0;
        $errors = [];

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

                continue;
            }

            $data = $this->prepareData($row);

            $validator = Validator::make($data, (new CreateEmployeeRequest)->rules());

            if ($validator->fails()) {
                $errors[] = 'Row '.($key + 1).': '.implode(', ', $validator->errors()->all());

                continue;
            }

            if (isset($data['email']) && $this->isExistingEmployee($data)) {
                $errors[] = 'Row '.($key + 1).': This employee already registered.';

                continue;
            }

            Supplier::create($data);
            $count_row++;
        }

        $this->flashMessages($count_row, count($errors), $errors);
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
            'type' => 1, // 1 for 'Employee'
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ];
    }

    private function isExistingEmployee(array $data): bool
    {
        return $this->employee
            ->where('email', $data['email'])
            ->exists();
    }

    private function flashMessages(int $importedCount, int $errorCount, array $errors)
    {
        $importMessage = "Import Employee\n";

        if ($importedCount > 0) {
            $importMessage .= "$importedCount rows were imported successfully.\n";
        }

        if ($errorCount > 0) {
            $importMessage .= "$errorCount rows contain errors.\n";
        }

        Session::flash('imported', $importMessage);
        Session::flash('import_errors', $errors);
    }

    private function flashError(string $message)
    {
        Session::flash('import_errors', [$message]);
    }
}
