<?PHP namespace App\Models;
	
use Illuminate\Database\Eloquent\Model;

class Notification extends Model {
	
	 public $timestamps = true;
     protected $table = 'notifications';
     protected $hidden = array('created_at', 'updated_at');
     
	 /*
     public function coupon() {
        return $this->hasOne('Coupon');
        
     }
	 */
}