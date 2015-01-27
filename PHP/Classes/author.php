<?php
/**
 * the creation of a Lynda.com author class
 *
 * This class is a collection of relevant course data regarding any one instructor.
 *
 * @jason king <jason@kingscreations.org>
 **/
class Author {
	/**
	 * id for this author; this is the primary key
	 **/
	private $authorId;
	/**
	 *  list of courses by author
	 **/
	private $authorCourses;
	/**
	 * credentials for this author
	 **/
	private $authorCredentials;
	/**
 	* author's name
 	**/
	private $authorName;
	/**
	 * photo of this author
	 **/
	private $authorPhoto;
	/**
	 * constructor for the author
	 *
	 * @param int $newAuthorId of this author or null if new author
	 * @param string $newAuthorCourses of the authors profile
	 * @param string $newAuthorCredentials of the authors profile
	 * @param string $newAuthorName of the authors profile
	 * @param string $newAuthorPhoto of the authors profile
	 * @throws InvalidArgumentException if data types are not valid
	 * @throws RangeException if data values are out of bounds (e.g., strings, negative integers, too long)
	 **/
	public function __construct($newAuthorId, $newAuthorCourses, $newAuthorCredentials, $newAuthorName, $newAuthorPhoto = null) {
		//use the mutators to do the work for us
		try {
			$this->setAuthorId($newAuthorId);
			$this->setAuthorCourses($newAuthorCourses);
			$this->setAuthorCredentials($newAuthorCredentials);
			$this->setAuthorName($newAuthorName);
			$this->setAuthorPhoto($newAuthorPhoto);
			} catch(InvalidArgumentException $invalidArgument) {
			//rethrow the exception to the caller
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			//rethrow the exception to the caller
			throw(new RangeException($range->getMessage(), 0, $range));
		}
	}
	/**
	 * accessor method for $authorId
	 *
	 * @return int value of $authorId
	 **/
	public function getAuthorId() {
		return($this->authorId);
	}
	/**
	 * mutator method for authorId
	 *
	 * @param int $newAuthorId new value of author id
	 * @throws InvalidArgumentException if $newAuthorId is not an integer or not positive
	 * @throws RangeException if $newAuthorId is not positive
	 **/
	public function setAuthorId($newAuthorId){
		// base case: if the author id is null, this is a new author without a mySQL assigned id (yet)
			if($newAuthorId === null) {
				$this->authorId = null;
				return;
			}
		//verify the author id is valid
		$newAuthorId = filter_var($newAuthorId,FILTER_VALIDATE_INT);
		if($newAuthorId === false){
			throw(new InvalidArgumentException("author id is not a valid integer"));
		}
		//verify the author id is positive
		if($newAuthorId <= 0) {
		   throw(new RangeException("author id is not positive"));
		}
		//convert and store the profile id
		$this->authorId = intval($newAuthorId);
		}
	/**
	 * accessor method for authorCourses
	 *
	 * @return string value of $authorCourses
	 **/
	public function getAuthorCourses() {
		return($this->authorCourses);
	}
	/**
	 * mutator method for authorCourses
	 *
	 * @param string $newAuthorCourses new value of authorCourses
	 * @throws InvalidArgumentException if $newAuthorCourses is not a string or insecure
	 * @throws RangeException if $newAuthorCourses is > 256 characters
	 	**/
	public function setAuthorCourses($newAuthorCourses){
			//verify the input is secure
			$newAuthorCourses = trim($newAuthorCourses);
			$newAuthorCourses = filter_var($newAuthorCourses, FILTER_SANITIZE_STRING);
			if(empty($newAuthorCourses) === true) {
				throw(new InvalidArgumentException("author courses is empty or insecure"));
			}
			//verify the author courses content will fit in the database
			if(strlen($newAuthorCourses) > 256) {
				throw(new RangeException("author courses is too large"));
			}
			// store the author courses content
			$this->authorCourses = $newAuthorCourses;
		}
	/**
	 * accessor method for authorCredentials
	 *	 * @return string value of $authorCredentials
	 **/
	public function getAuthorCredentials() {
		return($this->authorCredentials);
	}
	/**
	 * mutator method for authorCredentials
	 *
	 * @param string $newAuthorCredentials new value of authorCredentials
	 * @throws InvalidArgumentException if $newAuthorCredentials is not a string or insecure
	 * @throws RangeException if $newAuthorCredentials is > 256 characters
	 **/
	public function setAuthorCredentials($newAuthorCredentials){
		//verify the input is secure
		$newAuthorCredentials = trim($newAuthorCredentials);
		$newAuthorCredentials = filter_var($newAuthorCredentials,FILTER_SANITIZE_STRING);
		if(empty($newAuthorCredentials) === true) {
			throw(new InvalidArgumentException("author credentials is empty or insecure"));
		}
		//verify the author credentials content will fit in the database
		if(strlen($newAuthorCredentials) > 256) {
			throw(new RangeException("author Credentials too large"));
		}
		// store the author credentials content
		$this->authorCredentials = $newAuthorCredentials;
	}
	/**
	 * accessor method for authorName
	 *
	 *@return string value of $authorName
	 **/
	public function getAuthorName() {
		return($this->authorName);
	}
	/**
	 * mutator method for authorName
	 *
	 * @param string $newAuthorName new value of authorName
	 * @throws InvalidArgumentException if $newAuthorName is not a string or insecure
	 * @throws RangeException if $newAuthorName is > 160 characters
	 **/
	public function setAuthorName($newAuthorName){
		//verify the input is secure
		$newAuthorName = trim($newAuthorName);
		$newAuthorName = filter_var($newAuthorName, FILTER_SANITIZE_STRING);
		if(empty($newAuthorName) === true) {
			throw(new InvalidArgumentException("author name is empty or insecure"));
		}
		//verify the author name content will fit in the database
		if(strlen($newAuthorName) > 160) {
			throw(new RangeException("author name too large"));
		}
		// store the author name content
		$this->authorName = $newAuthorName;
	}
	/**
	 * accessor method for $authorPhoto
	 *
	 *@return string value of $authorPhoto
	 **/
	public function getAuthorPhoto() {
		return ($this->authorPhoto);
	}
	/**
	 * mutator method for author photo
	 *
	 * @param string $newAuthorPhoto new value of author photo
	 * @throws InvalidArgumentException if $newAuthorPhoto is not a string or insecure
	 * @throws RangeException if $newAuthorPhoto is > 160 characters
	 **/
	public function setAuthorPhoto($newAuthorPhoto){
		//verify the input is secure
		$newAuthorPhoto = trim($newAuthorPhoto);
		$newAuthorPhoto = filter_var($newAuthorPhoto, FILTER_SANITIZE_STRING);
		if(empty($newAuthorPhoto) === true) {
			throw(new InvalidArgumentException("author photo is empty or insecure"));
		}
		//verify the author photo content will fit in the database
		if(strlen($newAuthorPhoto) > 160) {
			throw(new RangeException("author photo too large"));
		}
		// store the author photo content
		$this->authorPhoto = $newAuthorPhoto;
	}
	/**
	 * inserts this Author into mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function insert(&$mysqli) {
		//handle degenerate cases
		if(gettype($mysqli) !=="object" || get_class($mysqli) !=="mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}
		//enforce the authorId is null (i.e., don't insert an author that already exists
		if($this->authorId !== null) {
			throw(new mysqli_sql_exception("not a new author"));
		}
		//create query template
		$query = "INSERT INTO author(authorCourses, authorCredentials, authorName, authorPhoto)VALUES(?,?,?,?)";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}
		// bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("ssss", $this->authorCourses, $this->authorCredentials, $this->authorName, $this->authorPhoto);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters"));
		}
		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement: ". $statement->error));
		}
		//update the null authorId with what mySQL just gave us
		$this->authorId = $mysqli->insert_id;
		// clean up the statement
		$statement->close();
	}
	/**
	 * delete this author from mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function delete(&$mysqli) {
		//handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		//enforce the authorId is not null (don't delete an author that hasnt been inserted)
		if($this->authorId === null) {
			throw(new mysqli_sql_exception("unable to delete an author that does not exist"));
		}

		//create query template
		$query = "DELETE FROM author WHERE authorId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		//bind the member variables to the place holder in the template
		$wasClean = $statement->bind_param("i",$this->authorId);
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
	 * updates this Author in mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function update(&$mysqli) {
		//handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		//enforce the authorId is not null(ie don't update an author that hasn't been inserted)
		if($this->authorId === null) {
			throw(new mysqli_sql_exception("unable to update an author that does not exist"));
		}

		//create query template
		$query = "UPDATE author SET authorCourses = ?, authorCredentials = ?, authorName = ?, authorPhoto = ? WHERE authorId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		//bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("ssssi", $this->authorCourses, $this->authorCredentials, $this->authorName, $this->authorPhoto, $this->authorId);
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
	 * gets the Authors courses by content
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param string $authorCourses content to search for
	 * @return mixed array of AuthorCourses found. Courses found, or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getAuthorByAuthorCourses(&$mysqli, $authorCourses) {
		//handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		//sanitize the description before searching
		$authorCourses = trim($authorCourses);
		$authorCourses = filter_var($authorCourses, FILTER_SANITIZE_STRING);

		//create query template
		$query = "SELECT authorId, authorCourses, authorCredentials, authorName, authorPhoto FROM author WHERE authorCourses LIKE?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		//bind the author courses to the place holder in the template
		$authorCourses = "%$authorCourses%";
		$wasClean = $statement->bind_param("s",$authorCourses);
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
					$course = new Course($row["authorId"], $row[authorCourses], $row[authorCredentials], $row[authorName], $row[authorPhoto]);
				$courses[] = $course;
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
		$numberOfCourses = count($courses);
		if($numberOfCourses === 0) {
			return(null);
			} else if($numberOfCourses === 1) {
			return($courses[0]);
			} else {
			return($courses);
		}
	}
}
?>