<?PHP namespace App\Models;
	
use Illuminate\Database\Eloquent\Model;

class Partner extends Model {
	
	public $timestamps = false;
    protected $table = 'partners';
    protected $hidden = array('created_at', 'updated_at', 'deleted_at');
    
	
	public function partnerbranch() {
	  	return $this->belongsTo('App\Models\PartnerBranch.php');
    }
	
  
     
}