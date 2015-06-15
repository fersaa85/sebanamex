<?PHP namespace App\Models;
	
use Illuminate\Database\Eloquent\Model;

class Category extends Model {
	
	 public $timestamps = true;
     protected $table   = 'categories';
     protected $hidden  = array('created_at', 'updated_at');
     
}