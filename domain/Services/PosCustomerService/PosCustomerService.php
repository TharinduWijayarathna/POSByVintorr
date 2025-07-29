<?php

namespace domain\Services\PosCustomerService;

use App\Models\BillPayment;
use App\Models\Configuration;
use App\Models\Customer;
use App\Models\PosCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * PosCustomerService
 * php version 8
 *
 * @category Service
 *
 * @author   Vintorr <contact@Vintorr.com>
 * @license  https://Vintorr.com Config
 *
 * @link     https://Vintorr.com
 * */
class PosCustomerService
{
    protected $customer;

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
        $this->customer = new Customer;
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

    public function customerAll()
    {
        return $this->customer->orderBy('name', 'asc')->get();
    }

    /**
     * Store
     * store data in database
     *
     * @return void
     */
    public function store(array $data)
    {
        $customer = $this->customer->where('contact', $data['contact'])->first();
        if ($customer) {
            return 'exists';
        } else {
            return $this->customer->create($data);
        }
    }

    public function allStore(array $data)
    {
        $data['created_by'] = Auth::user()->id;

        if (isset($data['customer_outstanding'])) {
            // remove thousand-seperator
            $data['customer_outstanding'] = (float) str_replace(',', '', $data['customer_outstanding']);
        } else {
            $data['customer_outstanding'] = 0;
        }

        $contactValues = [
            'contact' => $data['contact'] ?? null,
            'contact2' => $data['contact2'] ?? null,
            'contact3' => $data['contact3'] ?? null,
        ];

        $customer = $this->customer
            ->where(function ($query) use ($contactValues) {
                $query->whereIn('contact', $contactValues)
                    ->orWhereIn('contact2', $contactValues)
                    ->orWhereIn('contact3', $contactValues);
            })
            ->first();

        if ($customer != null) {
            return 'This contact number already registered';
        } elseif ($customer == null) {
            return $this->customer->create($data);
        }
    }

    /**
     * Get
     * retrieve relevant data using pos_customer_id
     *
     * @return void
     */
    public function get(int $pos_customer_id)
    {
        return $this->customer->withTrashed()->find($pos_customer_id);
    }

    /**
     * Update
     * update existing data using pos_customer_id
     *
     * @param  int  $pos_customer_id
     * @return void
     */
    public function update(array $data, int $customer_id)
    {
        $customer = $this->customer->find($customer_id);

        return $customer->update($this->edit($customer, $data));
    }

    /**
     * Edit
     * merge data which retrieved from update function as an array
     *
     * @param  PosCustomer  $pos_customer
     * @return void
     */
    protected function edit(Customer $customer, array $data)
    {
        return array_merge($customer->toArray(), $data);
    }

    /**
     * Delete
     * delete specific data using pos_customer_id
     *
     * @param  int  $pos_customer_id
     * @return void
     */
    public function delete(int $customer_id)
    {
        return $this->customer->find($customer_id)->delete();
    }

    public function getByNumber(int $contact)
    {
        return $this->customer->getByNumber($contact);
    }

    public function addCredit(int $customer_id, float $credit)
    {
        $customer = $this->pos_customer->find($customer_id);
        $customer->credit += $credit;
        $customer->save();
    }

    public function payCredit(int $customer_id, float $credit)
    {
        $customer = $this->pos_customer->find($customer_id);
        $customer->credit -= $credit;
        $customer->save();
    }

    public function restoreCustomer(int $customer_id)
    {
        $deleted_customer = $this->customer->withTrashed()->find($customer_id);
        $deleted_customer->deleted_at = null;

        return $deleted_customer->save();
    }

    public function multiselectCustomersSearch(Request $request)
    {
        $query = $request->input('query');
        if ($query) {
            $customers = $this->customer->orderBy('name', 'asc')->where('name', 'like', '%'.$query.'%');

            return $customers->get();
        }

        return [];  // Return an empty array if no query
    }

    // public function fillOutstanding()
    // {
    //     $customers = $this->customer->get();
    //     foreach ($customers as $customer) {
    //         $total = $this->getOutstanding($customer->id);
    //         $customer->customer_outstanding = $total;
    //         $customer->save();
    //     }
    // }

    // private function getOutstanding($customer_id)
    // {
    //     $bills = $this->bill_payment->where('customer_id', $customer_id)->get();

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
