import MySQLdb

import recursiveJsonSearch as rJS
import connectToDB as cDB
import fileReader as fR
import interactWithDB as iWDB

if __name__ == "__main__":

    isbnArray = fR.readGivenFile("../Isbn_Txt_Files/Not_Read/one_isbn.txt")

    db = cDB.connect()

    iWDB.addToDatabase(isbnArray, db)

    db.close()







