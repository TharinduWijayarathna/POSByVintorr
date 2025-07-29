<?php

namespace domain\Services\EmployeeService;

use App\Models\BillPayment;
use App\Models\Configuration;
use App\Models\PosCustomer;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * EmployeeService
 * php version 8
 *
 * @category Service
 *
 * @author   Vintorr <contact@Vintorr.com>
 * @license  https://Vintorr.com Config
 *
 * @link     https://Vintorr.com
 * */
class EmployeeService
{
    protected $employee;

    protected $pos_customer;

    protected $pos_cashier;

    protected $bill_payment;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->employee = new Supplier;
        $this->pos_customer = new PosCustomer;
        $this->pos_cashier = new Configuration;
        $this->bill_payment = new BillPayment;
    }

    /**
     * All
     * retrieve all the data from PosCustomer model
     *
     * @return void
     */
    public function all()
    {
        return $this->pos_customer->all();
    }

    public function multiselectEmployees()
    {
        return $this->employee->orderBy('name', 'asc')->where('type', 1)->get();
    }

    public function multiselectEmployeesSearch(Request $request)
    {
        $query = $request->input('query');
        if ($query) {
            $employees = $this->employee->where('type', 1)->orderBy('name', 'asc')->where('name', 'like', '%'.$query.'%');

            return $employees->get();
        }

        return [];  // Return an empty array if no query
    }

    public function allStore(array $data)
    {
        $data['created_by'] = Auth::user()->id;

        $contactValues = [
            'contact' => $data['contact'] ?? null,
            'contact2' => $data['contact2'] ?? null,
            'contact3' => $data['contact3'] ?? null,
        ];

        $employee = $this->employee
            ->where(function ($query) use ($contactValues) {
                $query->whereIn('contact', $contactValues)
                    ->orWhereIn('contact2', $contactValues)
                    ->orWhereIn('contact3', $contactValues);
            })
            ->first();

        if ($employee != null) {
            return 'This contact number already registered';
        } elseif ($employee == null) {
            $createdEmployee = $this->employee->create($data);

            return $createdEmployee;
        }
    }

    /**
     * Get
     * retrieve relevant data using pos_employee_id
     *
     * @return void
     */
    public function get(int $pos_employee_id)
    {
        return $this->employee->find($pos_employee_id);
    }

    /**
     * Update
     * update existing data using pos_employee_id
     *
     * @param  int  $pos_employee_id
     * @return void
     */
    public function update(array $data, int $employee_id)
    {
        $employee = $this->employee->find($employee_id);

        return $employee->update($this->edit($employee, $data));
    }

    /**
     * Edit
     * merge data which retrieved from update function as an array
     *
     * @param  PosCustomer  $pos_customer
     * @return void
     */
    protected function edit(Supplier $employee, array $data)
    {
        return array_merge($employee->toArray(), $data);
    }

    /**
     * Delete
     * delete specific data using pos_employee_id
     *
     * @param  int  $pos_employee_id
     * @return void
     */
    public function delete(int $employee_id)
    {
        return $this->employee->find($employee_id)->delete();
    }

    public function addCredit(int $employee_id, float $credit)
    {
        $employee = $this->pos_customer->find($employee_id);
        $employee->credit += $credit;
        $employee->save();
    }

    public function payCredit(int $employee_id, float $credit)
    {
        $employee = $this->pos_customer->find($employee_id);
        $employee->credit -= $credit;
        $employee->save();
    }

    public function restoreEmployee(int $employee_id)
    {
        $deleted_employee = $this->employee->withTrashed()->find($employee_id);
        $deleted_employee->deleted_at = null;

        return $deleted_employee->save();
    }
}
