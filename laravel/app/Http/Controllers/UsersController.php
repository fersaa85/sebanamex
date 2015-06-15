<?php namespace App\Http\Controllers;

use Input;
use Session;
use Config;	
use Response;
use DB;
use Auth;
use Validator;
use View;
use Redirect;
use App\Models\Coupon;
use App\Models\Scan;
use App\Models\Rating;
use App\Models\Comment;
use App\Models\Token;
use App\User;

class UsersController extends Controller {


	public $restful = true;


	public function getIndex() {
		
		$limit 	= Input::get('limit', 20);
		$page 	= Input::get('page' , 1) - 1;
		$search = Input::get('search' , '');
		$order 	= Input::get('order' , 'username|asc');
		
		$rows   = User::leftJoin("user_role_user",  "user_role_user.user_id", "=", "user_users.id")
					  ->leftJoin("user_roles", 		"user_roles.id", 		  "=", "user_role_user.role_id")
					  ->leftJoin("user_users_info", 	 "user_users.id", 			   "=", "user_users_info.user_id")
					  ->where("user_roles.id", "!=", "1");
					  
		if ($search != "") {
			
			$where_search = '(username LIKE ? OR email LIKE ? OR level LIKE ? OR user_users_info.name LIKE ? OR user_users_info.surename LIKE ?)';
			$rows->whereRaw($where_search, array("%$search%", "%$search%", "%$search%", "%$search%", "%$search%"));
			$total = $rows->count();
			
		} else {
			$total = $rows->count();
		}
				
		$order = explode("|", $order);
		$rows->take($limit)->skip($page * $limit)->orderBy($order[0], $order[1]);
		
		$rows = $rows->get(array("user_users.*", "user_users_info.name", "user_users_info.surename", 
								   DB::raw("user_roles.id AS role_id"), DB::raw("user_roles.name AS level")));
				
		return View::make('admin.users.index')->with("rows",   $rows)
											  ->with("search", $search)
											  ->with("page",   $page)
											  ->with("limit",  $limit)
											  ->with("total",  $total)
											  ->with("show",   min(($page + 1) * $limit, $total))
											  ->with("torder", $order[1]=="asc"?"desc":"asc");
											  
	}
	
	
	public function getDelete($id = null) {	
		
		if (!$data = $this->_validate($id)) {
			return Redirect::to('admin/users');
		}
			
		return View::make('admin.users.delete')->with("data", $data->email);
	}
	
	
	public function postDelete($id = null) {	
		
		if (!$data = $this->_validate($id)) {
			return Redirect::to('admin/users');
		}
			
		$data->delete();
		
		return Redirect::to('admin/users')->with("message", "Usuario eliminado!");
	}
	
	
	public function getEdit($id = null) {
	
		if (!$data = $this->_validate($id)) {
			return Redirect::to('admin/users');
		}
		
		$view = View::make('admin.users.edit')->with("data", $data);			
		return $view;
		
	}
	
	
	public function postEdit($id = null) {
			
		if (!$data = $this->_validate($id)) {
			return Redirect::to('admin/users');
		}	
				
		$input = Input::all();
		$password_validate = strlen(Input::get('password')) > 0 || strlen(Input::get('password'));
		if ($password_validate) {
			$rules = array(
				'password'  => 'required|between:6,16',
				'cpassword' => 'required|same:password',
			);
		} else {
			$rules = array();
		}
		
		$rules = $rules + array("name" 		=> "required|between:3,30",
								"surename" 	=> "required|between:3,30",
								"email" 	=> "required|email");
		
		$validation = Validator::make($input, $rules);						
		if ($validation->fails()) {
			return Redirect::to('admin/users/edit/'.$id)->withErrors($validation)->withInput();
		} else {
			
			if ($password_validate) {
				$data->password = Input::get('password');
			}	
			$data->email    = Input::get('email');		
			$data->disabled = Input::get("disabled", 1);
			$data->save();
			
			$info = array(
				"user_id"		=> $data->id,
				"name"			=> Input::get('name'),
				"surename"		=> Input::get('surename'),
			);
			$data->info()->update($info);
			
			return Redirect::to('admin/users')->with("message", "El usuario ha sido guardado");	
		}
		
	}
	
	
	public function getAdd() {
		return View::make('admin.users.add');
	}
	
	public function postAdd() {
	
		$input = Input::all();
		$rules = array(	"email" 	=> "required|min:5|max:12",
					 	"password" 	=> "required|between:6,16",
					 	"cpassword" => "required|same:password");

	   
		$validation = Validator::make($input, $rules);
		if ($validation->fails()) {
			return Redirect::to('admin/users/add')->with_errors($validation)->with_input();
		} else {
		
			$user = new User();
			$user->email 		= Input::get('email');
			$user->password 	= Input::get('password');
			$user->name 		= Input::get('name');
			$user->surename 	= Input::get('surename');
			$user->disabled	 	= 0;
			$user->deleted	 	= 0;
			$user->save();

			return Redirect::to('admin/users')->with("message", "Usuario agregado!");
		}
		
	}
	
	public function getMe() {
		$user = User::find( Auth::user()->id );
		return View::make('admin.users.me')->with("user", $user);
	}
	
	public function postMe() {
			
		$input = Input::all();
		$password_validate = strlen(Input::get('password')) > 0 || strlen(Input::get('password'));
		if ($password_validate) {
			$rules = array(
				'password'  => 'required|between:6,16',
				'cpassword' => 'required|same:password',
			);
		} else {
			$rules = array();
		}
		
		$rules = $rules + array("name" 		=> "required|between:3,30",
								"surename" 	=> "required|between:3,30");
		
		$validation = Validator::make($input, $rules);						
		if ($validation->fails()) {
			return Redirect::to('admin/users/me')->withErrors($validation)->withInput();
		} else {
			
			$data = Auth::user();
			
			if ($password_validate) {
				$data->password = Input::get('password');
				$data->save();
			}				
			$info = array(
				"name"			=> Input::get('name'),
				"surename"		=> Input::get('surename'),
			);
			$data->info()->update($info);
			
			return Redirect::to('admin/users/me')->with("message", "El usuario ha sido guardado");	
		}
		
	}
	
		
	
	
	/**
	 * get_profile function.
	 * 
	 * @description Perfil de los usuarios registrados de la base de datos		 
	 * @access public
	 * @param mixed $id (default: null)
	 * @return void
	 */
	 
	public function getProfile($id = null) {
	
		if (!$data = $this->_validate($id)) {
			return Redirect::to('admin');
		}	
		
		$comments = Comment::leftJoin('coupons', 'comments.coupon_id', '=', 'coupons.id')
							->whereNull('coupons.deleted_at')
							->get(array('coupons.id', 'coupons.title', 'comments.comment', 'comments.created_at'));					
					
		return View::make('admin.users.profile')
					->with("data", 	   $data)
					->with("comments", $comments);
		
	}
	
	
	private function _validate($id) {

		if ($id == null) {
			return false;
		}

		$data = User::find($id);
		if ($data === null) {
			return false;
		}
		
		if ($data->deleted_at != NULL) {
			return false;
		}
		
		if ($data->is('Super Admin')) {
			return false;
		}
		
		return $data;
	}
	
}