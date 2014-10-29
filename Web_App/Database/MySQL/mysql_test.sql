-- Title: BookUp_test.sql
-- Summary: sql script for testing purposes
-- Owner: Zack Fout
-- Version 1.0
-- Last Modified: 10/23/2014
-- Last Modified By: Danny Rizzuto
-- Notes: added some dummy accounts for api testing
-- 10/23/2014 Added 1984 as book and popular book

USE BookUp;

INSERT INTO Account VALUES
    ("test1.Old@bookup.com", "BookUp1", False),
    ("test2.Old@bookup.com", "BookUp2", False),
    ("test3.Old@bookup.com", "BookUp3", False),
    ("test4.New@bookup.com", "BookUp4", True),
    ("test5.New@bookup.com", "BookUp5", true);

INSERT INTO BookList VALUE
	("9780547249643", "1984", "George Orwell"),
	("6789502039469", "Harry Potter", "JK Rowling");

INSERT INTO PopularBook VALUE
	("9780547249643");
