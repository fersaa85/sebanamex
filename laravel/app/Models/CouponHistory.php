<?PHP namespace App\Models;
	
use Illuminate\Database\Eloquent\Model;

class CouponHistory extends Model {
	
	public $timestamps = true;
    protected $table = 'coupons_history';
    protected $hidden = array('created_at', 'updated_at', 'deleted_at');
    
	
	public function coupon() {
	  	return $this->belongsTo('App\Models\Coupon');
    }
	
    public function user() {
        return $this->belongsTo('App\User');
    }
     
}