-- clearing tables
DROP TABLE IF EXISTS student;
DROP TABLE IF EXISTS author;
DROP TABLE IF EXISTS course;

-- create user table
CREATE TABLE student (
-- this creates the attribute for the primary key
-- auto_increment tells mySQL to number them (1,2,3,...)
-- NOT NULL MEANS THE ATTRIBUTE IS REQUIRED!
	studentId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	studentProfile VARCHAR(256)NOT NULL,
-- to make something optional, exclude the not null
	studentInterests VARCHAR(256) NOT NULL,
	PRIMARY KEY (studentId)
);
-- create author table
CREATE TABLE author (
	authorId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	authorCourses VARCHAR(256) NOT NULL,
	authorCredentials VARCHAR(256) NOT NULL,
	authorName VARCHAR(160) NOT NULL,
	authorPhoto VARCHAR(160) NOT NULL,
	PRIMARY KEY(authorId)
);
-- create the favorite entity (a weak entity from an m-to-n for profile -->tweet)
CREATE TABLE course (
	-- these are not auto_increment because they're still foreign keys
	courseId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	authorId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	courseTitle VARCHAR(160),
	courseOverview VARCHAR(256),
-- index the foreign keys
	INDEX (authorId),
	FOREIGN KEY(authorId) REFERENCES author(authorId),
	PRIMARY KEY (courseId)
	);