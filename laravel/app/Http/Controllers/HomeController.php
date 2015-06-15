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

class HomeController extends Controller {

	public $restful = true;
	
	
	public function getIndex() {
		return Redirect::to('admin');
	}
	
	public function getForm() {
		return View::make('home.form');
	}
	
	
	public function getAdmin() {
		if (Auth::check()) {
			if (Auth::user()->is('Super Admin')) {
				return Redirect::to('admin/coupons');
			}
    	} 
    	return View::make('home.login');
	}

	
	public function postAdmin() {
    	
    	$username    = Input::get('username');
		$password    = Input::get('password');
		$credentials = array('username' => $username, 'password' => $password, 'disabled' => 0);
				
		try {
			Auth::attempt($credentials);
		} catch( \Exception $e ) {
			return Redirect::to('admin')->with('login_errors', true);
		}
    	
    	return Redirect::to('admin/coupons');
    	
    }
    
    
    public function getLogin() {
		return Redirect::to('');
	}
    
    
    public function getLogout() {
		 Auth::logout();
		 return Redirect::to('');
	}	

}