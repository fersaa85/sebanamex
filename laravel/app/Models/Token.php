<?PHP namespace App\Models;
	
use Illuminate\Database\Eloquent\Model;

class Token extends Model {

	public $timestamps = false;
    protected $table = 'user_users_tokens';
    
	public function user() {
        return $this->belongsTo('App\User');
    }

}