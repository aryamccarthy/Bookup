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
    ("drizzuto@bookup.com", "Candles", true),
    ("amccarthy@bookup.com", "Candles", true),
    ("khabeck@bookup.com", "Candles", true),
    ("zfout@bookup.com", "Candles", true),
    ("loglesbee@bookup.com", "Candles", true),
    ("ngatmaitin@bookup.com", "Candles", true),
    ("ebusbee@bookup.com", "Candles", true);

INSERT INTO BookList VALUES ("9780001000391"), 
							("9780006479901"), 
							("9780060012380"), 
							("9780451190758"), 
							("9781862301382"), 
							("9781595143211"), 
							("9781595142504"), 
							("9781595140838"), 
							("9781594743344"), 
							("9781580493888"), 
							("9781577314806");

INSERT INTO PopularBookList VALUES ("9780001000391"), 
								("9780006479901"), 
								("9780060012380"), 
								("9780451190758"), 
								("9781862301382"), 
								("9781595143211"), 
								("9781595142504"), 
								("9781595140838"), 
								("9781594743344"), 
								("9781580493888"), 
								("9781577314806");

INSERT INTO ReadingList VALUES ("drizzuto@bookup.com", NOW(), "9781577314806"),
								("drizzuto@bookup.com", NOW(), "9780001000391");
