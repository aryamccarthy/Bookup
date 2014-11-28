-- Title: BookUp_init.sql
-- Summary: sql script to initialize BookUp database
-- Owner: Zack Fout
-- Version: 1.3
-- Last Modified: 11/28/2014
-- Last Modified By: Luke Oglesbee
-- Notes: Added recommender stuff

-- create database
-- DROP DATABASE IF EXISTS BookUpv4;
CREATE DATABASE BookUpv4
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;
USE BookUpv4;

DROP TABLE IF EXISTS Account;
CREATE TABLE IF NOT EXISTS Account(
    email       VARCHAR(30) PRIMARY KEY,
    pass_hash   VARCHAR(30) NOT NULL
);

DROP TABLE IF EXISTS BookList_Good;
CREATE TABLE IF NOT EXISTS BookList_Good( 
    isbn_num    VARCHAR(13) PRIMARY KEY NOT NULL,
    language    VARCHAR(30),
    title       VARCHAR(30) NOT NULL,
    author      VARCHAR(30) NOT NULL,
    description  TEXT,
    image_link_s  TEXT,
    image_link   TEXT
    UsNIQUE (title, author)
);

DROP TABLE IF EXISTS BookList_Good;
CREATE TABLE IF NOT EXISTS BookList_Good( 
    isbn_num    VARCHAR(13) PRIMARY KEY NOT NULL,
    language    VARCHAR(30),
    title       VARCHAR(30),
    author      VARCHAR(30),
    description  TEXT,
    image_link_s  TEXT,
    image_link   TEXT
);

DROP TABLE If EXISTS BookList_Bad;
CREATE TABLE IF NOT EXISTS BookList_Bad(
    isbn_num    VARCHAR(13) PRIMARY KEY,
    reason      VARCHAR(50)
);

DROP TABLE IF EXISTS ISBN_Duplicates;
CREATE TABLE IF NOT EXISTS ISBN_Duplicates(
    isbn_good   VARCHAR(13),
    isbn_bad    VARCHAR(13),
    PRIMARY KEY (isbn_good, isbn_bad),
    FOREIGN KEY (isbn_good) REFERENCES BookList_Good(isbn_num)
);

DROP TABLE IF EXISTS PopularBookList;
CREATE TABLE IF NOT EXISTS PopularBookList(
    isbn_num    VARCHAR(15) PRIMARY KEY,
    FOREIGN KEY(isbn_num) REFERENCES  BookList_Good(isbn_num)
);

DROP TABLE IF EXISTS Rating;
CREATE TABLE IF NOT EXISTS Rating(
    email  VARCHAR(30),
    rating      INT,
    timestamp   DATETIME,
    isbn_num    VARCHAR(15),
    PRIMARY KEY(email, isbn_num),
    FOREIGN KEY(email) REFERENCES Account(email),
    FOREIGN KEY(isbn_num) REFERENCES BookList_Good(isbn_num)
);

DROP TABLE IF EXISTS ReadingList;
CREATE TABLE IF NOT EXISTS ReadingList(
    email  VARCHAR(30),
    timestamp   DATETIME,
    isbn_num    VARCHAR(15),
    PRIMARY KEY(email, isbn_num),
    FOREIGN KEY(email) REFERENCES Account(email),
    FOREIGN KEY(isbn_num) REFERENCES BookList_Good(isbn_num)
);

DROP TABLE IF EXISTS BookSeen;
CREATE TABLE IF NOT EXISTS BookSeen(
    email  VARCHAR(30),
    timestamp   DATETIME,
    isbn_num    VARCHAR(15),
    PRIMARY KEY(email,timestamp,isbn_num),
    FOREIGN KEY(email) REFERENCES Account(email),
    FOREIGN KEY(isbn_num) REFERENCES BookList_Good(isbn_num)
);

--
-- Recommender stuff
--

DROP TABLE IF EXISTS Compare;
CREATE TABLE Compare(
    isbn_num1 VARCHAR(15) NOT NULL DEFAULT '0',
    isbn_num2 VARCHAR(15) NOT NULL DEFAULT '0',
    count INTEGER(11) NOT NULL DEFAULT '0',
    sum INTEGER(11) NOT NULL DEFAULT '0',
    PRIMARY KEY(isbn_num1,  isbn_num2),
    FOREIGN KEY(isbn_num1) REFERENCES BookList_Good(isbn_num),
    FOREIGN KEY(isbn_num2) REFERENCES BookList_Good(isbn_num)
);

/*
*   Not used in the current recommender setup
*   
DROP TABLE IF EXISTS BookHash;
CREATE TABLE IF NOT EXISTS BookHash(
    isbn_num    VARCHAR(15),
    hash_val    INT,
    PRIMARY KEY(isbn_num, hash_val),
    FOREIGN KEY(isbn_num) REFERENCES BookList(isbn_num)
);
*/

/*
*   Not used in the current recommender setup
*
DROP TABLE IF EXISTS AccountHash;
CREATE TABLE IF NOT EXISTS AccountHash(
    email  VARCHAR(30),
    hash_val    INT,
    PRIMARY KEY(email, hash_val),
    FOREIGN KEY(email) REFERENCES Account(email)
); 
*/

