-- Title: BookUp_init.sql
-- Summary: sql script to initialize BookUp database
-- Owner: Zack Fout
-- Version: 1.1
-- Last Modified: 10/31/2014
-- Last Modified By: Luke Oglesbee
-- Notes: 
    -- 10/23/2014 Added Author to BookList to accomodate for duplicate titles
    -- 10/31/2014 Changed primary key of BookSeen and Rating to include isbn_num
-- Version: 1.2
-- Last Modified: 10/27/2014
-- Last Modified By: Zack Fout
-- Notes: changed foreign key constraints from account_id to email

-- create database
DROP DATABASE IF EXISTS BookUp;
CREATE DATABASE BookUp;
USE BookUp;

DROP TABLE IF EXISTS Account;

CREATE TABLE IF NOT EXISTS Account(
    email       VARCHAR(30) NOT NULL UNIQUE,
    password    VARCHAR(30) NOT NULL,
    -- new_user    BOOLEAN,
    PRIMARY KEY(email)
);

DROP TABLE IF EXISTS BookList;

CREATE TABLE IF NOT EXISTS BookList( 
    isbn_num    VARCHAR(15) NOT NULL UNIQUE,
    PRIMARY KEY(isbn_num)
);

DROP TABLE IF EXISTS PopularBookList;

CREATE TABLE IF NOT EXISTS PopularBookList(
    isbn_num    VARCHAR(15) NOT NULL UNIQUE,
    PRIMARY KEY(isbn_num),
    FOREIGN KEY(isbn_num) REFERENCES  BookList(isbn_num)
);

DROP TABLE IF EXISTS Rating;

CREATE TABLE IF NOT EXISTS Rating(
    email  VARCHAR(30),
    rating      INT,
    timestamp   DATETIME,
    isbn_num    VARCHAR(15),
    PRIMARY KEY(email, isbn_num),
    FOREIGN KEY(email) REFERENCES Account(email),
    FOREIGN KEY(isbn_num) REFERENCES BookList(isbn_num)
);

DROP TABLE IF EXISTS ReadingList;

CREATE TABLE IF NOT EXISTS ReadingList(
    email  VARCHAR(30),
    timestamp   DATETIME,
    isbn_num    VARCHAR(15),
    PRIMARY KEY(email, isbn_num),
    FOREIGN KEY(email) REFERENCES Account(email),
    FOREIGN KEY(isbn_num) REFERENCES BookList(isbn_num)
);

DROP TABLE IF EXISTS BookSeen;

CREATE TABLE IF NOT EXISTS BookSeen(
    email  VARCHAR(30),
    rating      INT,
    timestamp   DATETIME,
    isbn_num    VARCHAR(15),
    PRIMARY KEY(email,isbn_num),
    FOREIGN KEY(email) REFERENCES Account(email),
    FOREIGN KEY(isbn_num) REFERENCES BookList(isbn_num)
);

DROP TABLE IF EXISTS BookHash;

CREATE TABLE IF NOT EXISTS BookHash(
    isbn_num    VARCHAR(15),
    hash_val    INT,
    PRIMARY KEY(hash_val),
    FOREIGN KEY(isbn_num) REFERENCES BookList(isbn_num)
);

DROP TABLE IF EXISTS AccountHash;

CREATE TABLE IF NOT EXISTS AccountHash(
    email  VARCHAR(30),
    hash_val    INT,
    PRIMARY KEY(hash_val),
    FOREIGN KEY(email) REFERENCES Account(email)
); 
