<?php

namespace Admin\Controllers\User;

use App\Controllers\Basecontroller;
use App\Entities\Userentity;
use App\Models\GroupModel;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;
use Myth\Admin\Authorization\PermissionModel;

/**
 * Class UserController.
 */
class User extends Basecontroller
{
    use ResponseTrait;

    /** @var \App\Models\UserModel */
    protected $users;

    public function __construct()
    {
        $this->users = model('Admin\Models\UserModel');
    }

    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return mixed
     */
    public function index()
    {
        // die("sssssd");
        if ($this->request->isAJAX()) {
            $start = $this->request->getGet('start');
            $length = $this->request->getGet('length');
            $search = $this->request->getGet('search[value]');
            $order = UserModel::ORDERABLE[$this->request->getGet('order[0][column]')];
            $dir = $this->request->getGet('order[0][dir]');

            return $this->respond(Userentity::datatable(
                $this->users->getResource($search)->orderBy($order, $dir)->limit($length, $start)->get()->getResultObject(),
                $this->users->getResource()->countAllResults(),
                $this->users->getResource($search)->countAllResults()
            ));
        }

        return view('Admin\Views\Admin\User\index', [
            'title'    => lang('Users.user.title'),
            'subtitle' => lang('Users.user.subtitle'),
        ]);
    }

    /**
     * Show profile user or update.
     *
     * @return mixed
     */
    public function profile()
    {
        if ($this->request->getMethod() === 'post') {
            $id = user()->id;
            $validationRules = [
                'email'        => "required|valid_email|is_unique[users.email,id,$id]",
                'username'     => "required|alpha_numeric_space|min_length[3]|is_unique[users.username,id,$id]",
                'password'     => 'if_exist',
                'pass_confirm' => 'matches[password]',
            ];

            if (!$this->validate($validationRules)) {
                return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
            }

            $user = new User();

            if ($this->request->getPost('password')) {
                $user->password = $this->request->getPost('password');
            }

            $user->email = $this->request->getPost('email');
            $user->username = $this->request->getPost('username');

            if ($this->users->skipValidation(true)->update(user()->id, $user)) {
                return redirect()->back()->with('sweet-success', lang('Users.user.msg.msg_update'));
            }

            return redirect()->back()->withInput()->with('sweet-error', lang('Users.user.msg.msg_get_fail'));
        }

        return view('Admin\Views\User\profile', [
            'title' => lang('Users.user.fields.profile'),
        ]);
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return mixed
     */
    public function new()
    {
        $session =session();
        return view('Admin\Views\Admin\User\create', [
            'title'       => lang('Users.user.title'),
            'subtitle'    => lang('Users.user.add'),
            'errors'=>$session->getFlashdata('error'),
            'sweet_success'=>$session->getFlashdata('sweet-success'),
            'sweet_error'=>$session->getFlashdata('sweet-error')
        ]);
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return mixed
     */
    public function create()
    {
        $validationRules = [
            'active'       => 'required',
            'username'     => 'required|alpha_numeric_space|min_length[3]|is_unique[users.username]',
            'email'        => 'required|valid_email|is_unique[users.email]',
            'password'     => 'required',
            'pass_confirm' => 'required|matches[password]',
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        $this->db->transBegin();

        try {
            $id = $this->users->insert(new Userentity([
                'active'   => $this->request->getPost('active'),
                'email'    => $this->request->getPost('email'),
                'username' => $this->request->getPost('username'),
                'password_hash' => $this->request->getPost('password'),
            ]));

            // foreach ($permissions as $permission) {
            //     $this->authorize->addPermissionToUser($permission, $id);
            // }

            // foreach ($roles as $role) {
            //     $this->authorize->addUserToGroup($id, $role);
            // }

            $this->db->transCommit();
        } catch (\Exception $e) {
            $this->db->transRollback();

            return redirect()->back()->with('sweet-error', $e->getMessage());
        }

        return redirect()->back()->with('sweet-success', lang('Users.user.msg.msg_insert'));
    }

    /**
     * Return the editable properties of a resource object.
     *
     * @param int id
     *
     * @return mixed
     */
    public function edit($id)
    {
        $session=session();
        $data = [
            'title'       => lang('Users.user.title'),
            'subtitle'    => lang('Users.user.edit'),
            // 'permissions' => $this->authorize->permissions(),
            // 'permission'  => (new PermissionModel())->getPermissionsForUser($id),
            // 'roles'       => $this->authorize->groups(),
            // 'role'        => (new GroupModel())->getGroupsForUser($id),
            'sweet_success'=>$session->getFlashdata('sweet-success'),
            'sweet_error'=>$session->getFlashdata('sweet-error'),
            'user'        => $this->users->asArray()->find($id),
        ];
// pp($data,TRUE);
        return view('Admin\Views\Admin\User\update', $data);
    }

    /**
     * Add or update a model resource, from "posted" properties.
     *
     * @param int id
     *
     * @return mixed
     */
    public function update($id)
    {
        $validationRules = [
            'active'       => 'required',
            'username'     => "required|alpha_numeric_space|min_length[3]|is_unique[users.username,id,$id]",
            'email'        => "required|valid_email|is_unique[users.email,id,$id]",
            'password'     => 'if_exist',
            'pass_confirm' => 'matches[password]',
            'permission'   => 'if_exist',
            'role'         => 'if_exist',
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        $this->db->transBegin();

        try {
            $data = $this->request->getPost();
            $temp=[
                'active'=>$this->request->getPost('active'),
                'email'=>$this->request->getPost('email'),
                'username'=>$this->request->getPost('username'),
                'id'=>$id,
            ];
            $user = new Userentity($temp);

            if ($this->request->getPost('password')) {
               $user->password_hash = $this->request->getPost('password');
            }else{
                unset($user->password_hash);
            }

            // pp($user,TRUE);
            $this->users->skipValidation(true)->save($user);

            // // delete first permission from user
            // $this->db->table('auth_users_permissions')->where('user_id', $id)->delete();

            // foreach ($this->request->getPost('permission') as $permission) {
            //     // insert with new permission
            //     $this->authorize->addPermissionToUser($permission, $id);
            // }

            // // delete first groups from user
            // $this->db->table('auth_groups_users')->where('user_id', $id)->delete();

            // foreach ($this->request->getPost('role') as $role) {
            //     // insert with new role
            //     $this->authorize->addUserToGroup($id, $role);
            // }

            $this->db->transCommit();
        } catch (\Exception $e) {
            $this->db->transRollback();

            return redirect()->back()->with('sweet-error', $e->getMessage());
        }

        return redirect()->back()->with('sweet-success', lang('Users.user.msg.msg_update'));
    }

    /**
     * Delete the designated resource object from the model.
     *
     * @param int id
     *
     * @return mixed
     */
    public function delete($id)
    {
        if (!$found = $this->users->delete($id)) {
            return $this->failNotFound(lang('Users.user.msg.msg_get_fail'));
        }

        return $this->respondDeleted($found, lang('Users.user.msg.msg_delete'));
    }
}
