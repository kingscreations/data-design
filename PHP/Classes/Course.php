<?php
/**
 * the creation of a Lynda.com course class
 *
 * This class is a collection of relevant course data regarding any one class.
 *
 * @jason king <jason@kingscreations.org>
 **/
class Course {
	/**
	 * id for this course; this is the primary key
	 **/
	private $courseId;
	/**
	 *  reference to the authors Id; this is a foreign key
	 **/
	private $authorId;
	/**
	 *  overview of course
	 **/
	private $courseOverview;
	/**
	 * courses title
	 **/
	private $courseTitle;
		/**
	 * constructor for the course
	 *
	 * @param int $newCourseId of this course or null if new course
	 * @param string $newAuthorId of this course
	 * @param string $newCourseOverview of this course
	 * @param string $newCourseTitle of this course
	 * @throws InvalidArgumentException if data types are not valid
	 * @throws RangeException if data values are out of bounds (e.g., strings, negative integers, too long)
	 **/
	public function __construct($newCourseId, $newAuthorId, $newCourseOverview, $newCourseTitle = null) {
		//use the mutators to do the work for us
		try {
			$this->setCourseId($newCourseId);
			$this->setAuthorId($newAuthorId);
			$this->setCourseOverview($newCourseOverview);
			$this->setCourseTitle($newCourseTitle);
		} catch(InvalidArgumentException $invalidArgument) {
			//rethrow the exception to the caller
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			//rethrow the exception to the caller
			throw(new RangeException($range->getMessage(), 0, $range));
		}
	}
	/**
	 * accessor method for $courseId
	 *
	 * @return int value of $courseId
	 **/
	public function getCourseId() {
		return($this->courseId);
	}
	/**
	 * mutator method for courseId
	 *
	 * @param int $newCourseId new value of course id
	 * @throws InvalidArgumentException if $newCourseId is not an integer or not positive
	 * @throws RangeException if $newCourseId is not positive
	 **/
	public function setCourseId($newCourseId){
		// base case: if the course id is null, this is a new course without a mySQL assigned id (yet)
		if($newCourseId === null) {
			$this->courseId = null;
			return;
		}
		//verify the course id is valid
		$newCourseId = filter_var($newCourseId,FILTER_VALIDATE_INT);
		if($newCourseId === false){
			throw(new InvalidArgumentException("course id is not a valid integer"));
		}
		//verify the course id is positive
		if($newCourseId <= 0) {
			throw(new RangeException("course id is not positive"));
		}
		//convert and store the course id
		$this->courseId = intval($newCourseId);
	}
	/**
	 * accessor method for AuthorId
	 *
	 * @return int value of AuthorId
	 **/
	public function getAuthorId() {
		return($this->AuthorId);
	}
	/**
	 * mutator method for Author Id
	 *
	 * @param string $newAuthorId new value of authorId
	 * @throws InvalidArgumentException if $newAuthorId is not a string or insecure
	 * @throws RangeException if $newAuthorId is not positive
	 **/
	public function setAuthorId($newAuthorId) {
		//verify the author Id is valid
		$newAuthorId = filter_var($newAuthorId, FILTER_VALIDATE_INT);
		if($newAuthorId === false) {
			throw(new InvalidArgumentException("author id in course not a valid integer"));
		}
		// verify the author Id is positive
		if($newAuthorId <= 0) {
			throw(new RangeException("author id in course not positive"));
		}
		// convert and store the author Id
		$this->authorId = intval($newAuthorId);
	}
	/**
	 * accessor method for a course overview
	 *	 * @return string value of $courseOverview
	 **/
	public function getCourseOverview() {
		return($this->courseOverview);
	}
	/**
	 * mutator method for courseOverview
	 *
	 * @param string $newCourseOverview new value of courseOverview
	 * @throws InvalidArgumentException if $newCourseOverview is not a string or insecure
	 * @throws RangeException if $newCourseOverview is > 256 characters
	 **/
	public function setCourseOverview($newCourseOverview){
		//verify the input is secure
		$newCourseOverview = trim($newCourseOverview);
		$newCourseOverview = filter_var($newCourseOverview,FILTER_SANITIZE_STRING);
		if(empty($newCourseOverview) === true) {
			throw(new InvalidArgumentException("course overview is empty or insecure"));
		}
		//verify the course overview content will fit in the database
		if(strlen($newCourseOverview) > 256) {
			throw(new RangeException("course overview too large"));
		}
		// store the course overview content
		$this->courseOverview = $newCourseOverview;
	}
	/**
	 * accessor method for courseTitle
	 *
	 *@return string value of $courseTitle
	 **/
	public function getCourseTitle() {
		return($this->courseTitle);
	}
	/**
	 * mutator method for courseTitle
	 *
	 * @param string $newCourseTitle new value of courseTitle
	 * @throws InvalidArgumentException if $newCourseTitle is not a string or insecure
	 * @throws RangeException if $newCourseTitle is > 160 characters
	 **/
	public function setCourseTitle($newCourseTitle){
		//verify the input is secure
		$newCourseTitle = trim($newCourseTitle);
		$newCourseTitle = filter_var($newCourseTitle, FILTER_SANITIZE_STRING);
		if(empty($newCourseTitle) === true) {
			throw(new InvalidArgumentException("course title is empty or insecure"));
		}
		//verify the course title content will fit in the database
		if(strlen($newCourseTitle) > 160) {
			throw(new RangeException("course title too large"));
		}
		// store the course title content
		$this->courseTitle = $newCourseTitle;
	}
	/**
	 * inserts this Course into mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function insert(&$mysqli) {
		//handle degenerate cases
		if(gettype($mysqli) !=="object" || get_class($mysqli) !=="mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}
		//enforce the CourseId is null (i.e., don't insert a course that already exists
		if($this->courseId !== null) {
			throw(new mysqli_sql_exception("not a new course"));
		}
		//create query template
		$query = "INSERT INTO course(authorId, courseOverview, courseTitle) VALUES(?,?,?)";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}
		// bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("iss", $this->authorId, $this->courseOverview, $this->courseTitle);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters"));
		}
		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement: ". $statement->error));
		}
		//update the null courseId with what mySQL just gave us
		$this->courseId = $mysqli->insert_id;
		// clean up the statement
		$statement->close();
	}
	/**
	 * delete this course from mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function delete(&$mysqli) {
		//handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		//enforce the courseId is not null (don't delete a course that hasnt been inserted)
		if($this->courseId === null) {
			throw(new mysqli_sql_exception("unable to delete a course that does not exist"));
		}

		//create query template
		$query = "DELETE FROM course WHERE courseId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		//bind the member variables to the place holder in the template
		$wasClean = $statement->bind_param("i",$this->courseId);
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
	 * updates this Course in mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function update(&$mysqli) {
		//handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		//enforce the courseId is not null(ie don't update a course that hasn't been inserted)
		if($this->courseId === null) {
			throw(new mysqli_sql_exception("unable to update a course that does not exist"));
		}

		//create query template
		$query = "UPDATE course SET authorId = ?, courseOverview = ?, courseTitle = ? WHERE courseId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		//bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("issi", $this->authorId, $this->courseOverview, $this->courseTitle, $this->courseId);
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
	 * gets the Courses title by content
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param string $courseTitle content to search for
	 * @return mixed array of Course titles found. Course title found, or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getCourseByCourseTitle(&$mysqli, $courseTitle) {
		//handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		//sanitize the description before searching
		$courseTitle = trim($courseTitle);
		$courseTitle = filter_var($courseTitle, FILTER_SANITIZE_STRING);

		//create query template
		$query = "SELECT courseId, authorId, courseOverview, courseTitle FROM course WHERE courseTitle LIKE?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		//bind the course titles to the place holder in the template
		$courseTitle = "%$courseTitle%";
		$wasClean = $statement->bind_param("s",$courseTitle);
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

		//build an array of course titles
		$titles = array();
		while(($row = $result->fetch_assoc()) !== null) {
			try{
				$title = new Title($row["courseId"], $row[authorId], $row[courseOverview], $row[courseTitle]);
				$titles[] = $title;
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
		$numberOfTitles = count($titles);
		if($numberOfTitles === 0) {
			return(null);
		} else if($numberOfTitles === 1) {
			return($titles[0]);
		} else {
			return($titles);
		}
	}
}
?>