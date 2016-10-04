<?php 

class Timetable {
	private $classes_arrays;
	private $classes_objects;

	public function __construct ($classes_arrays) {
		$this->classes_arrays = $classes_arrays;
	}

	private function instantiate_classes() {
		$this->classes_objects = array();
		foreach ($this->classes_arrays as $class) {
			$this->classes_objects[] = new Clas($class);
		}
	}

	public function save() {
		$this->instantiate_classes();
		foreach ($this->classes_objects as $class) {
			// echo "<pre>";
			// print_r($class);
			// echo "</pre>";
			if(! $class->add()) {
				echo mysqli_error($conn);
			}
		}
	}
}

 ?>