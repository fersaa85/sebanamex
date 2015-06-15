<?PHP namespace App\Models;
	
use Illuminate\Database\Eloquent\Model;

class Scan extends Model {
	
	public $timestamps = true;
    protected $table = 'scans';
    protected $hidden = array('created_at', 'updated_at', 'deleted_at');
    
	public function user() {
        return $this->belongsTo('App\User');
    }
     
    public function coupon() {
        return $this->belongsTo('App\Models\Coupon');
    }
     
}