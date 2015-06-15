<?PHP namespace App\Models;
	
use Illuminate\Database\Eloquent\Model;

class MedicalNetworkCategory extends Model {
	
	public $timestamps = false;
    protected $table = 'medical_network_categorys';
    protected $hidden = array('created_at', 'updated_at', 'deleted_at');
     
 
     
}