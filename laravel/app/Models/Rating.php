<?PHP namespace App\Models;
	
use Illuminate\Database\Eloquent\Model;

class Rating extends Model {
	
	public $timestamps = false;
    protected $table = 'ratings';
    
	public function user() {
        return $this->belongsTo('App\User');
    }
     
    public function coupon() {
        return $this->belongsTo('App\Model\Coupon');
    }
     
}