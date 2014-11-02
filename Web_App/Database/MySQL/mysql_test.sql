-- Title: BookUp_test.sql
-- Summary: sql script for testing purposes
-- Owner: Zack Fout
-- Version 1.0
-- Last Modified: 10/31/2014
-- Last Modified By: Luke Oglesbee
-- Notes: added some dummy accounts for api testing
-- 10/23/2014 Added 1984 as book and popular book

USE BookUp;

INSERT INTO Account VALUES
    ("drizzuto@bookup.com", "Candles", true),
    ("amccarthy@bookup.com", "Candles", false),
    ("khabeck@bookup.com", "Candles", true),
    ("zfout@bookup.com", "Candles", true),
    ("loglesbee@bookup.com", "Candles", true),
    ("ngatmaitin@bookup.com", "Candles", true),
    ("ebusbee@bookup.com", "Candles", true);

