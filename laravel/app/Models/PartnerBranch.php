<?PHP namespace App\Models;
	
use Illuminate\Database\Eloquent\Model;

class PartnerBranch extends Model {
	
	public $timestamps = false;
    protected $table = 'partners_branch';
    protected $hidden = array('created_at', 'updated_at', 'deleted_at');
     
 
     
}