CREATE TABLE t_persons(
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	bogen_id VARCHAR(255),
	password_hash CHAR(40),
	password_salt CHAR(40),
	forename VARCHAR(30),
	middlename VARCHAR(30),
	lastname VARCHAR(30),
	UNIQUE INDEX(bogen_id)
) TYPE=InnoDB;

CREATE TABLE t_person_properties(
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	person_id INT,
	property VARCHAR(30),
	value VARCHAR(255),
	UNIQUE INDEX(person_id, property),
	INDEX(person_id), FOREIGN KEY(person_id) REFERENCES t_persons(id) ON DELETE CASCADE
) TYPE=InnoDB;

