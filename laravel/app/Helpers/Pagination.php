<?PHP namespace App\Helpers;
	
class Pagination {
	public static function limit($limit = 0, $attrs = array('name'=>'limit', 'class'=>'not_choosen'), $values = array(10, 25, 50, 100)) {
		
        $return = '<label>Mostrar <select%attrs%>%values%</select> registros</label>';
		
		$attrs_replace = '';
		foreach ($attrs as $k=>$v) {
			$attrs_replace .= ' '.$k.'"'.$v.'"';
		} 
		
		if ($limit != 0 && !in_array($limit, $values)) {
			$values[] = $limit;
			asort($values);
		}
		
		$values_replace = '';
		foreach ($values as $v) {
			$values_replace .= "<option value='$v'".($limit == $v ? 'selected="selected"' : '').">$v</option>";
		}
		
		$return = str_replace("%attrs%", $attrs_replace, $return);
		$return = str_replace("%values%", $values_replace, $return);
	
        return $return;
    }
    
    public static function search($search = '', $placeholder = 'Palabra clave') {
		
		return "<label for='search'>Buscar: 
					<input type='text' name='search' class='form-control' value='$search' id='search' placeholder='$placeholder'><input type='submit' class='btn btn-default' value='&gt;' />
				</label>";
		
	}
	
	public static function show($from, $to, $total) {
		return "Mostrando desde $from hasta $to de $total registro".($total == 1 ? "" : "s");		
	}
	
	public static function links ($page, $total, $limit, $max_pages = 4) {
		
		$return = '<ul class="pagination">';
				
		$max_limit = ceil($total/$limit);
		$max_total = ceil($total/$limit);
		$count = 0;
		$page_url = preg_replace('/(?:&|(\?))' . "page" . '=[^&]*(?(1)&|)?/i', '$1', $_SERVER['QUERY_STRING']);
		
		if ($max_limit > $max_pages) {
			$count 		= $page - $max_pages/2 < 0 ? 0 : $page - $max_pages/2;
			$max_limit 	= $count + $max_pages + 1 > $max_total ? $max_total : $count + $max_pages + 1;
		}
		
		if ($count>=1) $return .= "<li><a href='?$page_url&page=1'>Primero</a></li>";
		if ($count>=2) $return .= "<li><a href='?$page_url&page=$page'>Anterior</a></li>";

		while ($count++ < $max_limit) {
			$return .= "<li><a href='?$page_url&page=$count'".($count-1==$page?' class="active"':'').">$count</a></li>";
		}

		if($max_limit<$max_total-1) $return .= "<li><a href='?$page_url&page=".($page+2)."'>Siguiente</a></li>";
		if($max_limit<$max_total)   $return .= "<li><a href='?$page_url&page=$max_total'>Último</a></li>";

		$return .= '</ul>';
		
		return $return;
	}
	
}