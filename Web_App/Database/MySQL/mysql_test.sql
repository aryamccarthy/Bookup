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

INSERT INTO BookList VALUES
	("9780439023481"),
	("9780439358071"),
	("9780316015844"),
	("9780061120084"),
	("9780679783268"),
	("9780446675536"),
	("9780066238500"),
	("9780452284241"),
	("9780060256654"),
	("9780345391803"),
	("9780393978896"),
	("9780739326220"),
	("9780307277671"),
	("9780375831003"),
	("9780451527745"),
	("9780743477116"),
	("9780451525260"),
	("9780345538376"),
	("9780140283334"),
	("9780812550702"),
	("9780375751516"),
	("9780143058144"),
	("9780064410939"),
	("9780451528827"),
	("9780061122415"),
	("9780007491568"),
	("9780525478812"),
	("9780142000670"),
	("9780440498056"),
	("9780393970128"),
	("9780345418265"),
	("9780060929879"),
	("9780399155345"),
	("9780060531041"),
	("9780142437209"),
	("9780099408390"),
	("9780142437179"),
	("9780316166683"),
	("9781594489501"),
	("9780517189603"),
	("9781565125605"),
	("9780394800165"),
	("9780062024039"),
	("9781416914280"),
	("9780141439600"),
	("9780770430078"),
	("9780143039952"),
	("9780345803924"),
	("9780671027346"),
	("9780385333849"),
	("9780140385724"),
	("9780684833392"),
	("9780451207142"),
	("9780316769174"),
	("9780340839935"),
	("9780380395866"),
	("9780743273565"),
	("9780385199575"),
	("9780743454537"),
	("9780141439471"),
	("9780192833594"),
	("9780061120077"),
	("9780192835086"),
	("9780007205233"),
	("9780451163967"),
	("9780385490818"),
	("9780307265432"),
	("9780061148514"),
	("9780316323703"),
	("9780060786502"),
	("9780671727796"),
	("9780393312836"),
	("9780156012195"),
	("9780374528379"),
	("9780553588484"),
	("9780440242949"),
	("9780452011878"),
	("9780786838653"),
	("9780553208849"),
	("9780142437230"),
	("9781594480003"),
	("9780552135399"),
	("9780307269751"),
	("9780312353766"),
	("9780142437247"),
	("9780141301068"),
	("9780553816716"),
	("9780451529305"),
	("9780385732550"),
	("9780679879244"),
	("9780345476876"),
	("9780312422158"),
	("9780142001745"),
	("9780684830490"),
	("9780385074070"),
	("9780525467564"),
	("9780140042597"),
	("9780143039563"),
	("9780618346257"),
	("9780142403884");

INSERT INTO PopularBookList VALUES
	("9780439023481"),
	("9780439358071"),
	("9780316015844"),
	("9780061120084"),
	("9780679783268"),
	("9780446675536"),
	("9780066238500"),
	("9780452284241"),
	("9780060256654"),
	("9780345391803"),
	("9780393978896"),
	("9780739326220"),
	("9780307277671"),
	("9780375831003");

INSERT INTO ReadingList VALUES
	("drizzuto@bookup.com", NOW(), "9780439023481"),
	("drizzuto@bookup.com", NOW(), "9780439358071"),
	("loglesbee@bookup.com", NOW(), "9780142437230"),
	("loglesbee@bookup.com", NOW(), "9780385074070"),
	("loglesbee@bookup.com", NOW(), "9780375831003"),
	("amccarthy@bookup.com", NOW(), "9780553816716"),
	("amccarthy@bookup.com", NOW(), "9780451529305"),
	("amccarthy@bookup.com", NOW(), "9780385732550"),
	("amccarthy@bookup.com", NOW(), "9780679879244");

