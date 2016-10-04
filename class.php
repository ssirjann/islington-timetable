<?php 


class Clas {
	// private $id;
	private $day;
	private $class_type;
	private $time;
	private $start_time;
	private $end_time;
	private $module_code;
	private $module_title;
	private $lecturer;
	private $group;
	private $block;
	private $room;
	private static $table_name = 'classes';
	private static $table_fields = ['day','start_time','end_time','class_type','module_code','module_title','lecturer','group','block','room'];

	public function __construct ($class) {
		$this->day = $class['day']; 
		$this->time = $class['time']; 
		$this->class_type = $class['class_type']; 
		$this->module_code = $class['module_code']; 
		$this->module_title = $class['module_title']; 
		$this->lecturer = $class['lecturer']; 
		$this->group = $class['group']; 
		$this->block = $class['block']; 
		$this->room = $class['room']; 

		$this->start_time = $this->split_time()[0];
		$this->end_time = $this->split_time()[1];
		// var_dump($this);
	}

	private function split_time() {
		$time_string = $this->time;
		$time = explode('-', $time_string);
		// echo $time_string.'<br>';
		foreach ($time as $key => $value) {
			$time[$key] = $this->get_storable_time($value);
		}
		// echo "vd <br>";
		// var_dump($time);
		// echo " <br>";
		return $time;
	}

	private function get_storable_time($value) {
		if (stripos($value, 'am')) {
			return $this->strip_time($value);
		} else if (stripos($value, 'pm')) {
			$time = explode(':', $value);
			if($time[0] != '12'){
				$time[0] += 12;
			}
			return $this->strip_time(implode(':', $time));
		}
		return;
	}

	private function strip_time($time_string) {
		return trim(str_ireplace(['am','pm'], '', $time_string));
	}



	public function add() {
			global $conn;
			$values = array();
			$query = "INSERT IGNORE INTO " . static::$table_name;

			foreach (static::$table_fields as $id => $value) {
				$values["`{$value}`"] = is_string($this->$value) ?
					"'".mysqli_real_escape_string($conn, $this->$value)."'" : $this->$value;
			}
			$query .= " (" . join(", ", array_keys($values)) . ") ";
			$query .= " VALUES (" . join(", ", array_values($values)) . ") ";
			// echo $query;
			if(mysqli_query($conn, $query)) {
				return true;
			} else {
				die(mysqli_error($conn));
				return false;
			}	
		}

}


 ?>