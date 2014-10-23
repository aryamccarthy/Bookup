-- Title: BookUp_init.sql
-- Summary: sql script to initialize BookUp database
-- Owner: Zack Fout
-- Version: 1.1
-- Last Modified: 10/23/2014
-- Last Modified By: Danny Rizzuto
-- Notes: 
-- 10/23/2014 Added Author to BookList to accomodate for duplicate titles

-- create database
DROP DATABASE IF EXISTS BookUp;
CREATE DATABASE BookUp;
USE BookUp;

DROP TABLE IF EXISTS Account;

CREATE TABLE IF NOT EXISTS Account(
    account_id  INT NOT NULL UNIQUE,
    email       VARCHAR(30) NOT NULL,
    password    VARCHAR(30) NOT NULL,
    new_user    BOOLEAN,
    PRIMARY KEY(account_id)
);

DROP TABLE IF EXISTS BookList;

CREATE TABLE IF NOT EXISTS BookList( 
    isbn_num    BIGINT NOT NULL UNIQUE,
    title       VARCHAR(45) NOT NULL,
    author		VARCHAR(45) NOT NULL,
    PRIMARY KEY(isbn_num)
);

DROP TABLE IF EXISTS PopularBook;

CREATE TABLE IF NOT EXISTS PopularBook(
    isbn_num    BIGINT NOT NULL UNIQUE,
    PRIMARY KEY(isbn_num),
    FOREIGN KEY(isbn_num) REFERENCES  BookList(isbn_num)
);

DROP TABLE IF EXISTS Rating;

CREATE TABLE IF NOT EXISTS Rating(
    account_id  INT NOT NULL UNIQUE,
    rating      INT,
    timestamp   DATETIME,
    isbn_num    BIGINT NOT NULL UNIQUE,
    PRIMARY KEY(account_id),
    FOREIGN KEY(account_id) REFERENCES Account(account_id),
    FOREIGN KEY(isbn_num) REFERENCES BookList(isbn_num)
);

DROP TABLE IF EXISTS ReadingList;

CREATE TABLE IF NOT EXISTS ReadingList(
    account_id  INT NOT NULL UNIQUE,
    timestamp   DATETIME,
    isbn_num    BIGINT NOT NULL UNIQUE,
    PRIMARY KEY(account_id),
    FOREIGN KEY(account_id) REFERENCES Account(account_id),
    FOREIGN KEY(isbn_num) REFERENCES BookList(isbn_num)
);

DROP TABLE IF EXISTS BookSeen;

CREATE TABLE IF NOT EXISTS BookSeen(
    account_id  INT NOT NULL UNIQUE,
    rating      INT,
    timestamp   DATETIME,
    isbn_num    BIGINT NOT NULL UNIQUE,
    PRIMARY KEY(account_id),
    FOREIGN KEY(account_id) REFERENCES Account(account_id),
    FOREIGN KEY(isbn_num) REFERENCES BookList(isbn_num)
);

DROP TABLE IF EXISTS BookHash;

CREATE TABLE IF NOT EXISTS BookHash(
    isbn_num    BIGINT NOT NULL UNIQUE,
    hash_val    INT,
    PRIMARY KEY(hash_val),
    FOREIGN KEY(isbn_num) REFERENCES BookList(isbn_num)
);

DROP TABLE IF EXISTS AccountHash;

CREATE TABLE IF NOT EXISTS AccountHash(
    account_id  INT NOT NULL UNIQUE,
    hash_val    INT,
    PRIMARY KEY(hash_val),
    FOREIGN KEY(account_id) REFERENCES Account(account_id)
); 
