__author__ = 'DRizzuto'

import MySQLdb
import time
import json
import requests

import recursiveJsonSearch as rJS


def addToDatabase(isbnArray, db):

    """
    :param isbnArray: array of isbns being added to database
    :param db: a connection to the database
    :return: no return

    This function accepts a connection to a database and an array of isbns and adds them to the database
    The google API call will be tested for a Title, Author, Description, and Image Link

    The language will also be taken down for possible future multi-langual versions
    """

    file = open('book_object_list.txt', "w+")

    cursor = db.cursor()

    for isbn in isbnArray:

        deleteFromBookList_Bad(isbn, db)

        time.sleep(2)

        # print isbn

        googleQuery = 'https://www.googleapis.com/books/v1/volumes?q=isbn:' + str(isbn)

        googleRequest = requests.get(googleQuery)

        string = googleRequest.content
        file.write(string)
        file.write("\n")

        googleRequest = json.loads(googleRequest.content)

        if "error" in googleRequest:

            try:

                cursor.execute("INSERT INTO BookList_Bad VALUES (%s, %s)", (isbn, "API Call returned Error"))

                # print "\t API CALL ERROR"

                db.commit()

            except MySQLdb.Error, e:
                pass

                # print "\t API CALL ERROR - Not in DB"

        else:

            bookObject = rJS.find_key(googleRequest, 'title')
            authors1 = rJS.find_key(googleRequest, 'authors')
            language1 = rJS.find_key(googleRequest, 'language')
            description1 = rJS.find_key(googleRequest, 'description')
            thumbnail1 = rJS.find_key(googleRequest, 'thumbnail')
            small_thumbnail1 = rJS.find_key(googleRequest, "smallThumbnail")

            # print title1
            # print authors1
            # print language1
            # print description1
            # print thumbnail1
            # print small_thumbnail1

            if len(bookObject) is 6:

                title = bookObject[0]

                authors = ""
                for author in bookObject[1]:
                    authors = author + ", " + authors

                language = bookObject[2]
                description = bookObject[3]
                thumbnail = bookObject[4]
                sThumbnail = bookObject[5]

                try:

                    cursor.execute("INSERT INTO BookList_Good VALUES (%s, %s, %s, %s, %s, %s, %s)", (isbn, language, title, authors, description, sThumbnail, thumbnail))

                    db.commit()

                    # print "\t Added to good"


                except MySQLdb.Error, e:

                    try:

                        cursor.execute("INSERT INTO BookList_Bad VALUES (%s, %s)", (isbn, e.args[1]))

                        # print "\t added to bad"

                        # print e.args[1]


                        db.commit()

                    except MySQLdb.Error, e:

                        pass
                        # print "\t tried bad"

                except:

                    try:

                        cursor.execute("INSERT INTO BookList_Bad VALUES (%s, %s)", (isbn, "God Only Knows Man"))

                        # print "\t God Only Knows Man"

                        db.commit()

                    except:
                        pass


            else:

                try:

                    cursor.execute("INSERT INTO BookList_Bad VALUES (%s, %s)", (isbn, "Not all API info available"))

                    db.commit()

                    # print "\t not all info available"


                except MySQLdb.Error, e:
                    pass

                    # print "\t tried no info, fail"

                    # print "\t" + e.args[1]


            del bookObject [:]

    file.close()

def collateByUsageLimit(db):

    cursor = db.cursor()

    cursor.execute("SELECT isbn_num FROM BookList_Bad WHERE reason = %s",
        ("API Call returned Error"))

    isbns = [isbn[0] for isbn in cursor.fetchall()]

    return isbns


def deleteFromBookList_Bad(isbn, db):

    cursor = db.cursor()

    cursor.execute("DELETE FROM BookList_Bad WHERE isbn_num = %s", (isbn))

    db.commit()

def updateCronJobLog(file, db):

    cursor = db.cursor()

    cursor.execute("INSERT INTO CronJobLog VALUES (%s, NOW())", file)

    db.commit()

if __name__ == "__main__":
    print "Meant to be accessed from manageDatabase.py"