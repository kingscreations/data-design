<?php
/**
 * the creation of a Lynda.com student class
 *
 * This class is a collection of relevant course data regarding any one student.
 *
 * @jason king <jason@kingscreations.org>
 **/
class Student {
	/**
	 * id for this student; this is the primary key
	 **/
	private $studentId;
	/**
	 * list of student interests
	 **/
	private $studentInterests;
	/**
	 * student profile
	 **/
	private $studentProfile;
	/**
	 * constructor for the student
	 *
	 * @param int $newStudentId of this student or null if new student
	 * @param string $newStudentInterests of the student
	 * @param string $newStudentProfile of the student
	 * @throws InvalidArgumentException if data types are not valid
	 * @throws RangeException if data values are out of bounds (e.g., strings, negative integers, too long)
	 **/
	public function __construct($newStudentId, $newStudentInterests, $newStudentProfile = null) {
		//use the mutators to do the work for us
		try {
			$this->setStudentId($newStudentId);
			$this->setStudentInterests($newStudentInterests);
			$this->setStudentProfile($newStudentProfile);
		} catch(InvalidArgumentException $invalidArgument) {
			//rethrow the exception to the caller
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			//rethrow the exception to the caller
			throw(new RangeException($range->getMessage(), 0, $range));
		}
	}
	/**
	 * accessor method for $studentId
	 *
	 * @return int value of $studentId
	 **/
	public function getStudentId() {
		return($this->studentId);
	}
	/**
	 * mutator method for studentId
	 *
	 * @param int $newStudentId new value of student Id
	 * @throws InvalidArgumentException if $newStudentId is not an integer or not positive
	 * @throws RangeException if $newStudentId is not positive
	 **/
	public function setStudentId($newStudentId){
		// base case: if the student id is null, this is a new student without a mySQL assigned id (yet)
		if($newStudentId === null) {
			$this->studentId = null;
			return;
		}
		//verify the student id is valid
		$newStudentId = filter_var($newStudentId,FILTER_VALIDATE_INT);
		if($newStudentId === false){
			throw(new InvalidArgumentException("student id is not a valid integer"));
		}
		//verify the student id is positive
		if($newStudentId <= 0) {
			throw(new RangeException("student id is not positive"));
		}
		//convert and store the student id
		$this->studentId = intval($newStudentId);
	}
	/**
	 * accessor method for studentInterests
	 *
	 * @return string value of $studentInterests
	 **/
	public function getStudentInterests() {
		return($this->studentInterests);
	}
	/**
	 * mutator method for studentInterests
	 *
	 * @param string $newStudentInterests new value of studentInterests
	 * @throws InvalidArgumentException if $newStudentInterests is not a string or insecure
	 * @throws RangeException if $newStudentInterests is > 256 characters
	 **/
	public function setStudentInterests($newStudentInterests){
		//verify the input is secure
		$newStudentInterests = trim($newStudentInterests);
		$newStudentInterests = filter_var($newStudentInterests, FILTER_SANITIZE_STRING);
		if(empty($newStudentInterests) === true) {
			throw(new InvalidArgumentException("student interests is empty or insecure"));
		}
		//verify the student interests content will fit in the database
		if(strlen($newStudentInterests) > 256) {
			throw(new RangeException("student interests too large"));
		}
		// store the author courses content
		$this->studentInterests = $newStudentInterests;
	}
	/**
	 * accessor method for studentProfile
	 *	 @return string value of $studentProfile
	 **/
	public function getStudentProfile() {
		return($this->studentProfile);
	}
	/**
	 * mutator method for studentProfile
	 *
	 * @param string $newStudentProfile new value of studentProfile
	 * @throws InvalidArgumentException if $newStudentProfile is not a string or insecure
	 * @throws RangeException if $newStudentProfile is > 256 characters
	 **/
	public function setStudentProfile($newStudentProfile){
		//verify the input is secure
		$newStudentProfile = trim($newStudentProfile);
		$newStudentProfile = filter_var($newStudentProfile, FILTER_SANITIZE_STRING);
		if(empty($newStudentProfile) === true) {
			throw(new InvalidArgumentException("student profile is empty or insecure"));
		}
		//verify the student profile content will fit in the database
		if(strlen($newStudentProfile) > 256) {
			throw(new RangeException("student profile too large"));
		}
		// store the student profile content
		$this->studentProfile = $newStudentProfile;
	}
	/**
	 * inserts this student into mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function insert(&$mysqli) {
		//handle degenerate cases
		if(gettype($mysqli) !=="object" || get_class($mysqli) !=="mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}
		//enforce the studentId is null (i.e., don't insert a student that already exists
		if($this->studentId !== null) {
			throw(new mysqli_sql_exception("not a new student"));
		}
		//create query template
		$query = "INSERT INTO student(studentInterests, studentProfile) VALUES(?,?)";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}
		// bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("ss", $this->studentInterests, $this->studentProfile);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters"));
		}
		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement: ". $statement->error));
		}
		//update the null studentId with what mySQL just gave us
		$this->studentId = $mysqli->insert_id;
		// clean up the statement
		$statement->close();
	}
	/**
	 * delete this student from mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function delete(&$mysqli) {
		//handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		//enforce the studentId is not null (don't delete a student that hasnt been inserted)
		if($this->studentId === null) {
			throw(new mysqli_sql_exception("unable to delete a student that does not exist"));
		}

		//create query template
		$query = "DELETE FROM student WHERE studentId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		//bind the member variables to the place holder in the template
		$wasClean = $statement->bind_param("i",$this->studentId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement: ".$statement->error));
		}

		//clean up the statement
		$statement->close();
	}
	/**
	 * updates this Student in mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function update(&$mysqli) {
		//handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		//enforce the studentId is not null(ie don't update a student that hasn't been inserted)
		if($this->studentId === null) {
			throw(new mysqli_sql_exception("unable to update a student that does not exist"));
		}

		//create query template
		$query = "UPDATE student SET studentInterests = ?, studentProfile = ? WHERE studentId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		//bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("ssi", $this->studentInterests, $this->studentProfile, $this->studentId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters"));
		}

		//execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement: ".$statement->error));
		}

		//clean up the statement
		$statement->close();
	}
	/**
	 * gets the Students Profile by content
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param string $studentProfile content to search for
	 * @return mixed array of student profiles found. Profile found, or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getStudentByStudentProfile(&$mysqli, $studentProfile) {
		//handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		//sanitize the description before searching
		$studentProfile = trim($studentProfile);
		$studentProfile = filter_var($studentProfile, FILTER_SANITIZE_STRING);

		//create query template
		$query = "SELECT studentId, studentInterests, studentProfile FROM student WHERE studentProfile LIKE?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		//bind the student profile to the place holder in the template
		$studentProfile = "%$studentProfile%";
		$wasClean = $statement->bind_param("s",$studentProfile);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters"));
		}

		//execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement: ".$statement->error));
		}

		//get result from the SELECT query
		$result = $statement->get_result();
		if($result === false) {
			throw(new mysqli_sql_exception("unable to get result set"));
		}

		//build an array of course
		$courses = array();
		while(($row = $result->fetch_assoc()) !== null) {
			try{
				$profile = new Profile($row["studentId"], $row[studentInterests], $row[studentProfile]);
				$profiles[] = $profile;
			}
			catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception($exception->getMessage(), 0, $exception));
			}
		}

		//count the results in the array and return:
		//1) null if 0 results
		//2) a single object if 1 result
		//3) the entire array if >1 result
		$numberOfProfiles = count($profiles);
		if($numberOfProfiles === 0) {
			return(null);
		} else if($numberOfProfiles === 1) {
			return($profiles[0]);
		} else {
			return($profiles);
		}
	}
}
?>