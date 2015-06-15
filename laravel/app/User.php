<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Toddish\Verify\Models\User as VerifyUser;
use App\Models\UserInfo;

class User extends VerifyUser implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['id', 'name', 'email', 'password'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token', 'salt', 'disabled', 'created_at', 'updated_at', 'deleted_at'];
	
	
	public function info() {
        return $this->hasOne('App\Models\UserInfo');
    }
     
    public function comments() {
        return $this->hasMany('App\Models\Comment');
    }
     
    public function scans() {
        return $this->hasMany('App\Models\Scan');
    }
     
    public function rankings() {
        return $this->hasMany('App\Models\Rating');
    }
     
    public function tokens() {
        return $this->hasMany('App\Models\Token');
    }
	
	
	public function reports(){
		return $this->hasMany('App\Models\Report');
	}
	
	public function infonew(){
		return $this->hasOne('App\Models\UserInfo');
	}
}
