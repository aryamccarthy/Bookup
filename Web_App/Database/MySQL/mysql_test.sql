-- Title: BookUp_test.sql
-- Summary: sql script for testing purposes
-- Owner: Zack Fout
-- Version 1.0
-- Last Modified: 10/22/2014
-- Last Modified By: Zack Fout
-- Notes: added some dummy accounts for api testing

USE BookUp;

INSERT INTO Account VALUES
    (1, "test1@bookup.com", "BookUp1", False),
    (2, "test2@bookup.com", "BookUp2", False),
    (3, "test3@bookup.com", "BookUp3", False),
    (4, "test4@bookup.com", "BookUp4", False),
    (5, "test5@bookup.com", "BookUp5", False);
