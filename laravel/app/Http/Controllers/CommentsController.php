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

class CommentsController extends Controller {

	public $restful = true;
	
	
	/**
	 * delete function.
	 * 
	 * @description Elimina comentarios de la base de datos	 
	 * @access public
	 * @param mixed $id (default: null)
	 * @return void
	 */
	 
	public function getDelete($id = null) {	
	
		if (!$data = $this->_validate($id)) {
			return Redirect::to('admin/comments');
		}
			
		$view = View::make('admin.comments.delete')
					->with("data", $data->comment);	
					
		return $view;
	}
	
	public function postDelete($id = null) {	
	

		if (!$data = $this->_validate($id)) {
			return Redirect::to('admin/comments');
		}
			
		$data->delete();
		
		return Redirect::to('admin/comments')->with("message", "Comentario eliminado!");
	}
	
	
	
	
	/**
	 * get_list function.
	 * 
	 * @description Listado de los comentarios registrados de la base de datos		 
	 * @access public
	 * @return void
	 */
	 
	public function getIndex() {	
	
		$limit 	= Input::get('limit', 10);
		$page 	= Input::get('page' , 1) - 1;
		$search = Input::get('search' , '');
		$order 	= Input::get('order' , 'comments.created_at|desc');
			
		$rows  = Comment::join('user_users', 'comments.user_id', '=', 'user_users.id')
					    ->leftJoin('coupons', 'comments.coupon_id', '=', 'coupons.id')
						->whereNull('user_users.deleted_at')
						->whereNull('coupons.deleted_at');
		
		if ($search != "") {
			
			$where_search = '(username LIKE ? OR comment LIKE ?)';
			$rows->whereRaw($where_search, array("%$search%", "%$search%"));
			$total = $rows->count();
			
		} else {
			$total = $rows->count();
		}
		
		$order = explode("|", $order);
		$rows->take($limit)->skip($page * $limit)->orderBy($order[0], $order[1]);
		$rows = $rows->get(array('comments.id', DB::raw('DATE_FORMAT(comments.created_at, "%e/%c/%Y") AS created'), 
										 'comments.comment', 'user_users.email', 'coupons.title'));

		return View::make('admin.comments.index')->with("rows", 	$rows)
											    ->with("search",  	$search)
											    ->with("page", 	 	$page)
											    ->with("limit", 	$limit)
											    ->with("total", 	$total)
											    ->with("show", 	 	min(($page + 1) * $limit, $total))
											    ->with("torder",  	$order[1]=="asc"?"desc":"asc");
	}
	
	
	
	
	/**************************
	     MÉTODOS PRIVADOS
	***************************/
	
	private function _validate($id) {
		if ($id == null) {
			return false;
		}
		$data = Comment::find($id);		
		if (!$data) {
			return false;
		}
		return $data;
	}
	
	
}