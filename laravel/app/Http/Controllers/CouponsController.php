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
use File;
use App\Models\Coupon;
use App\Models\Scan;
use App\Models\Rating;
use App\Models\Comment;
use App\Models\Category;
use App\User;

class CouponsController extends Controller {


	public $restful = true;
	
	public function __construct() {	
		/*	
		Validator::register('youtube', function($attribute, $value, $parameters) {
			return preg_match('~youtube.com/watch\?v=[a-zA-Z0-9-_]{10}~', Str::lower($value));
		});
		Validator::register('upload', function($attribute, $value, $parameters) {
			return !empty($value) && getimagesize($value['tmp_name']);
		});
		*/
	}
	
	
	
	
	/**
	 * delete function.
	 * 
	 * @description Elimina los cupones de la base de datos	 
	 * @access public
	 * @param mixed $id (default: null)
	 * @return void
	 */
	 
	public function getDelete($id = null) {	
		
		if (!$coupon = $this->_validate($id)) {
			return Redirect::to('admin/coupons');
		}
			
		return View::make('admin.coupons.delete')
				   ->with("data", $coupon->title);
				   
	}
	
	public function postDelete($id = null) {	
		
		if (!$coupon = $this->_validate($id)) {
			return Redirect::to('admin/coupons');
		}
			
		//$coupon->flush();	
		$coupon->delete();		
		
		return Redirect::to('admin/coupons')->with("message", "Cupón eliminado!");
	}
	
	

	
	/**
	 * edit function.
	 * 
	 * @description Edita los cupones de la base de datos	 	 
	 * @access public
	 * @param mixed $id (default: null)
	 * @return void
	 */
	 
	public function getEdit($id = null) {
	
		if (!$data = $this->_validate($id)) {
			return Redirect::to('admin/coupons/list');
		}
		
		$categories = Category::orderBy('name',  'asc')->lists('name', 'id');
						
		return View::make('admin.coupons.edit')
					->with("data", 	 	 $data)
					->with("categories", $categories);
		
	}
	
	public function postEdit($id = null) {
	
		if (!$data = $this->_validate($id)) {
			return Redirect::to('admin/coupons/list');
		}
						
		$input = Input::get();
		$rules = array(	"title"		=> "required|min:10|max:100",
					   	'file' 		=> 'image');
	   
		$validation = Validator::make($input, $rules);
			
		if ($validation->fails()) {
			return Redirect::to('admin/coupons/edit/'.$id)->withErrors($validation)->withInput();
			
		} else {
					
			$data->title 		= Input::get('title');
			$data->description 	= Input::get('description');
			$data->restriction 	= Input::get('restriction');
			$data->user_id	 	= Auth::user()->id;
			$data->latitude 	= Input::get('latitude');
			$data->longitude 	= Input::get('longitude');
			$data->category_id 	= Input::get('category');
			$data->disabled		= Input::get('disabled') != "" ? 0 : 1;
			$data->save();
			
			$image = Input::file('image.tmp_name');
			
			if (Input::hasFile('file')) {
				if (getimagesize(Input::file('file')->getRealPath())) {
					$data->image = Input::file('file')->getRealPath();
					$data->flush();
				}	
			}
		
			return Redirect::to('admin/coupons/edit/'.$id)->with("message", "Cupón editado!");
		}
		
	}
	
	
	
	
	/**
	 * add function.
	 * 
	 * @description Agrega cupones a la base de datos	 	 
	 * @access public
	 * @return void
	 */
	 
	public function getAdd() {
		
		$categories = Category::orderBy('name',  'asc')->lists('name', 'id');
		
		return View::make('admin.coupons.add')
				   ->with("categories", $categories);
				   
	}
	
	public function postAdd() {
	
		$input = Input::all();
		$rules = array(	"title"		=> "required|min:10|max:100",
					   	'file' 		=> 'image',);
	   
		$validation = Validator::make($input, $rules);
		if ($validation->fails()) {
			return Redirect::to('admin/coupons/add')->withErrors($validation)->withInput();
			
		} else {
		
			$coupon = new Coupon();
			$coupon->title 			= Input::get('title');
			$coupon->description 	= Input::get('description');
			$coupon->restriction 	= Input::get('restriction');
			$coupon->user_id 		= Auth::user()->id;
			$coupon->latitude 		= Input::get('latitude');
			$coupon->longitude 		= Input::get('longitude');
			$coupon->category_id 	= Input::get('category');
			$coupon->save();
						
			if (Input::hasFile('file')) {
				if (getimagesize(Input::file('file')->getRealPath())) {
					$data->image = Input::file('file')->getRealPath();
					$data->flush();
				}	
			}
			
			return Redirect::to('admin/coupons')->with("message", "Cupón agregado!");
			
		}
		
	}
	
	
	
	
	/**
	 * get_list function.
	 * 
	 * @description Obtiene el listado de todos los cupones		 
	 * @access public
	 * @return void
	 */
	 
	public function getIndex() {	
	
		$limit 	= Input::get('limit', 	10);
		$page 	= Input::get('page' , 	1) - 1;
		$search = Input::get('search' , '');
		$order 	= Input::get('order' , 	'id|desc');
			
		$rows   = Coupon::leftJoin('categories', 'coupons.category_id', '=', 'categories.id');
		
		if ($search != "") {
			
			$where_search = '(title LIKE ? OR coupon LIKE ? OR categories.name LIKE ?)';
			$rows->rawWhere($where_search, array("%$search%", "%$search%", "%$search%"));
			$total = $rows->count();
			
		} else {
			$total = $rows->count();
		}
		
		$order = explode("|", $order);
		$rows->take($limit)->skip($page * $limit)->orderBy($order[0], $order[1]);
		$rows = $rows->get(array('coupons.*', 'categories.name as category'));
		
		return View::make('admin.coupons.index')->with("rows", 	 $rows)
											    ->with("search", $search)
											    ->with("page", 	 $page)
											    ->with("limit",  $limit)
											    ->with("total",  $total)
											    ->with("show", 	 min(($page + 1) * $limit, $total))
											    ->with("torder", $order[1]=="asc"?"desc":"asc");
	
	}	
	
	
	/**************************
	     MÉTODOS PRIVADOS
	***************************/
		
	/**
	 * premium function.
	 * 
	 * @description Listado de cupones premium	 
	 * @access public
	 * @return void
	 */
	public function getPremium() {	
	
		$limit 	= Input::get('limit', 10);
		$page 	= Input::get('page' , 1) - 1;
		$search = Input::get('search' , '');
		$order 	= Input::get('order' , 'id|desc');
			
		$rows = Coupon::leftJoin('categories', 'coupons.category_id', '=', 'categories.id')
						 ->join('coupons_premium', 'coupons.id', '=', 'coupons_premium.coupon_id');
		
		if ($search != "") {
			
			$where_search = '(title LIKE ? OR categories.name LIKE ?)';
			$coupons->rawWhere($where_search, array("%$search%", "%$search%"));
			$total = $rows->count();
			
		} else {
			$total   = $rows->count();
		}
		
		
		$order = explode("|", $order);
		$rows->take($limit)->skip($page * $limit)->orderBy($order[0], $order[1]);
		$rows = $rows->get(array('coupons.*', 'categories.name as category'));
		
		
		$not_in = $rows->lists("id");
		$list   = Coupon::whereNotIn('id', $not_in)
						 ->orderBy('title', 'ASC')
						 ->lists('title', 'id');
						 
		return View::make('admin.coupons.premium')->with("rows", 	 $rows)
												  ->with("list", 	 $list)
											   	  ->with("search",   $search)
											   	  ->with("page", 	 $page)
											   	  ->with("limit", 	 $limit)
											   	  ->with("total", 	 $total)
											   	  ->with("show", 	 min(($page + 1) * $limit, $total))
											   	  ->with("torder",   $order[1]=="asc"?"desc":"asc");
	
	}
	
	public function postPremium() {	
		
		$id = Input::get('coupon', 0);
		
		if (!$coupon = $this->_validate($id)) {
			return Redirect::to('admin/coupons/premium');
		}
		
		$exists = DB::table('coupons_premium')->where('coupon_id', '=', $id)->get();	
			
		if (!$exists) {
			DB::table('coupons_premium')->insert(array('coupon_id' => $id));	
		} 
		
		return Redirect::to('admin/coupons/premium')->with("message", "Cupón agregado!");
		
	}
	
	
	
	
	/**
	 * remove function.
	 * 
	 * @description Elimina los cupones premium		 
	 * @access public
	 * @param mixed $id (default: null)
	 * @return void
	 */
	 
	 
	public function getDeletePremium($id = null) {	
		
		if (!$coupon = $this->_validate($id)) {
			return Redirect::to('admin/premium/');
		}
			
		return View::make('admin.coupons.delete-premium')
					->with("data", $coupon->title);
					
	}
	
	
	public function postDeletePremium($id = null) {	
	
		if (!$coupon = $this->_validate($id)) {
			return Redirect::to('admin/premium');
		}
		
		DB::table('coupons_premium')->where("coupon_id", "=", $id)->delete();
		
		return Redirect::to('admin/coupons/premium')->with("message", "Cupón removido!");
		
	}
		
	
	
	/**
	 * get_profile function.
	 * 
	 * @description Devuelve el perfil del cupón 
	 * @access public
	 * @param mixed $id (default: null)
	 * @return void
	 */
	 
	public function getProfile($id = null) {
	
		if (!$data = $this->_validate($id)) {
			return Redirect::to('admin/coupons/list');
		}
				
		$view = View::make('admin.coupons.profile')
					->with("data", $data);	

		return $view;
		
	}
	
	
	
	/**
	 * post_scans_by_period function.
	 * 
	 * @description Devuelve las estadísticas de escaneos por mes 
	 * @access public
	 * @param mixed $id (default: null)
	 * @return void
	 */
	 
	public function postScansByPeriod($id = null) {
		
		$id 	= Input::get('id', array(0));
		$period = Input::get('period', date("m/Y", time()));
		$period = join("-", array_reverse(explode("/", $period)))."-01";
		
		$scans = Scan::where('created_at', '>=', $period);
		$scans->whereIn('coupon_id', $id);

		$scans = $scans->groupBy('coupon_id')->groupBy('day')
					   ->get(array('coupon_id', DB::raw('COUNT(`id`) as qty'), DB::raw('DATE_FORMAT(`created_at`,"%Y-%m-%d") AS day')));
		
		$data = array();
		foreach ($scans as $scan) {
			$data[$scan->coupon_id][$scan->day] = $scan->qty;
		}
			
		return Response::json(array("data"=>$data, "period"=>$period));
		
	}
	
	
	
	/**
	 * get_qr function.
	 * 
	 * @description Genera la imagen de un código QR 
	 * @access public
	 * @param mixed $id (default: null)
	 * @param mixed $type (default: null)
	 * @return void
	 */
	 
	public function getQr($id = null, $size = null) {	
		
		if (!$data = $this->_validate($id)) {
			return Redirect::to('admin/coupons/list');
		}
		
		if ($size == null) $size = 400;
		
		$qr_path = $data->qr($size);
		$headers = array(
				'Content-Type'              => File::mimeType(File::extension($qr_path)),
				'Content-Disposition' 		=> 'attachment; filename="qr-'.$coupon->qr.'.png"',
				'Content-Description'       => 'File Transfer',
		);
		
		return Response::make(readfile($qr_path), 200, $headers);	
		
	}
	
	
	/**************************
	     MÉTODOS PRIVADOS
	***************************/
	
	private function _validate($id) {
		if ($id == null) {
			return false;
		}
		
		$coupon = Coupon::find($id);		
		if (!$coupon) {
			return false;
		}

		return $coupon;
	}
	
	
	private function _validate_user() {
		return Auth::is('Super Admin');
	}
	
	
	private function _get_user_client() {
		return Client::where('user_id', '=', Auth::user()->id)->first()->id;
	}
	
	
	private function _get_user_coupons($ids) {
		global $ids_check;
		$ids_check = $ids;
		$coupons = Coupon::leftJoin("clients", "coupons.client_id", "=", "clients.id")
							->where("clients.user_id", "=", Auth::user()->id)->get("coupons.id");
		$user_coupons = array_map( function($object) { 
			global $ids_check;
			return in_array($object->id, $ids_check) ? $object->id : false; 
		}, $coupons);			
		return $user_coupons;
	}
	
}