<?PHP namespace App\Models;
	
use Illuminate\Database\Eloquent\Model;

class ReportCategory extends Model {
	
	public $timestamps = true;
    protected $table = 'report_category';
    protected $hidden = array('created_at', 'updated_at', 'deleted_at');
     
 
     
}