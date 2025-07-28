<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserFeedback\SendUserFeedback;
use App\Http\Requests\Users\CreateUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Http\Resources\DataResource;
use App\Models\User;
use domain\Facades\UserFacade\UserFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends ParentController
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN']) {
            if (Auth::user()->user_role_id == 1) {
                // $response = UserFacade::retrieveHost();
                return Inertia::render('User/index');
            } else {
                return Inertia::render('Cart/index');
            }
        }
    }

    /**
     * all
     *
     * @return void
     */
    public function all(Request $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN']) {

            $query = User::query()->withTrashed()->where('status', User::STATUS['VISIBLE'])->orderBy('id', 'desc');
            if (isset($request->search_status)) {
                if ($request->search_status == 2) {
                    $query->where('deleted_at', '!=', null);
                } elseif ($request->search_status == 1) {
                    $query->where('deleted_at', null);
                }
            }
            $payload = QueryBuilder::for($query)
                ->allowedSorts(['id'])
                ->allowedFilters(
                    AllowedFilter::callback('search', function ($query, $value) {
                        $query->where('name', 'like', "%{$value}%");
                    })
                )
                ->paginate(request('per_page', config('basic.pagination_per_page')));

            return DataResource::collection($payload);
        }
    }

    public function allUsers()
    {
        return UserFacade::allUsers();
    }

    public function allAdmins()
    {
        return UserFacade::allAdmins();
    }

    /**
     * store
     *
     * @return void
     */
    public function store(CreateUserRequest $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN']) {
            return UserFacade::store($request->all());
        }
    }

    /**
     * delete
     *
     * @return void
     */
    public function delete(int $user_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN']) {
            if (Auth::user()->user_role_id == 1) {
                if (Auth::user()->id == $user_id) {
                    return 'The admin who is logged into the system cannot delete his account.';
                } else {
                    return UserFacade::delete($user_id);
                }
            } else {
                return 'You do not have permission to delete users.';
            }
        }
    }

    /**
     * restore
     *
     * @return void
     */
    public function restore(int $user_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN']) {
            if (Auth::user()->user_role_id == 1) {
                return UserFacade::restore($user_id);
            } else {
                $response['alert-danger'] = 'You do not have permission to restore users.';

                return redirect()->route('dashboard')->with($response);
            }
        }
    }

    /**
     * update
     *
     * @return void
     */
    public function update(UpdateUserRequest $request, int $user_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN']) {
            if (Auth::user()->user_role_id == 1) {
                return UserFacade::update($request->all(), $user_id);
            } else {
                return 'You do not have permission to restore users.';
            }
        }
    }

    /**
     * get
     *
     * @return void
     */
    public function get(int $user_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN']) {
            $payload = UserFacade::get($user_id);

            return new DataResource($payload);
        }
    }

    /**
     * roles
     *
     * @return void
     */
    public function roles(int $user_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN']) {
            $payload = UserFacade::get($user_id);

            return new DataResource($payload);
        }
    }

    /**
     * sendUserFeedback
     * send user feedback to admin end
     *
     * @return void
     */
    public function sendUserFeedback(SendUserFeedback $request)
    {
        $user_id = Auth::user()->id;

        return UserFacade::sendUserFeedback($user_id, $request->all());
    }
}
