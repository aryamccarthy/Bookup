USE BookUp;
START TRANSACTION;

CREATE TABLE Account(
    email       VARCHAR(30) PRIMARY KEY,
    password    VARCHAR(30) NOT NULL -- this should really be hashed.
    -- we should check out the php-login set.
    -- deleted newUser because this is a derived quantity.
);

CREATE TABLE BookList( 
    isbn_num    VARCHAR(15) PRIMARY KEY,
    title       VARCHAR(45) NOT NULL,
    author      VARCHAR(45) NOT NULL
    -- there are missing columns in the original file. please incorporate
);

CREATE TABLE PopularBook(
    isbn_num    VARCHAR(15) PRIMARY KEY,
    FOREIGN KEY(isbn_num) REFERENCES BookList(isbn_num)
);

CREATE TABLE Rating(
    email       VARCHAR(30),
    rating      INT,
    timestamp   DATETIME,
    isbn_num    VARCHAR(15),
    PRIMARY KEY(email, isbn_num), -- Changed b/c otherwise, it breaks.
    FOREIGN KEY(email) REFERENCES Account(email),
    FOREIGN KEY(isbn_num) REFERENCES BookList(isbn_num)
);

CREATE TABLE ReadingList(
    email       VARCHAR(30) NOT NULL UNIQUE,
    timestamp   DATETIME,
    isbn_num    VARCHAR(15) NOT NULL UNIQUE,
    -- PRIMARY KEY(email), -- This is a bad idea.
    FOREIGN KEY(email) REFERENCES Account(email),
    FOREIGN KEY(isbn_num) REFERENCES BookList(isbn_num)
);

CREATE TABLE BookSeen(
    email  VARCHAR(30) NOT NULL UNIQUE,
    rating      INT,
    timestamp   DATETIME,
    isbn_num    VARCHAR(15) NOT NULL UNIQUE,
    PRIMARY KEY(email, isbn_num),
    FOREIGN KEY(email) REFERENCES Account(email),
    FOREIGN KEY(isbn_num) REFERENCES BookList(isbn_num)
);

CREATE TABLE BookHash( -- I still see no purpose for this table.
    isbn_num    VARCHAR(15) NOT NULL UNIQUE,
    hash_val    INT,
    PRIMARY KEY(hash_val),
    FOREIGN KEY(isbn_num) REFERENCES BookList(isbn_num)
);

CREATE TABLE AccountHash( -- I still see no purpose for this table.
    email  VARCHAR(30) NOT NULL UNIQUE,
    hash_val    INT,
    PRIMARY KEY(hash_val),
    FOREIGN KEY(email) REFERENCES Account(email)
); 

CREATE TABLE dev ( -- @see http://lemire.me/fr/documents/publications/webpaper.pdf
    itemID1 int (11) NOT NULL default '0 ' ,
    itemID2 int (11) NOT NULL default '0 ' ,
    count int (11) NOT NULL default '0 ' ,
    sum int(11) NOT NULL default '0',
    PRIMARY KEY ( itemID1 , itemID2 )
);

COMMIT;
