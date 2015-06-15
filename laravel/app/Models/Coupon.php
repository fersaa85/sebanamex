<?PHP namespace App\Models;
	
use Illuminate\Database\Eloquent\Model;
use Intervention\Image\ImageManagerStatic as Image;
use App\Models\Rating;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Coupon extends Model {
	
	public $timestamps 	= true;
	protected $table 	= 'coupons';
	protected $filepath = "assets/uploads/coupons";
    protected $hidden 	= array('created_at', 'updated_at', 'deleted_at');
     
    public function category() {
	  	return $this->belongsTo('App\Models\Category');
    }
    
     public function user() {
	  	return $this->belongsTo('App\User');
    }
     
    public function comments() {
        return $this->hasMany('App\Models\Comment')
        			->leftJoin('user_users', 'comments.user_id', '=', 'user_users.id')
        			->whereNull('user_users.deleted_at');
    }
     
    public function scans() {
        return $this->hasMany('App\Models\Scan');
    }
     
    public function ratings() {
        return $this->hasOne('App\Models\Rating');
    }
     
    public function average() {
        return Rating::where("coupon_id", "=", $this->id)->avg("value");
    }
     
    public function qr($size) {
	    if (!$this->qr_code) {
		    $this->qr_code = md5(md5($this->id)."!@#$.Oiad12Adkfjas2@$55");
		    $this->save();
	    }
	    $qr = $this->getPath("c", "qr.".$size);
	    if (!file_exists($qr)) {
		    QrCode::format('png')->size($size)->generate($this->qr_code, $qr);
		}
	   	    
	    return $qr;
	    
    }
    
    public function setImageAttribute($image) {
	    if ($this->id) {
			//Image::make($image)->save($this->filepath."/o/".$this->id.".png");
			$this->flush();
		}
	}
	
	public function getImageAttribute() {
		if ($this->id) {
			return $this->getPath("o");
		} else {
			return null;
		}
	}
	
	public function thumb($w, $h) {
		$tb = $this->getPath("c", $w."x".$h);
		if (!file_exists($tb)) {
			//$or    = $this->getPath("o");
			//$image = Image::make($or)->resize($w, $h)->canvas($w, $h, '#FFFFFF')->save($tb);
			//$image->resizeCanvas($w, $h, 'center', 'center', $image->allocateColor(255, 255, 255))->saveToFile($tb);			
		}
		return $tb;
	}
	
	public function getPath($type, $sufix = "") {
		return $this->filepath."/$type/".$this->id.($sufix==""?"":".$sufix").".png";
	}
     
    static function nearest($latitude, $longitude) {
	     
	    $max_distance = 100;
	    $radius = 6371;
	    $distance = sprintf("( %d * acos( cos( radians(%s) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(%s) ) + sin( radians(%s) ) * sin( radians( latitude ) ) ) ) AS distance", $radius, $latitude, $longitude, $latitude);
	     
		return Coupon::having('distance', '<', $max_distance )->orderBy('distance', 'ASC')->take(30)->get(array('*', DB::raw($distance)));

    }
    
    public function flush() {
		
		$dir = $this->filepath."/c/";
		
		$prefix = $this->id.".";
		
		$dir = rtrim($dir, '\\/');
		$result = array();

		$h = opendir($dir);
		
		while (($f = readdir($h)) !== false) {
			if ($f !== '.' and $f !== '..') {
				if (strpos($f, $prefix, 0) === 0) {
					$result[] = $dir."/".$f;
					unlink($dir."/".$f);
				}
			}
		}
		closedir($h);
		return $result;
		
	}
     
}