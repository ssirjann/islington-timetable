CREATE DATABASE timetable;
USE timetable;
CREATE TABLE classes (
	day ENUM ('SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI'),
	-- `time` VARCHAR(25),
	start_time TIME,
	end_time TIME,
	class_type VARCHAR(10),
	module_code CHAR(8),
	module_title VARCHAR(50),
	lecturer VARCHAR(40),
	`group` VARCHAR(20),
	block VARCHAR(25),
	room VARCHAR(25),
	CONSTRAINT PRIMARY KEY classes_pk (day, lecturer, `group`)
);
GRANT ALL PRIVILEGES ON timetable.* TO 'sirjan' IDENTIFIED BY 'password';