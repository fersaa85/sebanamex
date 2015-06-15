<?PHP namespace App\Models;
	
use Illuminate\Database\Eloquent\Model;

class Report extends Model {
	
	public $timestamps = true;
    protected $table = 'reports';
    protected $hidden = array('created_at', 'updated_at', 'deleted_at');
    
	
	public function reportcategory() {
	  	return $this->belongsTo('App\Models\ReportCategory');
    }
	
    public function user() {
        return $this->belongsTo('App\User');
    }
     
}