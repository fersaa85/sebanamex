<?PHP namespace App\Models;
	
use Illuminate\Database\Eloquent\Model;

class MedicalNetwork extends Model {
	
	public $timestamps = false;
    protected $table = 'medical_network';
    protected $hidden = array('created_at', 'updated_at', 'deleted_at');
    
	
	public function medicalnetworkcategory() {
	  	return $this->belongsTo('App\Models\MedicalNetworkCategory.php');
    }
	
  
     
}