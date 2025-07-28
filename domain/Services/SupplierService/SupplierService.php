<?php

namespace domain\Services\SupplierService;

use App\Models\BillPayment;
use App\Models\Configuration;
use App\Models\PosCustomer;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * SupplierService
 * php version 8
 *
 * @category Service
 * @author   EmergentSpark <contact@emergentspark.com>
 * @license  https://emergentspark.com Config
 * @link     https://emergentspark.com
 * */
class SupplierService
{
    protected $supplier;
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
        $this->supplier = new Supplier();
        $this->pos_customer = new PosCustomer();
        $this->pos_cashier = new Configuration();
        $this->bill_payment = new BillPayment();
    }

    /**
     * All
     * retrieve all the data from PosCustomer model
     *
     * @return void
     */
    public function all()
    {
        return  $this->pos_customer->all();
    }

    public function multiselectSuppliers()
    {
        return  $this->supplier->orderBy('name', 'asc')->where('type', 0)->get();
    }

    public function multiselectSuppliersSearch(Request $request)
    {
        $query = $request->input('query');
        if ($query) {
            $suppliers = $this-> supplier->where('type', 0)->where('name', 'like', '%' . $query . '%')->orWhere('company', 'like', '%' . $query . '%')->orderBy('name', 'asc');
            return $suppliers->get();
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

        $supplier = $this->supplier
            ->where(function ($query) use ($contactValues) {
                $query->whereIn('contact', $contactValues)
                    ->orWhereIn('contact2', $contactValues)
                    ->orWhereIn('contact3', $contactValues);
            })
            ->first();

        if ($supplier != null) {
            return "This contact number already registered";
        } elseif ($supplier == null) {
            $createdSupplier = $this->supplier->create($data);
            return $createdSupplier;
        }
    }

    /**
     * Get
     * retrieve relevant data using pos_supplier_id
     *
     * @param  int  $pos_supplier_id
     * @return void
     */
    public function get(int $pos_supplier_id)
    {
        return $this->supplier->find($pos_supplier_id);
    }

    /**
     * Get
     * get deleted data also for payroll using pos_supplier_id
     *
     * @param int $pos_supplier_id
     *
     * @return void
     */
    public function getPayrollSupplier(int $pos_supplier_id)
    {
        return $this->supplier->withTrashed()->find($pos_supplier_id);
    }

    /**
     * Get
     * get deleted data also for expenses using pos_supplier_id
     *
     * @param int $pos_supplier_id
     *
     * @return void
     */
    public function getExpenseSupplier(int $pos_supplier_id)
    {
        return $this->supplier->withTrashed()->find($pos_supplier_id);
    }

    /**
     * Update
     * update existing data using pos_supplier_id
     *
     * @param  array $data
     * @param  int   $pos_supplier_id
     * @return void
     */
    public function update(array $data, int $supplier_id)
    {
        $supplier = $this->supplier->find($supplier_id);
        return $supplier->update($this->edit($supplier, $data));
    }

    /**
     * Edit
     * merge data which retrieved from update function as an array
     *
     * @param  PosCustomer $pos_customer
     * @param  array $data
     * @return void
     */
    protected function edit(Supplier $supplier, array $data)
    {
        return array_merge($supplier->toArray(), $data);
    }

    /**
     * Delete
     * delete specific data using pos_supplier_id
     *
     * @param  int   $pos_supplier_id
     * @return void
     */
    public function delete(int $supplier_id)
    {
        return $this->supplier->find($supplier_id)->delete();
    }

    public function addCredit(int $supplier_id, float $credit)
    {
        $supplier = $this->pos_customer->find($supplier_id);
        $supplier->credit += $credit;
        $supplier->save();
    }

    public function payCredit(int $supplier_id, float $credit)
    {
        $supplier = $this->pos_customer->find($supplier_id);
        $supplier->credit -= $credit;
        $supplier->save();
    }

    public function restoreSupplier(int $supplier_id)
    {
        $deleted_supplier = $this->supplier->withTrashed()->find($supplier_id);
        $deleted_supplier->deleted_at = null;
        return $deleted_supplier->save();
    }

    // public function fillOutstanding()
    // {
    //     $suppliers = $this->supplier->get();
    //     foreach ($suppliers as $supplier) {
    //         $total = $this->getOutstanding($supplier->id);
    //         $supplier->supplier_outstanding = $total;
    //         $supplier->save();
    //     }
    // }

    // private function getOutstanding($supplier_id)
    // {
    //     $bills = $this->bill_payment->where('supplier_id', $supplier_id)->get();

    //     $latestBills = $bills->groupBy('order_id')->map(function ($group) {
    //         return $group->latest()->first();
    //     });
    //     Log::info($latestBills);

    //     return $latestBills->sum('balance');
    // }

    // public function getTenantId()
    // {
    //     return $this->pos_cashier->
    // }
}
