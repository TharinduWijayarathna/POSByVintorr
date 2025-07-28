<?php

namespace App\Http\Controllers;

use App\Http\Requests\Employee\CreateEmployeeRequest;
use App\Http\Resources\DataResource;
use App\Http\Resources\GetEmployeePayrollResource;
use App\Imports\EmployeeImport;
use App\Models\Supplier;
use App\Models\Expense;
use App\Models\User;
use domain\Facades\EmployeeFacade\EmployeeFacade;
use domain\Facades\UserFacade\UserFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class EmployeeController extends ParentController
{
    public function index()
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            // $response = UserFacade::retrieveHost();
            return Inertia::render('Employee/index');
        }
    }

    public function allStore(CreateEmployeeRequest $req)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return EmployeeFacade::allStore($req->all());
        }
    }

    public function all(Request $request)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            $query = Supplier::orderBy('id', 'desc')->where('type', 1);
            if (isset($request->search_employee_name)) {
                $query->where('name', 'like', "%{$request->search_employee_name}%");
            }
            if (isset($request->search_employee_email)) {
                $query->where('email', 'like', "%{$request->search_employee_email}%");
            }
            if (isset($request->search_employee_contact)) {
                $searchContact = $request->search_employee_contact;
                $query->where(function ($query) use ($searchContact) {
                    $query->where('contact', 'like', '%' . $searchContact . '%');
                    $query->orWhere('contact2', 'like', '%' . $searchContact . '%');
                    $query->orWhere('contact3', 'like', '%' . $searchContact . '%');
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

    public function multiselectEmployees()
    {
        // if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
        return EmployeeFacade::multiselectEmployees();
        // }
    }

    public function multiselectEmployeesSearch(Request $request)
    {
        // if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
        return EmployeeFacade::multiselectEmployeesSearch($request);
        // }
    }

    public function get($employee_id)
    {
        // if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return EmployeeFacade::get($employee_id);
        // }
    }

    public function update(CreateEmployeeRequest $req, $employee_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return EmployeeFacade::update($req->all(), $employee_id);
        }
    }

    public function delete($employee_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return EmployeeFacade::delete($employee_id);
        }
    }

    public function loadEmployee(int $employee_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            // $response = UserFacade::retrieveHost();
            $response['employee_id'] = $employee_id;
            return Inertia::render('Employee/edit')->with($response);
        }
    }

    public function allExpenses(int $employee_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            $query = Expense::orderBy('created_at', 'desc')->where('type', 1)->where('supplier_id', $employee_id);
            $payload = QueryBuilder::for($query)
                ->paginate(request('per_page', config('basic.pagination_per_page')));
            return DataResource::collection($payload);
        }
    }

    public function deletedList()
    {
        // $response = UserFacade::retrieveHost();
        return Inertia::render('Employee/deleted');
    }

    public function deletedAll(Request $request)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            $query = Supplier::orderBy('id', 'desc')->where('type', 1)->onlyTrashed();
            if (isset($request->search_employee_name)) {
                $query->where('name', 'like', "%{$request->search_employee_name}%");
            }
            if (isset($request->search_employee_email)) {
                $query->where('email', 'like', "%{$request->search_employee_email}%");
            }
            if (isset($request->search_employee_contact)) {
                $searchContact = $request->search_employee_contact;
                $query->where(function ($query) use ($searchContact) {
                    $query->where('contact', 'like', '%' . $searchContact . '%');
                    $query->orWhere('contact2', 'like', '%' . $searchContact . '%');
                    $query->orWhere('contact3', 'like', '%' . $searchContact . '%');
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

    public function restoreEmployee($employee_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return EmployeeFacade::restoreEmployee($employee_id);
        }
    }

    /**
     * import
     *
     * @param  mixed $request
     * @return void
     */
    public function import(Request $request)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            $request->validate([
                'employee_file' => 'required|mimes:xlsx'
            ]);

            Excel::import(new EmployeeImport, $request->file('employee_file'));
            $count_description = Session::get('imported');
            $errors = Session::get('import_errors');
            Session::forget('imported');
            Session::forget('import_errors');

            $response = [
                'message' => $count_description,
                'errors' => $errors
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
            $file = public_path('sample_excel/employees.xlsx');
            $headers = ['Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
            // dd($file, $headers);
            return response()->download($file, 'employee.xlsx', $headers);
        }
    }

    /**
     * AllPayrolls
     * get all employee payrolls using resource
     *
     * @param $employee_id
     *
     * @return void
     */
    public function allPayrolls($employee_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            $query = Expense::orderBy('created_at', 'desc')->where('type', 1)->where('supplier_id', $employee_id);
            $payload = QueryBuilder::for($query)
                ->paginate(request('per_page', config('basic.pagination_per_page')));

            return GetEmployeePayrollResource::collection($payload);
        }
    }
}
