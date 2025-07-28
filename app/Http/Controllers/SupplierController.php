<?php

namespace App\Http\Controllers;

use App\Http\Requests\Supplier\CreateSupplierRequest;
use App\Http\Resources\DataResource;
use App\Http\Resources\GetSupplierPurchaserOrderResource;
use App\Imports\SupplierImport;
use App\Models\Expense;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\User;
use domain\Facades\SupplierFacade\SupplierFacade;
use domain\Facades\UserFacade\UserFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SupplierController extends ParentController
{
    public function index()
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            // $response = UserFacade::retrieveHost();
            return Inertia::render('Supplier/index');
        }
    }

    public function allStore(CreateSupplierRequest $req)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return SupplierFacade::allStore($req->all());
        }
    }

    public function all(Request $request)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            $query = Supplier::orderBy('id', 'desc')->where('type', 0);
            if (isset($request->search_supplier_name)) {
                $query->where('name', 'like', "%{$request->search_supplier_name}%");
            }
            if (isset($request->search_supplier_company)) {
                $query->where('company', 'like', "%{$request->search_supplier_company}%");
            }
            if (isset($request->search_supplier_contact)) {
                $searchContact = $request->search_supplier_contact;
                $query->where(function ($query) use ($searchContact) {
                    $query->where('contact', 'like', '%'.$searchContact.'%');
                    $query->orWhere('contact2', 'like', '%'.$searchContact.'%');
                    $query->orWhere('contact3', 'like', '%'.$searchContact.'%');
                });
            }
            $payload = QueryBuilder::for($query)
                ->allowedSorts(['code'])
                ->allowedFilters(
                    AllowedFilter::callback('search', function ($query, $value) {
                        $query->Where('name', 'like', "%{$value}%");
                    })
                )
                ->paginate(request('per_page', config('basic.pagination_per_page')));

            return DataResource::collection($payload);
        }
    }

    public function multiselectSuppliers()
    {
        // if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
        return SupplierFacade::multiselectSuppliers();
        // }
    }

    public function multiselectSuppliersSearch(Request $request)
    {
        // if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
        return SupplierFacade::multiselectSuppliersSearch($request);
        // }
    }

    public function get($supplier_id)
    {
        // if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
        return SupplierFacade::get($supplier_id);
        // }
    }

    public function update(CreateSupplierRequest $req, $supplier_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return SupplierFacade::update($req->all(), $supplier_id);
        }
    }

    public function delete($supplier_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return SupplierFacade::delete($supplier_id);
        }
    }

    public function loadSupplier(int $supplier_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            // $response = UserFacade::retrieveHost();
            $response['supplier_id'] = $supplier_id;

            return Inertia::render('Supplier/edit', $response);
        }
    }

    public function allExpenses(int $supplier_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            $query = Expense::orderBy('created_at', 'desc')->where('supplier_id', $supplier_id);
            $payload = QueryBuilder::for($query)
                ->paginate(request('per_page', config('basic.pagination_per_page')));

            return DataResource::collection($payload);
        }
    }

    public function deletedList()
    {
        // $response = UserFacade::retrieveHost();
        return Inertia::render('Supplier/deleted');
    }

    public function deletedAll(Request $request)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            $query = Supplier::orderBy('id', 'desc')->where('type', 0)->onlyTrashed();
            if (isset($request->search_supplier_name)) {
                $query->where('name', 'like', "%{$request->search_supplier_name}%");
            }
            if (isset($request->search_supplier_company)) {
                $query->where('company', 'like', "%{$request->search_supplier_company}%");
            }
            if (isset($request->search_supplier_contact)) {
                $searchContact = $request->search_supplier_contact;
                $query->where(function ($query) use ($searchContact) {
                    $query->where('contact', 'like', '%'.$searchContact.'%');
                    $query->orWhere('contact2', 'like', '%'.$searchContact.'%');
                    $query->orWhere('contact3', 'like', '%'.$searchContact.'%');
                });
            }
            $payload = QueryBuilder::for($query)
                ->allowedSorts(['code'])
                ->allowedFilters(
                    AllowedFilter::callback('search', function ($query, $value) {
                        $query->Where('name', 'like', "%{$value}%");
                    })
                )
                ->paginate(request('per_page', config('basic.pagination_per_page')));

            return DataResource::collection($payload);
        }
    }

    public function restoreSupplier($supplier_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return SupplierFacade::restoreSupplier($supplier_id);
        }
    }

    /**
     * import
     *
     * @param  mixed  $request
     * @return void
     */
    public function import(Request $request)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            $request->validate([
                'supplier_file' => 'required|mimes:xlsx',
            ]);

            Excel::import(new SupplierImport, $request->file('supplier_file'));
            $count_description = Session::get('imported');
            $errors = Session::get('import_errors');
            Session::forget('imported');
            Session::forget('import_errors');

            $response = [
                'message' => $count_description,
                'errors' => $errors,
            ];

            return response()->json($response);
        }
    }

    /**
     * downloadSampleExcel
     *
     * @return void
     */
    public function downloadSampleExcel()
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            $file = public_path('sample_excel/suppliers.xlsx');
            $headers = ['Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];

            // dd($file, $headers);
            return response()->download($file, 'supplier.xlsx', $headers);
        }
    }

    /**
     * AllPurchaseOrder
     * get all purchase order
     *
     *
     * @return void
     */
    public function allPurchaseOrder($supplier_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            $query = PurchaseOrder::orderBy('created_at', 'desc')->where('supplier_id', $supplier_id);
            $payload = QueryBuilder::for($query)
                ->paginate(request('per_page', config('basic.pagination_per_page')));

            return GetSupplierPurchaserOrderResource::collection($payload);
        }
    }

    /**
     * GetPayrollSupplier
     *
     *
     * @return void
     */
    public function getPayrollSupplier($supplier_id)
    {
        // if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
        return SupplierFacade::getPayrollSupplier($supplier_id);
        // }
    }

    /**
     * GetExpenseSupplier
     *
     *
     * @return void
     */
    public function getExpenseSupplier($supplier_id)
    {
        // if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
        return SupplierFacade::getExpenseSupplier($supplier_id);
        // }
    }
}
