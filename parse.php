<?php 
require_once('vendor/autoload.php');
require_once('db_connect.php');
require_once('class.php');
require_once('timetable.php');

// $par = new Parse ('tt.pdf');
// $tt = new Timetable($par->main());
// $tt->save();

class Parse {

	const DAYS = array('SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI');
	private $filename;

	function __construct($filename) {
		$this->filename = $filename;
	}

	public function main() {
		$raw = $this->get_raw();
		$classes = $this->get_all_matches($raw);
		return $classes;
		// echo'<pre>';
		// print_r($classes);
		// echo'</pre>';
	}

	private function get_raw() {
		$reader = new \Asika\Pdf2text;
		$routine_filename = $this->filename;
		$raw = $reader->decode($routine_filename);
		$raw = nl2br($raw);
		// echo $raw;
		return $raw;
	}

	private function get_routine_headers($raw='') {
		if(empty($raw)) {
			$raw = $this->get_raw();
		}
		if(!$this->is_timetable($raw)) {
			die('The system could not parse that timetable. Are you sure it is the right file?');
		}
		$raw = $this->strip_title($raw);
		preg_match('/.*?SUN/is', $raw, $header_string);
		$header_string = $header_string[0];
		$header_string = preg_replace('/SUN.*/is', '', $header_string);
		$headers = $this->extract_to_array($header_string, true);
		return $headers;
	}
	private function extract_to_array($string, $is_header=false) {
		// receives strings like
		// 		SUN<br />
		// 		10:00 am - 12:00 am<br />
		// 		....<br />
		$entries = array();
		preg_match_all('/.*?<br \/>/i', $string, $entries);
		$entries = $entries[0];
		$entries = $this->strip($entries);
		if(! $is_header) {
			$entries=$this->make_associative($entries);
		}
		return $entries;
	}

	private function space_to_underscore($string) {
		return strtolower(str_replace(' ', '_', trim($string)));
	}

	private function make_associative($data) {
		$raw = $this->get_raw();
		// echo $raw;
		$headers = $this->get_routine_headers($raw);
		foreach ($data as $id => $value) {
			$data[$this->space_to_underscore($headers[$id])] = $value;
			unset($data[$id]);
		}
		$data['group'] = $this->format_group($data['group']);
		// var_dump($data);
		return $data;
	}

	public function get_level() {
		$raw = $this->get_raw();
		$match = array();
		preg_match("/year.*?table/i", $raw, $match);
		$match = str_ireplace(['year', ' '], '', $match[0]);
		return $match[0];
	}

	private function format_group($group) {
		if (strpos($group, '+') !== false) {
			$groups_set = explode('+', $group);
			foreach ($groups_set as $id => $group) {
				$groups_set[$id] = 'L'.$this->get_level().$group;
			}
			return implode('+', $groups_set);
		}
		return 'L'.$this->get_level().$group;
	}

	private function strip($target) {
		if (is_array($target)) {
			$output = array();
			foreach ($target as $value) {
				$value = trim($value);
				$value = str_ireplace(['<br />', '\n', '"', "'"], '', $value);
				$output[] = $value;
			}
			return $output;
		}
		$output = trim($target);
		$output = str_ireplace(['<br />', '\n', '"', "'"], '', $output);
		return $output;
	}

	private function strip_title($target) {
		$target = preg_replace('/london metropolitan university/is', '', $target);
		$target = preg_replace('/year .*? time table/is', '', $target);
		return $target;
	}

	private function get_no_of_headers($raw='') {
		if(empty($raw)) {
			$raw = $this->get_raw();
		}
		return count($this->get_routine_headers($raw));
	}

	private function get_all_matches($target) { //matches of mondays;
		$matches = array();
		foreach (self::DAYS as $day) {
			$match = array();
			preg_match_all('/'. $day .'(.*?(<br \/>)){'.$this->get_no_of_headers($target).'}/s', $target, $match);
			$matches = array_merge($matches, $match[0]);
		}
		foreach ($matches as $id => $value) {
			$matches[$id] = $this->extract_to_array($value);
		}
		return $matches;
	}

	private function is_timetable($raw='') {
		if(empty($raw)) {
			$raw = $this->get_raw();
		}
		if(	stripos($raw, 'day')===false || 
			stripos($raw, 'time')===false || 
			stripos($raw, 'module')===false || 
			stripos($raw, 'sun')===false || 
			stripos($raw, 'mon')===false || 
			stripos($raw, 'tue')===false || 
			stripos($raw, 'wed')===false || 
			stripos($raw, 'thu')===false || 
			stripos($raw, 'fri')===false || 
			(stripos($raw, 'time table')===false && stripos($raw, 'timetable') ===false) || 
			stripos($raw, 'london metropolitan university')===false) 
		{
				return false;
		} 
		return true;
	}
}

?>

