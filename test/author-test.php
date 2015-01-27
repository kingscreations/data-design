<?php
// first, require the SimpleTest framework <http://www.simpletest.org/>
// this path is *not* universal, but deployed on the bootcamp-coders server
require_once("/usr/lib/php5/simpletest/autorun.php");

// next, require the class from the project under scrutiny
require_once("../php/classes/author.php");

/**
 *  Unit test for the author class
 *
 *  This is a simpletest test case for the CRUD methods of the author class
 *
 * @see Author
 * @author Jason King <jason@kingscreations.org>
 **/
class AuthorTest extends UnitTestCase
	/**
	 * mysql object shared amongst all tests
	 **/
	private $mysqli = null;

	/**
	 * instance of the object
	 **/
	private $author = null;

// this section contains member variables with constants needed for creating a new author
/**
 * profile id of the person who is inserting the test author
 * @deprecated a parent class of type profile should be used here instead
 **/
private $profileId = 1;
/**
 * content of the test author
 **/
private $authorContent = "My unit tests pass!";
/**
 * date the Author was created
 **/
private $authorDate = null;

/**
	 * sets up the mySQL connection for this test
	**/
	public function setUp() {
	//first, connect to mysqli
	mysqli_report(MYSQLI_REPORT_STRICT);
	$this->mysqli = new mysqli("localhost", "jking", "wahulantouchlucre", " jking");

	//second, create an instance of the object under scrutiny
	$this->authorDate = new DateTime();
	$this->author = new Author(null, "my", "unit", "test", "passed");
}

/**
 * tears down the connection to mysql and deletes the test instance object
 **/
public function tearDown() {
	// destroy the object if it was created
	if($this->author !== null) {
		$this->author->delete($this->mysqli);
		$this->author = null;
	}

	//disconnect from mySQL
	if($this->mysqli !==null) {
		$this->mysqli->close();
		$this->mysqli = null;
		}
}

/**
 * test inserting a valid Author into mySQL
 **/
public function testInsertValidAuthor() {
	// zeroth, ensure the Author and mySQL class are sane
	$this->assertNotNull($this->author);
	$this->assertNotNull($this->mysqli);

	// first, insert the Author into mySQL
	$this->author->insert($this->mysqli);

	// second, grab an Author in mySQL
	$mysqlAuthor = Author :: getAuthorByAuthorId($this->mysqli, $this->author->getAuthorId());

	// third, assert the Author we have created and mySQL's Author are the same object
	$this->assertIdentical($this->author->getAuthorId(), $mysqlAuthor->getAuthorId());
	$this->assertIdentical($this->author->getAuthorCourses(), $mysqlAuthor->getAuthorCourses());
	$this->assertIdentical($this->author->getAuthorCredentials(), $mysqlAuthor->getAuthorCredentials());
	$this->assertIdentical($this->author->getAuthorName(), $mysqlAuthor->getAuthorName());
	$this->assertIdentical($this->author->getAuthorPhoto(), $mysqlAuthor->getAuthorPhoto());
}

/**
 * test inserting an invalid Author into mySQL
 */
public function testInsertedInvalidAuthor() {
	// zeroth, ensure the Author and mySQL class are sane
	$this->assertNotNull($this->author);
	$this->assertNotNull($this->mysqli);

	// first, set the author id to an invented value that should never insert in the first place
	$this->author->setAuthorId(42);

	//second, try to insert the Author and ensure the exception is thrown
	$this->expectException("mysqli_sql_exception");
	$this->author->insert($this->mysqli);

	// third, set the Author to null to prevent tearDown() from deleting an author that never existed
	$this->author = null;

}

/**
 * test deleting an author from mySQL
 **/
public function testDeleteValidAuthor() {
	//zeroth, ensure the author and mySQL class are sane
	$this->assertNotNull($this->author);
	$this->assertNotNull($this->mysqli);

	// first, assert the author is inserted into mySQL by grabbing it from mySQL and asserting the primary key
	$this->author->insert($this->mysqli);
	$mysqlAuthor = Author :: getAuthorByAuthorId($this->mysqli, $this->author->getAuthorId());
	$this->assertIdentical($this->author->getAuthorId(), $mysqlAuthor->getAuthorId());

	// second, delete the Author from mySQL and re-grab it from mySQL and assert it does not exist
	$this->author->delete($this->mysqli);
	$mysqlAuthor = Author :: getAuthorByAuthorId($this->mysqli, $this->author->getAuthorId());
	$this->assertNull($mysqlAuthor);

	// third, set the Author to null to prevent tearDown() from deleting an Author that has already been deleted
	$this->author = null;
}

/**
 * test deleting an Author from mySQL that does not exist
 **/
public function testDeleteInvalidAuthor() {
	// zeroth, ensure the Author and mySQL class are sane
	$this->assertNotNull($this->author);
	$this->assertNotNull($this->mysqli);

	// first, try to delete the Author before inserting it and ensure the exception is thrown
	$this->expectException("mysqli_sql_exception");
	$this->author->delete($this->mysqli);

	// second, change the Author, update it mySQL
	$newContent = "My unit tests updated everything";
	$this->author->setAuthorName($newName);
	$this->author->update($this->mysqli);

	// third, re-grab the Author from mySQL
	$mysqlAuthor - Author :: getAuthorByAuthorId($this->mysqli, $this->author->getAuthorId());
	$this->assertNotNull(mysqlAuthor);

	// fourth, assert the Author we have updated and mySQL's Author are the same object
	$this->assertIdentical($this->author->getAuthorId(), $mysqlAuthor->getAuthorId());
	$this->assertIdentical($this->author->getAuthorCourses(), $mysqlAuthor->getAuthorCourses());
	$this->assertIdentical($this->author->getAuthorCredentials(), $mysqlAuthor->getAuthorCredentials());
	$this->assertIdentical($this->author->getAuthorName(), $mysqlAuthor->getAuthorName());
	$this->assertIdentical($this->author->getAuthorPhoto(), $mysqlAuthor->getAuthorPhoto());
}

/**
 * test updating an Author from mySQL that does not exist
 **/
public function testUpdateInvalidAuthor() {
	//zeroth, ensure the Author and mySQL class are sane
	$this->assertNotNull($this->author);
	$this->assertNotNull($this->mysqli);

	// first, try to update the Author before inserting it and ensure the exception is thrown
	$this->expectException("mysqli_sql_exception");
	$this->author->update)($this->mysqli);

	// second, set the Author to null to prevent tearDown() from deleting an Author that has already been deleted
	]$this->author = null;

}
?>