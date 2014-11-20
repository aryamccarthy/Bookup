-- Title: BookUp_init.sql
-- Summary: sql script to initialize BookUp database
-- Owner: Zack Fout
-- Version: 1.2
-- Last Modified: 11/2/2014
-- Last Modified By: Luke Oglesbee
-- Notes: removed new_user from account

-- create database
DROP DATABASE IF EXISTS BookUp;
CREATE DATABASE BookUp;
USE BookUp;



DROP TABLE IF EXISTS Account;

CREATE TABLE IF NOT EXISTS Account(
    email       VARCHAR(30) PRIMARY KEY,
    password    VARCHAR(30) NOT NULL
);



DROP TABLE IF EXISTS BookList;

CREATE TABLE IF NOT EXISTS BookList( 
    isbn_num    VARCHAR(15) PRIMARY KEY
);



DROP TABLE IF EXISTS PopularBookList;

CREATE TABLE IF NOT EXISTS PopularBookList(
    isbn_num    VARCHAR(15) PRIMARY KEY,
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
    PRIMARY KEY(hash_val), -- This seems like a mistake.
    -- If the hash_val is intended to bucket data, then it can't be unique.
    -- Unique hash_val forces fill of buckets with single elements.
    FOREIGN KEY(isbn_num) REFERENCES BookList(isbn_num)
);



DROP TABLE IF EXISTS AccountHash;

CREATE TABLE IF NOT EXISTS AccountHash(
    email  VARCHAR(30),
    hash_val    INT,
    PRIMARY KEY(hash_val), -- This also seems like a mistake. See above.
    FOREIGN KEY(email) REFERENCES Account(email)
); 
