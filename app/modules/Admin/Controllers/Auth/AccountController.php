<?php
namespace Admin\Controllers\Auth;


use CodeIgniter\Controller;
use Config\Email;
use Config\Services;
use Admin\Models\UserModel;

class AccountController extends Controller
{

	/**
	 * Access to current session.
	 *
	 * @var \CodeIgniter\Session\Session
	 */
	public $session;

	/**
	 * Authentication settings.
	 */
	protected $config;


    //--------------------------------------------------------------------

	public function __construct()
	{
		// start session
		$this->session = Services::session();

		// load auth settings
		$this->config = config('Auth');

		// load layout helper
		helper('layout_helper');
	}

    //--------------------------------------------------------------------

	/**
	 * Displays account settings.
	 */
	public function account()
	{
		if (! $this->session->isLoggedIn) {
			return redirect()->to('login');
		}
		return view($this->config->views['account'], ['config' => $this->config
		,'userData' => $this->session->userData]);
		// return render($this, 'auth/account', [
		// 	'userData' => $this->session->userData,
		// 	'config' => $this->config
		// ]);
	}

    //--------------------------------------------------------------------

	/**
	 * Updates regular account settings.
	 */
	public function updateAccount()
	{
		// update user, validation happens in model
		$users = new UserModel();
		$users->setRules('updateAccount');
		$user = [
			'id'  	=> $this->session->get('userData.id'),
			'name' 	=> $this->request->getPost('name')
		];

		if (! $users->save($user)) {
			return redirect()->back()->withInput()->with('errors', $users->errors());
        }

        // update session data
        $this->session->push('userData', $user);

        return redirect()->to('account')->with('success', lang('Auth.updateSuccess'));
	}

    //--------------------------------------------------------------------

	/**
	 * Handles email address change.
	 */
	public function changeEmail()
	{
		helper('text');

		// check password
		$users = new UserModel();
		$user = $users->find($this->session->get('userData.id'));
		if (
			empty($this->request->getPost('password')) ||
			! password_verify($this->request->getPost('password'), $user['password_hash'])
		) {
			return redirect()->to('account')->withInput()->with('error', lang('Auth.wrongCredentials'));
		}

		// update user with temporary new email, validation happens in model
		$users->setValidationRules('changeEmail');
		$updatedUser = [
			'id'			=> $this->session->get('userData.id'),
			'new_email'		=> $this->request->getPost('new_email'),
			'activate_hash'	=> random_string('alnum', 32)
		];
		if (! $users->save($updatedUser)) {
			return redirect()->back()->withInput()->with('errors', $users->errors());
        }

        // update session data
        $this->session->push('userData', ['new_email' => $updatedUser['new_email']]);

		// send confirmation email to new address
		helper('auth');
        send_confirmation_email($updatedUser['new_email'], $updatedUser['activate_hash']);

		// send notification email to old address
        send_notification_email($user['email']);

        return redirect()->to('account')->with('success', lang('Auth.emailUpdateStarted'));
	}

    //--------------------------------------------------------------------

	/**
	 * Verifies and sets new e-mail address.
	 */
	public function confirmNewEmail()
	{
		$users = new UserModel();

		// check token and if new email is set
		$user = $users->where('activate_hash', $this->request->getGet('token'))
			->where('new_email !=', null)
			->first();

		if (! $user) {
			return redirect()->to('account')->with('error', lang('Auth.activationNoUser'));
		}

		// set new email as current
		$updatedUser['id'] = $user['id'];
		$updatedUser['email'] = $user['new_email'];
		$updatedUser['new_email'] = null;
		$updatedUser['activate_hash'] = null;
		$users->save($updatedUser);

		// update session data, if user is logged in
		if ($this->session->isLoggedIn) {
			$this->session->push('userData', [
				'email'		=> $updatedUser['email'],
            	'new_email'	=> null
        	]);

        	return redirect()->to('account')->with('success', lang('Auth.confirmEmailSuccess'));
		}

		return redirect()->to('login')->with('success', lang('Auth.confirmEmailSuccess'));
	}

    //--------------------------------------------------------------------

	/**
	 * Handles password change.
	 */
	public function changePassword()
	{
		// validate request
		$rules = [
			'password' 	=> 'required|min_length[5]',
			'new_password' => 'required|min_length[5]',
			'new_password_confirm' => 'required|matches[new_password]'
		];

		if (! $this->validate($rules)) {
			return redirect()->to('account')->withInput()
				->with('errors', $this->validator->getErrors());
		}

		// check current password
		$users = new UserModel();
		$user = $users->find($this->session->get('userData.id'));

		if (
			! $user ||
			! password_verify($this->request->getPost('password'), $user['password_hash'])
		) {
			return redirect()->to('account')->withInput()->with('error', lang('Auth.wrongCredentials'));
		}

		// update user's password
		$updatedUser['id'] = $this->session->get('userData.id');
		$updatedUser['password'] = $this->request->getPost('new_password');
		$users->save($updatedUser);

		// redirect to account with success message
		return redirect()->to('account')->with('success', lang('Auth.passwordUpdateSuccess'));
	}

    //--------------------------------------------------------------------

	/**
	 * Deletes user account.
	 */
	public function deleteAccount()
	{
		// check current password
		$users = new UserModel();
		$user = $users->find($this->session->get('userData.id'));

		if (
			! $user ||
			! password_verify($this->request->getPost('password'), $user['password_hash'])
		) {
			return redirect()->back()->withInput()->with('error', lang('Auth.wrongCredentials'));
		}

		// delete account from DB
		$users->delete($this->session->get('userData.id'));

		// log out user
		$this->session->remove(['isLoggedIn', 'userData']);

		// redirect to register with success message
		return redirect()->to('register')->with('success', lang('Auth.accountDeleted'));
	}

}
