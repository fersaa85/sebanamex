<?php namespace App\Http\Controllers;

use Request;
use Input;
use Session;
use Config;	
use Response;
use DB;
use Auth;
use Validator;
use URL;
use Hash;
use App\Models\Report;
use App\Models\Coupon;
use App\Models\Scan;
use App\Models\Rating;
use App\Models\Comment;
use App\Models\Token;
use App\Models\Notification;
use App\Models\Category;
use App\Models\Partner;
use App\Models\PartnerBranch;
use App\Models\MedicalNetwork;
use App\Models\CouponHistory; 
use App\User;



class ApiController extends Controller {

	public $restful = true;

	public function getIndex() {
		return "";
	}
	
	





	/*********************************
	     REGISTRO / LOGIN / PROFILE
	**********************************/
		
		
	/**
	 * login
	 *
	 * Logs the user generating a token
	 *
	 * @access public
	 * @params soid 	=> string
			   password => string
	 * @return json
	 * @author Fernando Saavedra
	 *
	 *	https://secure.kreativeco.com/se-banamex/actions/webservices.php?task=login&geid=00001234&password=123qaz&device=android 
	 *
	*/
	 
	public function postLogin() {	
		
		$username    = Input::get('soid');
		$password    = Input::get('password');
		$credentials = array('username' => $username, 'password' => $password);
								
		try {
			Auth::attempt($credentials);
		} catch( \Exception $e ) {
			return Response::json(array("success"=>false, "service"=>__FUNCTION__, "message"=>"Wrong credentials"));
		}
		
		return Response::json(array("success"=>true,
									"service"=>__FUNCTION__,
									"token"=>Session::token()));
	}


	
	
	
	/**
	 * postRegister
	 *
	 * recording a user data in batad base
	 * the soid with which it has registered collated
	 * Medical Network assigns agree to soid
	 *
	 * @access public
	 * @params soid => string
			   email => string
			   password => string
			   genre => string
			   blood_type => integer
			   full_name => string
			   proflie_image => file
	 * @return json
	 * @autor Fernando Saavedra
	 *
	 *
	 */
	public function postRegister() {
		
		$input = Input::all();
		
		
		$rules = array(	"email" 			=> "required|email|unique:user_users,username,".Input::get('email'),
						"surename" 			=> "",
					 	"password"		 	=> "required|between:6,16",
						"genre"				=> "required|in:man,woman",
						"medical_service"	=> "",
						"blood_type"		=> "required|numeric",
						"hobbies"			=> "",
						"proflie_image"		=> "",
						"full_name"			=> "required");
	   
	   
		$validation = Validator::make($input, $rules);
		if ($validation->fails()) {
			return Response::json(array("service"=>__FUNCTION__, "status"=>true, "error"=>true, "message"=>$validation->messages()));
		} else {
			
		
			$user = new User();
			$user->username = Input::get('soid');
			$user->email 	= Input::get('email');
			$user->soid = Input::get('soid');
			$user->password = Input::get('password');
			$user->disabled = 0;
			$user->save();
		
				
			$info = array(
				"user_id"			=> $user->id,
				"name"				=> Input::get('full_name'), 	
				"geid"				=> Input::get('geid'), 
				"full_name"			=> Input::get('full_name'), 
				"genre"				=> Input::get('genre'), 
				"medical_service"	=> Input::get('medical_service'), 
				"blood_type"		=> Input::get('blood_type'), 
				"hobbies"			=> Input::get('hobbies'), 
				"proflie_image"		=> Input::get('proflie_image', 'default.png'), 
			);
		
			$user->info()->insert($info);
			$user->save();
		
		
			Auth::login($user);
	
			return Response::json(array("success"=>true,
										"service"=>__FUNCTION__,
										"token"=>Session::token()));
		
		}
		
		
	}


	/**
	 * postResetpassword
	 *
	 * retrieves the user's password
	 * lets you enter soid / email to retrieve the password
	 *
	 * @access public
	 * @params soid => string
			   email => string
	 * @return json
	 * @autor Fernando Saavedra
	 *
	 *
	 */
	public function postResetpassword(){
	
		$request = Request::all();
	
		$user = User::where("soid", "=", $request["soid"])->first();
	
	
		$password = Hash::make('123qaz');
	
		$sql = "UPDATE user_users
				SET user_users.password = '{$request["soid"]}'
				WHERE id = {$user->id}
				LIMIT 1";
	
		DB::statement($sql);
					
		
		return Response::json(array("success"=>true,
									"service"=>__FUNCTION__,
									"password"=>$password));

	
	}
	
	
	
	
	
	
	
	/**
	 * postReports
	 *
	 * Up a report of incidence user
	 *
	 * @access public
	 * @params soid => string
			   message => string
			   report_category_id => integer
	 * @return json
	 * @autor Fernando Saavedra
	 *
	 *
	 */
	public function postReports(){
	
	
		$request = Request::all();
		
	
	
		$user = User::where("soid", "=", $request["soid"])->first();
		//dd($user->id);
		
		
		$report = new Report();
		$report->soid = $request["soid"];
		$report->message = $request["message"];
		$report->user_id = $user->id;
		$report->report_category_id = $request["report_category_id"];
		$report->save();
		
	
		return Response::json(array("success"=>true,
									"service"=>__FUNCTION__,
									"message"=>"Reporte levantado exitosamente"));

	
	}
	
	
	
	/**
	 * getDashboard
	 *
	 * regresa la informacion del escritorio del usuario
	 *
	 * @access public
	 * @params 
	 * @return json
	 * @autor Fernando Saavedra
	 *
	 * https://secure.kreativeco.com/se-banamex/api/dashboard
	 *
	 */
	
	public function getDashboard(){
	
		$request = Request::all();
		$user = Auth::user();
		
		
		
		
		if($user === null){
			return Response::json(array("success"=>true,
										"service"=>__FUNCTION__,
										"error"=>true,
										"message"=>"Token no valido"));
		}
		
		
		
		$limit = 1;
		$coupon = Coupon::orderBy('id', 'DESC')->take($limit)->get();
		
		
		$notification = Notification::where('notifications.id', '>', $request["notification_id"])
									->where('notifications.notification_type', '=', 1)	
									->orderBy('id', 'DESC')
									->take(1)
									->get();
									
									
		$notificationList = Notification::where('notifications.id', '>', $request["notification_id"])
										->orderBy('id', 'DESC')
										->get();
		
		
		//dd( count($notificationList) );
		
	
		return Response::json(array("success"			=> true,
									"service"			=> __FUNCTION__,
									"coupon" 			=> $coupon,
									"notification" 		=> $notification,
									"notification_list"	=> $notificationList,
									"count"				=> count($notificationList),));

	}
	
	
	
	
	/**
	 * getFavoritescategorys
	 *
	 * 4 category returns the user to use more
	 *
	 * @access public
	 * @params 
	 * @return json
	 * @autor Fernando Saavedra
	 *
	 * https://secure.kreativeco.com/se-banamex/api/favoritescategorys
	 *
	 */
	public function getFavoritescategorys(){
	
		$request = Request::all();
		$user = Auth::user();
		
				
		if($user === null){
			return Response::json(array("success"=>true,
										"service"=>__FUNCTION__,
										"error"=>true,
										"message"=>"Token no valido"));
		}
		
		
		
		
		$category = Category::orderByRaw("RAND()")
							->take(4)
							->get();
		
		
		
		
		
		return Response::json(array("success"		=> true,
									"service"		=> __FUNCTION__,
									"favorites" 		=> $category,
									"message"		=> "Categorias favoritas"));
	
	
	}
	
	
	
	
	
	
	
	/**
	 * getCouponsbycategory
	 *
	 * returns the current coupons selected category
	 *
	 * @access public
	 * @params category_id => integer
	 * @return json
	 * @autor Fernando Saavedra
	 *
	 * https://secure.kreativeco.com/se-banamex/api/couponsbycategory?category_id=1
	 *
	 */
	public function getCouponsbycategory(){
	
		$request = Request::all();
		$user = Auth::user();
		
		
		if($user === null){
			return Response::json(array("success"=>true,
										"service"=>__FUNCTION__,
										"error"=>true,
										"message"=>"Token no valido"));
		}
		
		
		$couponsByCategory = Coupon::where("category_id", "=", $request["category_id"])
								   ->orderBy("created_at", "DESC")
								   ->get();
								
								
		return Response::json(array("success"		=> true,
									"service"		=> __FUNCTION__,
									"coupons_by_category" 			=> $couponsByCategory,));						
		
		
	}
	
	
	
	/**
	 * getDetailsbycoupons
	 *
	 * returns the detailed information of the selected coupon
	 *
	 * @access public
	 * @params category_id => integer
	 * @return json
	 * @autor Fernando Saavedra
	 *
	 * https://secure.kreativeco.com/se-banamex/api/detailsbycoupons?coupon_id=386
	 *
	 */
	public function getDetailsbycoupons(){
	
		$request = Request::all();
		$user = Auth::user();
		
		
		if($user === null){
			return Response::json(array("success"=>true,
										"service"=>__FUNCTION__,
										"error"=>true,
										"message"=>"Token no valido"));
		}
		
		$coupon = Coupon::where("id", "=", $request["coupon_id"])
						->get();
						


		//$comment = $this->returnCommentsByCoupon();
		
		
		$comment = $comments = Comment::leftJoin('user_users', 'comments.user_id', '=', 'user_users.id')
									   ->leftJoin('coupons', 'comments.coupon_id', '=', 'coupons.id')
									   ->whereNull('user_users.deleted_at')
									   ->whereNull('coupons.deleted_at')
									   ->where('coupons.id', '=', $request["coupon_id"])
									   ->orderBy("comments.id", "DESC")
									   ->get(array('comments.id','comments.comment', 'comments.ranking'))
									   ->toArray();
						
		
		return Response::json(array("success"		=> true,
									"service"		=> __FUNCTION__,
									"coupon"		=> $coupon,
									"comment" 		=> $comment,));		
		
		
		
		
		
		
		
	
	
	}
	
	
	
	/**
	 * returnCommentsByCoupon
	 *
	 * returns comments that users have made ​​on a coupon
	 *
	 * @access public
	 * @params coupon_id => integer
	 * @return json
	 * @autor Fernando Saavedra
	 *
	 *
	 */
	public function returnCommentsByCoupon(){
	
		$request = Request::all();
		$user = Auth::user(); 
		
		$comments = Comment::leftJoin('user_users', 'comments.user_id', '=', 'user_users.id')
						   ->leftJoin('coupons', 'comments.coupon_id', '=', 'coupons.id')
						   ->whereNull('user_users.deleted_at')
						   ->whereNull('coupons.deleted_at')
						   ->where('coupons.id', '=', $request["coupon_id"])
						   ->orderBy("comments.id", "DESC")
						   ->get(array('comments.id','comments.comment', 'comments.ranking'))
						   ->toArray();
						   
			
		return $comments;
	}
	
	
	
	
	

	
	/**
	 * getPartnersbranchs
	 *
	 * returns branches near where applicable promotion
	 *
	 * @access public
	 * @params partner_id => integer
	 * @return json
	 * @autor Fernando Saavedra
	 *
	 * https://secure.kreativeco.com/se-banamex/api/partnersbranchs?partner_id=1
	 *
	 */
	public function getPartnersbranchs(){ 
		
		$request = Request::all();
		$user = Auth::user(); 
		
		
		if($user === null){
			return Response::json(array("success"=>true,
										"service"=>__FUNCTION__,
										"error"=>true,
										"message"=>"Token no valido"));
		}
		
		
		//$partner = Partner::where()get();
		
		
		$partnerBranch = PartnerBranch::where('partner_id', '=', $request["partner_id"])
									  ->get();
		
		//dd( $partnerBranch->id );
		
		return Response::json(array("success"		=> true,
									"service"		=> __FUNCTION__,
									"partners_branchs" 			=> $partnerBranch,));	
	
	}
	
	
	
	
	/**
	 * getDetailpartnersbranchs
	 *
	 * returns the details of the selected branch
	 *
	 * @access public
	 * @params partner_branch_id => integer
	 * @return json
	 * @autor Fernando Saavedra
	 *
	 *
	 */
	public function getDetailpartnersbranchs(){
			
		$request = Request::all();
		$user = Auth::user(); 
		
		if($user === null){
			return Response::json(array("success"=>true,
										"service"=>__FUNCTION__,
										"error"=>true,
										"message"=>"Token no valido"));
		}
		
		
		
		$partnerBranch = PartnerBranch::where('id', '=', $request["partner_branch_id"])
									  ->get();
		
		
		
		return Response::json(array("success"					=> true,
									"service"					=> __FUNCTION__,
									"detail_partners_branchs" 	=> $partnerBranch,));	
	
	}
	
	
	
	/**
	 * getMedicaletwork
	 *
	 * returns the list of medical network by category
	 *
	 * @access public
	 * @params medical_network_categorys_id => integer
			   medical_network_level_id => integer
	 * @return json
	 * @autor Fernando Saavedra
	 *
	 * 	https://secure.kreativeco.com/se-banamex/api/medicalnetwork?medical_network_categorys_id=1&medical_network_level_id=1
	 *
	 */
	public function getMedicalnetwork(){
		$request = Request::all();
		$user = Auth::user(); 
		
		if($user === null){
			return Response::json(array("success"=>true,
										"service"=>__FUNCTION__,
										"error"=>true,
										"message"=>"Token no valido"));
		}
		
	
		$medicalNetwork = MedicalNetwork::where('medical_network_categorys_id', '=', $request["medical_network_categorys_id"])
										->where('medical_network_level_id', '=', $request["medical_network_level_id"])
										->get();
		
		return Response::json(array("success"		=> true,
									"service"		=> __FUNCTION__,
									"medical_network" 			=> $medicalNetwork,));	
	
	
	}
	
	
	
	/**
	 * getDetailmedicaletwork
	 *
	 * back detail chosen medical network
	 *
	 * @access public
	 * @params medical_network_id => integer
	 * @return json
	 * @autor Fernando Saavedra
	 *
	 * https://secure.kreativeco.com/se-banamex/api/detailmedicaletwork?medical_network_id=1
	 *
	 */
	public function getDetailmedicaletwork(){
		$request = Request::all();
		$user = Auth::user(); 
		
		if($user === null){
			return Response::json(array("success"=>true,
										"service"=>__FUNCTION__,
										"error"=>true,
										"message"=>"Token no valido"));
		}
	
	
		$medicalNetwork = MedicalNetwork::where('id', '=', $request["medical_network_id"])
										->get(); 

	
		return Response::json(array("success"			=> true,
									"service"			=> __FUNCTION__,
									"medical_network"	=> $medicalNetwork,));	
	
	}
	
	
	
	/**
	 * getSearchmedicalnetwork
	 *
	 * back detail chosen medical network
	 *
	 * @access public
	 * @params search => string
	 * @return json
	 * @autor Fernando Saavedra
	 *
	 *
	 */
	public function getSearchmedicalnetwork(){
	
		$request = Request::all();
		$user = Auth::user(); 
		
		if($user === null){
			return Response::json(array("success"=>true,
										"service"=>__FUNCTION__,
										"error"=>true,
										"message"=>"Token no valido"));
		}
	
	//	$coupons->where("coupons.title", "LIKE", "%$search%");
		$medicalNetwork = MedicalNetwork::where('medical_network.name', 'LIKE', "%{$request["search"]}%")
										->get(); 

										
		return Response::json(array("success"					=> true,
									"service"					=> __FUNCTION__,
									"search_medical_network"	=> $medicalNetwork,));	
	
	}
	
	
	
	/**
	 * getSearchmedicalnetwork
	 *
	 * returns the history of the coupons that the user has acquired
	 *
	 * @access public
	 * @params user_id => string
	 * @return json
	 * @autor Fernando Saavedra
	 *
	 * 	//https://secure.kreativeco.com/se-banamex/api/couponhistory
	 *
	 */
	public function getCouponhistory(){
	
		$request = Request::all();
		$user = Auth::user(); 
		
		if($user === null){
			return Response::json(array("success"=>true,
										"service"=>__FUNCTION__,
										"error"=>true,
										"message"=>"Token no valido"));
		}
	
	
	//->join('contacts', 'users.id', '=', 'contacts.user_id')
		$couponHistory = CouponHistory::join("coupons", 	'coupons_history.coupon_id',	 '=',	 'coupons.id')
									  ->where('coupons_history.user_id', '=', $user->id)
									  ->orderBy('coupons_history.created_at', 'DESC')
									  ->get();
		
		
		return Response::json(array("success"		=> true,
									"service"		=> __FUNCTION__,
									"coupon_history" 		=> $couponHistory,));
	
	
	}
	
	
	/**
	 * getLogout
	 *
	 * logout the user from their session
	 *
	 * @access public
	 * @return json
	 *
	 */
	 
	public function getLogout() {
		Auth::logout();
		return Response::json(array("success"=>true, "service"=>__FUNCTION__));
	} 


	
	
	
	/**
	 * getProfile
	 *  
	 * returns the user's profile
	 *
	 * @access public
	 * @return json
	 *
	 * https://secure.kreativeco.com/se-banamex/api/profile
	 *
	 */
	public function getProfile() {	

		$user = Auth::user(); 
		
		if($user === null){
			return Response::json(array("success"=>true,
										"service"=>__FUNCTION__,
										"error"=>true,
										"message"=>"Token no valido"));
		}
	
		$user = Auth::user();
		$info = $user->info->toArray();
		
		unset($info['id']);
		unset($info['user_id']);
		unset($user['info']);
		
		$user = array_merge($info, $user->toArray());
				
		return Response::json(array("success"=>true, "service"=>__FUNCTION__, "profile"=>$user));
		
		
	}


	
	
	/**
	 * postProfile
	 *  
	 * updates the user's profile information	
	 *
	 * @access public
	 * @params email => string
			   genre => string
			   blood_type => integer
			   hobbies => string
			   proflie_image => file
			   full_name => string
	 * @return json
	 * @autor Fernando Saavedra
	 *
	 */
	public function postProfile() {	
		
		$input = Input::all();
		$rules = array(	"email" 			=> "required|email|unique:user_users,username,".Input::get('email'),
						"surename" 			=> "",
					 	"password"		 	=> "",
						"genre"				=> "required|in:man,woman",
						"medical_service"	=> "",
						"blood_type"		=> "required|numeric",
						"hobbies"			=> "",
						"proflie_image"		=> "",
						"full_name"			=> "required");
	   
		$validation = Validator::make($input, $rules);
		if ($validation->fails()) {
			return Response::json(array("service"=>__FUNCTION__, "status"=>true, "error"=>true, "message"=>$validation->messages()));
		} else {
		
			$password = Input::get('password');
			$user 	  = Auth::user();
			
			
				
			if (trim($password) != "") {
				$user->password = $password;
				$user->save();
			}
					
				
			$info = array(
				"name"				=> Input::get('full_name'), 	
				"full_name"			=> Input::get('full_name'), 
				"email"				=> Input::get('email'),
				"genre"				=> Input::get('genre'), 
				"blood_type"		=> Input::get('blood_type'), 
				"hobbies"			=> Input::get('hobbies'), 
				"proflie_image"		=> Input::get('proflie_image', 'default.png'), 
			);
			
			$user->info()->update($info);

			return $this->getProfile();
		}
	}


	
	
	
	

	
	
	
	/*********************************
	          METODOS VARIOS
	**********************************/
	
	/**
	 * coupons function.
	 * 
	 * @access public
	 * @param int $limit (default: 10)
	 * @description Devuelve el listado de cupones
	 * @return void
	 */
	 
	public function getCoupons($limit = 40) {
		return $this->postCoupons($limit);
	}
		
	public function postCoupons($limit = 40) {
	    	     
		$coupons = Coupon::leftJoin('categories', 		'coupons.category_id', 		 '=', 'categories.id')
						 ->leftJoin('coupons_premium',  'coupons_premium.coupon_id', '=', 'coupons.id');
	
	
		$radius 	= 6371; // Kilometros
		$lang	 	= Session::get('language');
		$latitude 	= Input::get('latitude', 19.509755469999998);
		$longitude 	= Input::get('longitude', -99.23768934);
		$categories = Input::get('categories', array(2));
		$search 	= Input::get('search', "");
				
		$distance = sprintf("( %d * acos( cos( radians(%s) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(%s) ) + sin( radians(%s) ) * sin( radians( latitude ) ) ) ) AS distance", $radius, $latitude, $longitude, $latitude);
	
		if (count($categories) > 0) {
			$coupons->whereIn("coupons.category_id", $categories);
		}
	
		if (strlen($search) > 0) {
			$coupons->where("coupons.title", "LIKE", "%$search%");
			$coupons->orWhere("coupons.description", "LIKE", "%$search%");
		}

		$coupons->where('coupons.disabled','=', '0');
		$coupons->take($limit);
		$coupons->orderBy('premium', 'DESC')->orderBy('distance', 'ASC');
		
		$fields = array('coupons.id', 'coupons.title',
						'coupons.category_id as category_id', 
						'coupons.description', 
						'coupons.restriction',
						'coupons.latitude', 
						'coupons.longitude',
						'categories.name as category', 
						DB::raw($distance), 
						DB::raw('IF(coupons_premium.coupon_id,1,0) AS premium'),);
		
		$status = $this->is_registered();
		$coupons = $coupons->get($fields);	
		$data = array();
		
		
		
		foreach ($coupons as $coupon) {
			
			if ($coupon->distance > 50 && $coupon->premium == 1) continue;
			if ($coupon->distance > 50 && $coupon->premium != 1 && count($data) >= 15) break;
			
			$cid = $coupon->id;
			$img = $coupon->thumb(200, 100);
			
			$coupon = $coupon->toArray();
			$coupon['rating']   = (float)Rating::where("coupon_id",  "=", $cid)->avg('value');
			$coupon['redems']   = (float)Scan::where("coupon_id",    "=", $cid)->count();
			$coupon['comments'] = (float)Comment::where("coupon_id", "=", $cid)->where("disabled", "=", 0)->count();
			$coupon['image'] 	= URL::to($img);
			//$coupon['image'] 	= $img;
			$data[] = $coupon;
			
		}	
		
		$output = array("data"=>$data, "service"=>__FUNCTION__);
		
		if (!$status) {
			$output['auth_error'] = true;
			$output['auth_message'] = "Session not started";
		}
		
		return Response::json($output);
	
	}	


	
	
	
	
	/**
	 * coupon function.
	 * 
	 * @access public
	 * @param int $limit (default: 10)
	 * @description Devuelve los datos de un cupon
	 * @return void
	 */
	 
	public function getCoupon($id = 0) {
	
		$coupon = Coupon::leftJoin('categories', 'coupons.category_id', '=', 'categories.id')
						->leftJoin('coupons_premium', 'coupons_premium.coupon_id', '=', 'coupons.id')
						->where('coupons.id', '=', $id)
						->get(array('coupons.id', 
									'coupons.title', 
									'categories.name as category', 
									'coupons.description', 
									'coupons.restriction',
									'coupons.latitude', 
									'coupons.longitude',
									DB::raw('IF(coupons_premium.coupon_id, 1, 0) AS premium'),))
						->toArray();
									
		if (count($coupon) == 0) {
			return Response::json(array());
		} 
		
		$coupon = $coupon[0];
		$coupon['ratings']  = (float)Rating::where("coupon_id",  "=", $coupon['id'])->avg('value');
		$coupon['redems']   = (float)Scan::where("coupon_id", 	 "=", $coupon['id'])->count();
		$coupon['comments'] = (float)Comment::where("coupon_id", "=", $coupon['id'])->where("disabled", "=", 0)->count();
	
		return Response::json($coupon);
	}


	
	
	
	
	/**
	 * comments function.
	 * 
	 * @access public
	 * @param mixed $id (default: null)
	 * @description Devuelve los comentarios de un cupón / Inserta un nuevo comentario
	 * @return void
	 */
	 
	public function getComments($coupon = 1) {
		return Response::json(array("data"=>$this->_get_comments($coupon), "service"=>__FUNCTION__));							 
	}
	
	public function postComments() {
		
		$coupon = Input::get('id', 0);
		
		if (!$this->_validate_coupon($coupon)) {
			return Response::json(array("service"=>__FUNCTION__, "status"=>true, "error"=>true, "message"=>"Coupon not found"));
		}
		
		$input = Input::all();
		$rules = array(	"comment" 	=> "required|between:5,255",
						"rating" 	=> "required|between:0,5",);
	   
		$validation = Validator::make($input, $rules);
		if ($validation->fails()) {
			return Response::json(array("service"=>__FUNCTION__, "status"=>true, "error"=>true, "message"=>$validation->messages()));
		} else {
						
			$comment = new Comment();
			$comment->user_id	= Auth::user()->id;
			$comment->coupon_id = $coupon;
			$comment->comment 	= Input::get('comment');
			$comment->disabled 	= 0;
			$comment->ranking 	= Input::get('ranking');
			$comment->save();
			
			$this->_post_rating($coupon, Input::get('rating'));
			
			return Response::json(array("succes"=> true, "data"=>$this->_get_comments($coupon), "service"=>__FUNCTION__));
		}
		
	}


	
	
	
	
	/**
	 * scan function.
	 * 
	 * @access public
	 * @return void
	 */
	 
	public function postScan() {
				
		$qr     = Input::get('qr', "");
		$coupon = Coupon::where("qr", "=", $qr)->first();

		if (!$coupon|| trim($qr) == "") {
			return Response::json(array("service"=>__FUNCTION__, "status"=>true, "error"=>true, "message"=>"Coupon not found"));
		}
		
		$user_id = Auth::user()->id; 
		$count   = Scan::where("user_id", "=", $user_id)
					 ->where("created_at", ">", date("Y-m-d", time()))->count();
		
		if ($count > 1) {
			return Response::json(array("service"=>__FUNCTION__, "status"=>true, "error"=>true, "message"=>"Scan error"));
		}
		
		$scan = new Scan();
		$scan->user_id 	 = $user_id;
		$scan->coupon_id = $coupon->id;	
		$scan->value 	 = 5;
		$scan->save();
		
		$redems = Scan::where("coupon_id", "=", $coupon->id)->count();	
		//$user->register->scans()->sum("value")
		$data = array("added"=>5, "total"=>0, "redems"=>$redems);
			
		return Response::json(array("service"=>__FUNCTION__, "status"=>true, "data"=>$data));
	}


	
	
	
	
	/**
	 * rating function.
	 * 
	 * @access public
	 * @return void
	 */
	 
	public function postRating() {
		
		$id		= Input::get('id', 0);
		$rating = Input::get('rating', 0);
		
		if (!$this->_validate_coupon($id)) {
			return Response::json(array("service"=>__FUNCTION__, "status"=>true, "error"=>true, "message"=>"Coupon not found"));
		}
			
		$status = $this->_post_rating($id, $rating);
		$rating = (float)Rating::where("coupon_id",  "=", $id)->avg('value');		
		
		return Response::json(array("service"=>__FUNCTION__, "status"=>$status, "rating"=>$rating));
		
	}


	
	
	
	
	/**
	 * notifications function.
	 * 
	 * @access public
	 * @return void
	 */
	public function postNotification() {
		
		$device_token = Input::get('token', '');
		$device_os    = Input::get('os', '');
		
		$token = Token::where('token', "=", $device_token)->where('os', "=", $device_os)->first();
		if (!$token) {
			$token = new Token();
			$token->token = $device_token;
			$token->os    = $device_os;
			$token->save();
		}
		
		$user = Auth::user()->id;
		$token->user_id = $user;
		$token->save();
			
		return Response::json(array("service"=>__FUNCTION__, "status"=>true));
	}

	
	
	
	
	
	/**************************
	     MÉTODOS PRIVADOS
	***************************/
	
	private function _validate_coupon($id) {
		return Coupon::find($id);
	}
	
	private function _get_rating($coupon) {
		
		if (!Auth::user()) return 0;
		
		$user   = Auth::user();
		$rating = Rating::where("user_id", "=", $user->id)->where("coupon_id", "=", $coupon)->first();
		return $rating ? $rating->value : 0;
		
	}
	
	private function _get_comments($coupon = 1) {
	
		$comments = Comment::leftJoin('user_users', 'comments.user_id', '=', 'user_users.id')
						   ->leftJoin('coupons', 'comments.coupon_id', '=', 'coupons.id')
						   ->whereNull('user_users.deleted_at')
						   ->whereNull('coupons.deleted_at')
						   ->where('coupons.id', '=', $coupon)
						   ->get(array(DB::raw('DATE_FORMAT(comments.created_at, "%e/%c/%Y") AS created'), 'comments.comment'))
						   ->toArray();
												 
		$rating = (float)Rating::where("coupon_id", "=", $coupon)->avg('value');
		
		return array("comments"=>$comments, "user_rating"=>$this->_get_rating($coupon), "rating"=>$rating);
	
	}
	
	
	public function _post_rating($coupon, $value) {
		
		if ($value == 0) {
			return false;
		}
		
		$user   = Auth::user()->id;	
		$rating	= Rating::where("user_id", "=", $user)->where("coupon_id", "=", $coupon)->first();
		
		if (!$rating) {
			$rating = new Rating();
		} 
		
		$rating->user_id 	= $user;
		$rating->coupon_id 	= $coupon;
		$rating->value	 	= $value;
		$rating->save();
			
		return true;
	}
    
    private function is_registered() {
	    return Auth::user() != null;
    }


	
	
	
	
	
	
	
	
	/*********************************
	     PROFILE 
	**********************************/
	
	
	public function getAddprofile(){
	
		//$input = Input::all();
		/*$rules = array(	"email" 	=> "required|email|unique:user_users,username,".Input::get('email'),
						"name" 		=> "required|between:3,30",
						"surename" 	=> "required|between:3,30",
					 	"password" 	=> "required|between:6,16",);*/
	   
	   
	   
	   //$rules = array(	"email" => "required|email|unique:user_users,username,".Input::get('email') );

		
		
		$request = Request::all();
		
	  
		$rules = array( "geid"				=> "required|numeric",
						"email" 			=> "required|email|unique:user_users,email,{$request["email"]}",
						"full_name"			=> "required",
						"genre"				=> "required|in:man,woman",
						"medical_service"	=> "required|numeric",
						"blood_type"		=> "",
						"hobbies"			=> "",
						"proflie_image"		=> "",
						);
		
		
		
		
		$messages = array( "geid"			=> "El geid es obligatorio",
						);
	   
	   
	   $validation = Validator::make($request, $rules, $messages);
	   
	   
	   if ($validation->fails()) {
			//dd($validation->errors());
			return Response::json(array("service"=>__FUNCTION__, "status"=>true, "error"=>true, "message"=>$validation->messages()));
		} else {
		
			echo "else";
		}
		
		
			$user = new User();
			$user->username = Input::get('email');
			$user->email 	= Input::get('email');
			$user->password = Input::get('password');
			$user->geid = $request["geid"];
			$user->disabled = 0;
			$user->save();
		
		
		//dd(Input::get('password')); //null
		
		//dd($user->id); //devuelve el ultimo id insertado
		
			$info = array(
				"user_id"	=> $user->id,
				
			);
		
		$user->info()->insert($info);
			/*
			$info = array(
				"user_id"	=> $user->id,
				"name"		=> Input::get('name'), 	
				"surename"	=> Input::get('surename'), 	
			);
		
			$user->info()->insert($info);
			$user->save();
			*/
		
		
		dd($request);
	
		return "getAddProfile";
	
	}
	
	
	
	//public function postAdd()
	
}