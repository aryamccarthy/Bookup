-- Title: BookUp_init.sql
-- Summary: sql script to initialize BookUp database
-- Owner: Zack Fout
-- Version: 1.3
-- Last Modified: 11/11/2014
-- Last Modified By: Zack Fout
-- Notes: updated BookList and PopularBookList for new schema

-- create database
# DROP DATABASE IF EXISTS BookUp;
CREATE DATABASE BookUp;
USE BookUp;

<<<<<<< HEAD:database/mysql/mysql_init.sql
DROP TABLE IF EXISTS Account;

CREATE TABLE IF NOT EXISTS Account(
    email       VARCHAR(30) PRIMARY KEY,
    password    VARCHAR(30) NOT NULL
) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

DROP TABLE IF EXISTS BookList;

CREATE TABLE IF NOT EXISTS BookList( 
    isbn_num    VARCHAR(15),
    title       VARCHAR(30),
    author      VARCHAR(30),
    description TEXT,
    thumbnail   BLOB,
    price       DOUBLE,
    PRIMARY KEY(isbn_num)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

DROP TABLE IF EXISTS PopularBookList;

CREATE TABLE IF NOT EXISTS PopularBookList(
    isbn_num    VARCHAR(15),
    title       VARCHAR(30),
    author      VARCHAR(30),
    description TEXT,
    thumbnail   BLOB,
    price       DOUBLE,
    PRIMARY KEY(isbn_num),
    FOREIGN KEY(isbn_num) REFERENCES  BookList(isbn_num)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

DROP TABLE IF EXISTS Rating;
=======
# DROP TABLE IF EXISTS Account;

CREATE TABLE IF NOT EXISTS Account(
    email       VARCHAR(30) PRIMARY KEY,
    pass_hash    VARCHAR(30) NOT NULL
) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

# DROP TABLE IF EXISTS BookList_Good;

CREATE TABLE IF NOT EXISTS BookList_Good( 
    isbn_num    VARCHAR(13) PRIMARY KEY NOT NULL,
    language    VARCHAR(30),
    title       VARCHAR(100) NOT NULL,
    author      VARCHAR(100) NOT NULL,
    descrition  TEXT,
    image_link_s  TEXT,
    image_link   TEXT,
    UNIQUE (title, author)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

# DROP TABLE If EXISTS BookList_Bad;

CREATE TABLE IF NOT EXISTS BookList_Bad(
    isbn_num    VARCHAR(13) PRIMARY KEY,
    reason      VARCHAR(100)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;


# DROP TABLE IF EXISTS ISBN_Duplicates;

CREATE TABLE IF NOT EXISTS ISBN_Duplicates(
    isbn_good   VARCHAR(13),
    isbn_bad    VARCHAR(13),
    PRIMARY KEY (isbn_good, isbn_bad),
    FOREIGN KEY (isbn_good) REFERENCES BookList_Good(isbn_num)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;


# DROP TABLE IF EXISTS PopularBookList;

CREATE TABLE IF NOT EXISTS PopularBookList(
    isbn_num    VARCHAR(13) PRIMARY KEY,
    FOREIGN KEY(isbn_num) REFERENCES  BookList_Good(isbn_num)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;



# DROP TABLE IF EXISTS Rating;
>>>>>>> a6860f33d73e7f133726bd3c3396d720c2f5fa54:Web_App/Database/MySQL/mysql_init.sql

CREATE TABLE IF NOT EXISTS Rating(
    email  VARCHAR(30),
    rating      INT,
    timestamp   DATETIME,
    isbn_num    VARCHAR(15),
    PRIMARY KEY(email, isbn_num),
    FOREIGN KEY(email) REFERENCES Account(email),
    FOREIGN KEY(isbn_num) REFERENCES BookList_Good(isbn_num)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

<<<<<<< HEAD:database/mysql/mysql_init.sql
DROP TABLE IF EXISTS ReadingList;
=======


# DROP TABLE IF EXISTS ReadingList;
>>>>>>> a6860f33d73e7f133726bd3c3396d720c2f5fa54:Web_App/Database/MySQL/mysql_init.sql

CREATE TABLE IF NOT EXISTS ReadingList(
    email  VARCHAR(30),
    timestamp   DATETIME,
    isbn_num    VARCHAR(15),
    PRIMARY KEY(email, isbn_num),
    FOREIGN KEY(email) REFERENCES Account(email),
    FOREIGN KEY(isbn_num) REFERENCES BookList_Good(isbn_num)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

<<<<<<< HEAD:database/mysql/mysql_init.sql
DROP TABLE IF EXISTS BookSeen;
=======


# DROP TABLE IF EXISTS BookSeen;
>>>>>>> a6860f33d73e7f133726bd3c3396d720c2f5fa54:Web_App/Database/MySQL/mysql_init.sql

CREATE TABLE IF NOT EXISTS BookSeen(
    email  VARCHAR(30),
    rating      INT,
    timestamp   DATETIME,
    isbn_num    VARCHAR(15),
    PRIMARY KEY(email,isbn_num),
    FOREIGN KEY(email) REFERENCES Account(email),
    FOREIGN KEY(isbn_num) REFERENCES BookList_Good(isbn_num)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

<<<<<<< HEAD:database/mysql/mysql_init.sql
DROP TABLE IF EXISTS BookHash;
=======


# DROP TABLE IF EXISTS BookHash;
>>>>>>> a6860f33d73e7f133726bd3c3396d720c2f5fa54:Web_App/Database/MySQL/mysql_init.sql

CREATE TABLE IF NOT EXISTS BookHash(
    isbn_num    VARCHAR(15),
    hash_val    INT,
<<<<<<< HEAD:database/mysql/mysql_init.sql
    PRIMARY KEY(isbn_num),
    FOREIGN KEY(isbn_num) REFERENCES BookList(isbn_num)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

DROP TABLE IF EXISTS AccountHash;
=======
    PRIMARY KEY(hash_val), -- This seems like a mistake.
    -- If the hash_val is intended to bucket data, then it can't be unique.
    -- Unique hash_val forces fill of buckets with single elements.
    FOREIGN KEY(isbn_num) REFERENCES BookList_Good(isbn_num)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;



# DROP TABLE IF EXISTS AccountHash;
>>>>>>> a6860f33d73e7f133726bd3c3396d720c2f5fa54:Web_App/Database/MySQL/mysql_init.sql

CREATE TABLE IF NOT EXISTS AccountHash(
    email  VARCHAR(30),
    hash_val    INT,
    PRIMARY KEY(email),
    FOREIGN KEY(email) REFERENCES Account(email)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci; 
