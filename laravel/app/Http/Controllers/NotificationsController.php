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
use App\Models\Token;
use App\Models\Notification;

class NotificationsController extends Controller {

	public $restful = true;
	
	
	
	/**
	 * delete function.
	 * 
	 * @description Elimina una notificacion		 
	 * @access public
	 * @param mixed $id (default: null)
	 * @return void
	 */
	 
	public function getDelete($id = null) {	
		
		if (!$data = $this->_validate($id)) {
			return Redirect::to('admin/notifications');
		}
			
		return View::make('admin.notifications.delete')
				   ->with("data", $data->message);	
	}
	
	public function postDelete($id = null) {	

		if (!$data = $this->_validate($id)) {
			return Redirect::to('admin/notifications');
		}
			
		$data->delete();
		
		return Redirect::to('admin/notifications')->with("message", "Notificación eliminada!");
	}
	
	
	
	
	/**
	 * list function.
	 * 
	 * @description Listado de las notificaciones de la base de datos		 
	 * @access public
	 * @return void
	 */
	
	public function getIndex() {	
	
		$limit 	= Input::get('limit' , 10);
		$page 	= Input::get('page'  , 1) - 1;
		$search = Input::get('search', '');
		$order 	= Input::get('order' , 'id|asc');
			
		$rows = Notification::leftJoin('coupons', 'notifications.coupon_id', '=', 'coupons.id');

		if ($search != "") {
			
			$where_search = '(message LIKE ? OR title LIKE ?)';
			$registers->whereRaw($where_search, array("%$search%", "%$search%"));
			$total = $rows->count();
			
		} else {
			$total = $rows->count();
		}
		
		$order = explode("|", $order);
		$rows->take($limit)->skip($page * $limit)->orderBy($order[0], $order[1]);
		$rows  = $rows->get(array('notifications.*', 'coupons.title'));
		
		
		$devices = DB::table('user_users_tokens')->count();
		$coupons = Coupon::orderBy('title', 'ASC')
				  		 ->select('coupons.*')
				  		 ->lists('title', 'id');
				  		 
		
		return View::make('admin.notifications.index')->with("rows", 	$rows)
													 ->with("devices",  $devices)
													 ->with("coupons", 	$coupons)
											   		 ->with("search",  	$search)
											   		 ->with("page", 	$page)
											   		 ->with("limit", 	$limit)
											   		 ->with("total", 	$total)
											   		 ->with("show", 	min(($page + 1) * $limit, $total))
											   		 ->with("torder",  	$order[1]=="asc"?"desc":"asc");
	}
	
	public function postIndex() {
	
		$input = Input::all();
		$rules = array(	"message" 	=> "required|min:10|max:90");	   
		$validation = Validator::make($input, $rules);
		
		if ($validation->fails()) {
			return Redirect::to('admin/notifications')->withErrors($validation)->withInput();
			
		} else {
	
			$data = new Notification();
			$data->message   = Input::get('message');
			$data->coupon_id = Input::get('coupon');
			$data->finished  = 0;
			$data->save();
			
			$tokens = Token::where("os", "=", "ios")->get();
			foreach ($tokens as $token) {
				//$this->_ios_notification($token->token, Input::get('message'), Input::get('coupon'));
			}
		
			return Redirect::to('admin/notifications')->with("message", "Notificación agregada!");
			
		}
		
	}
	
	
	public function getNotification() {
		$tokens = Token::where("os", "=", "ios")->get();
		foreach ($tokens as $token) {
			$this->_ios_notification($token->token, "HOLA MUNDO", 1);
		}
		return $this->getIndex();
	}
	
	
	/**************************
	     MÉTODOS PRIVADOS
	***************************/
	
	function _ios_notification($deviceToken, $message, $coupon) {

		$passphrase  = '123456';
		$certificate = path('public')."assets/certificates/notifications.pem"; 
		$ctx = stream_context_create();
		stream_context_set_option($ctx, 'ssl', 'local_cert', $certificate);
		stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
		$apnserver = 'ssl://gateway.sandbox.push.apple.com:2195';

		$fp = stream_socket_client($apnserver, $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

		if (!$fp) {
			//echo "Failed to connect: $err $errstr" . PHP_EOL;
			return false;
		}

		$body['aps'] = array('alert' => $message, 'sound' => 'default', 'id' => $coupon);
		$payload = json_encode($body);

		$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
		$result = fwrite($fp, $msg, strlen($msg));
	
		if (!$result) {
			//echo "Message not delivered: " . PHP_EOL."<br>";
			return false;
		} else {
			return true;
		}

		fclose($fp);
	}
		
		
	private function _validate($id) {
		if ($id == null) {
			return false;
		}
		
		$data = Notification::find($id);
		if (!$data) {
			return false;
		}
		
		return $data;
	}
	
	
}