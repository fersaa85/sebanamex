<?PHP namespace App\Models;
	
use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model {
	
	public $timestamps = true;
    protected $table = 'user_users_info';
    protected $hidden = array('created_at', 'updated_at', 'deleted_at');
     
    public function user() {
        return $this->belongsTo('App\User');
    }
     
}