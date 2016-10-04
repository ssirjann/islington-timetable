<?php 

	function link_with_order_by($filename, $get_values, $heading) {
		unset($get_values['order_by']);
		$uri = form_link_with($filename, $get_values);
		unset($get_values['page']);
		if (empty($get_values)) {
				$uri .= "?order_by=".$heading;
		} else {
			$uri .= '&order_by='.$heading;
		}
		return $uri;
	}

	function display_time($time) {
		$time = explode(':', $time);
		if($time[0]>=12) {
			if ($time[0]>12) {
				$time[0] -= 12;
				$time[0] = '0'.$time[0];
			}
			$time[2] = "PM";
		} else {
			$time[2] = "AM";
		}
		return $time[0] .':'. $time[1]." {$time[2]}";
	}

	function get_previous_page($filename, $page, $get_values=array()) {
		// var_dump($page);
		$prev_page = $page-1;
			$uri = form_link_with($filename, $get_values);
			unset($get_values['page']);
			if (empty($get_values)) {
				$uri .= "?page=".$prev_page;
			} else {
				$uri .= '&page='.$prev_page;
			}
			return $uri;
		}

		function get_next_page($filename, $page, $get_values=array()) {
		// var_dump($page);
		$next_page = $page+1;
			$uri = form_link_with($filename, $get_values);
			unset($get_values['page']);
			if (empty($get_values)) {
				$uri .= "?page=".$next_page;
			} else {
				$uri .= '&page='.$next_page;
			}
			return $uri;
		}

		function form_target_link($filename, $get_values, $heading, $value) {
			$uri = form_link_with($filename, $get_values);
			unset($get_values['page']);
			if(!stripos($heading, 'time')) {
					if (empty($get_values)) {
	 				return $uri.'?'.urlencode($heading).'='. urlencode($value);
					} else if(! isset($get_values[$heading])) {
						return $uri.'&'.urlencode($heading).'='. urlencode($value);
					} else {
						return '#';
					}
				} else {
					return '#';
				}
		}
		
		function form_link_with($filename, $get_values = array()) {
		unset($get_values['page']);
			if (empty($get_values)) {
				return $filename;
			}
			$arr = array();
			foreach ($get_values as $key => $value) {
				// if ($key != 'page') {
					$arr[] = "{$key}={$value}";
				// }
			}
			$uri = join ("&", array_values($arr));
			$uri = $filename ."?".$uri;
			return $uri;
		}

		function is_suitable($file) {
			if ($file['size'] > 300*1000 || stripos($file['type'], 'pdf') === false) {
				return false;
			}
			return true;
		}

 ?>