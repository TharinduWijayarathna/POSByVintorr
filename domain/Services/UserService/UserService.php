<?php

namespace domain\Services\UserService;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserService
{
    protected $users;
    protected $customer;
    protected $demo_request;

    protected $user_feedback;

    public function __construct()
    {
        $this->users = new User();
        $this->customer = new Customer();
    }


    /**
     * Get
     * retrieve relevant data using user_id
     *
     * @param  int  $user_id
     * @return void
     */
    public function get(int $user_id)
    {
        return $this->users->find($user_id);
    }

    public function getWithTrashed(int $user_id)
    {
        return $this->users->withTrashed()->find($user_id);
    }

    public function getByEmail(string $email)
    {
        return $this->users->getByEmail($email);
    }

    /**
     * all
     *
     * @return void
     */
    public function all()
    {
        return $this->users->all();
    }

    public function allUsers()
    {
        return $this->users
            ->where(function ($query) {
                $query->where('user_role_id', $this->users::USER_ROLE_ID['ADMIN'])
                    ->orWhere('user_role_id', $this->users::USER_ROLE_ID['CASHIER'])
                    ->orWhere('user_role_id', $this->users::USER_ROLE_ID['INSPECTOR']);
            })
            ->orderBy('name', 'asc')
            ->get();
    }

    public function allAdmins()
    {
        return $this->users
            ->where('user_role_id', $this->users::USER_ROLE_ID['ADMIN'])
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     * find
     *
     * @param  int $user_id
     * @return void
     */
    public function find(int $user_id)
    {
        return $this->users->find($user_id);
    }

    /**
     * Update
     * update existing data using user_id
     *
     * @param  array $data
     * @param  int   $user_id
     * @return void
     */
    public function update($data, $user_id)
    {
        $user = $this->users->where('id', $user_id)->first();

        if (Auth::user()->id == $user_id) {
            if (isset($data['password'])) {
                $data['password'] = bcrypt($data['password']);
            }
            if ($data['user_role_id'] != 1) {
                $data['user_role_id'] = 1;
                $user->update($this->edit($user, $data));
                return "The admin who is logged into the system cannot change hir user role.";
            } else {
                $user->update($this->edit($user, $data));
                return "success";
            }
        } else {
            if (isset($data['password'])) {
                $data['password'] = bcrypt($data['password']);
            }
            $user->update($this->edit($user, $data));
            return "success";
        }
    }

    public function profileUpdate($data, $user_id)
    {
        $user = $this->users->find($user_id);
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
            $user->password = $data['password'];
        }
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->save();
        return "success";
    }

    /**
     * Edit
     * merge data which retrieved from update function as an array
     *
     * @param  selyn $user
     * @param  array    $data
     * @return void
     */
    protected function edit(User $user, array $data)
    {
        return array_merge($user->toArray(), $data);
    }

    /**
     * make
     *
     * @param  array $data
     * @return void
     */
    public function store(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'user_role_id' => $data['user_role_id'],
            'password' => Hash::make($data['password']),
        ]);

        return $user;
    }


    /**
     * update Password
     *
     * @param  array $data
     * @return void
     */
    public function updatePassword(array $data, int $user_id)
    {
        $user = $this->users->withTrashed()->find($user_id);
        // $user = $this->users->find($user_id);
        $user->password = bcrypt($data['password']);
        return $user->update();
    }

    /**
     * delete
     *
     * @param  int $user_id
     * @return void
     */
    public function delete(int $user_id)
    {
        $users = $this->users->find($user_id);
        $admin_count = User::where('user_role_id', '1')->count();
        if ($admin_count == 1 && $users->user_role_id == 1) {
            return "Last admin can not delete";
        } else {
            $users->delete();
            return "success";
        }
    }

    /**
     * restore
     *
     * @param  int $user_id
     * @return void
     */
    public function restore(int $user_id)
    {
        $users = $this->users->withTrashed()->find($user_id);
        $users->restore();
    }

    /**
     * SendUserFeedback
     * send user feedback to admin end
     *
     * @param int $user_id
     * @param array $data
     *
     * @return void
     */
    public function sendUserFeedback(int $user_id, array $data)
    {
        $user = $this->users->find($user_id);
        $data['user_id'] = $user_id;
        $data['user_name'] = $user->name;
        if(isset($user->email)){
            $data['user_email'] = $user->email;
        }
        return $this->user_feedback->create($data);
    }


}
