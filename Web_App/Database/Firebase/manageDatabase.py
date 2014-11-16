from firebase import firebase
import sys
import requests
import MySQLdb
import json
import recursiveJsonSearch as rJS

def readFile():

    with open("Isbn_Txt_Files/list_of_5000_isbn.txt") as file:
      isbnArray = file.read().splitlines()

    return isbnArray

def connectToBookUp():

    try:

        db = MySQLdb.connect(unix_socket ="/Applications/MAMP/tmp/mysql/mysql.sock", host="localhost", user="root", passwd="root", db="BookUp")

        return db

    except MySQLdb.Error, e:

        print "Could not connect to database, got error:\n %s" % (e.args[1])
        sys.exit(1)

def addToDatabase(isbnArray, db):

    """
    :param isbnArray: array of isbns being added to database
    :param db: a connection to the database
    :return: no return

    This function accepts a connection to a database and an array of isbns and adds them to the database
    The google API call will be tested for a Title, Author, Description, and Image Link

    The language will also be taken down for possible future multi-langual versions
    """
    cursor = db.cursor()

    for isbn in isbnArray:

        googleQuery = 'https://www.googleapis.com/books/v1/volumes?q=isbn:' + str(isbn)

        googleRequest = requests.get(googleQuery)

        googleRequest = json.loads(googleRequest.content)

        if "error" in googleRequest:

            try:

                cursor.execute("INSERT INTO BookList_Bad VALUES (%s, %s)", (isbn, "API Call returned Error"))

                db.commit()

            except MySQLdb.Error, e:

                pass

        else:

            bookObject = rJS.find_key(googleRequest, "title")
            rJS.find_key(googleRequest, 'authors')
            rJS.find_key(googleRequest, "language")
            rJS.find_key(googleRequest, "description")
            rJS.find_key(googleRequest, "thumbnail")
            rJS.find_key(googleRequest, "smallThumbnail")

            if len(bookObject) is 6:

                title = bookObject[0]

                authors = ""
                for author in bookObject[1]:
                    authors = author + ", " + authors

                language = bookObject[2]
                description = bookObject[3]
                thumbnail = bookObject[4]
                sThumbnail = bookObject[5]

                print isbn
                print title
                print "--------------------------"
                # print authors
                # print language
                # print description
                # print thumbnail
                # print sThumbnail
                # print "--------------------------"

                try:

                    cursor.execute("INSERT INTO BookList_Good VALUES (%s, %s, %s, %s, %s, %s, %s)", (isbn, language, title, authors, description, sThumbnail, thumbnail))

                    db.commit()

                except MySQLdb.Error, e:

                    try:

                        cursor.execute("INSERT INTO BookList_Bad VALUES (%s, %s)", (isbn, e.args[1]))

                        db.commit()

                    except MySQLdb.Error, e:

                        pass

            else:

                try:

                    cursor.execute("INSERT INTO BookList_Bad VALUES (%s, %s)", (isbn, "Not all API info available"))

                    db.commit()

                except MySQLdb.Error, e:

                     pass

def cleanBookList_Bad(db):

    cursor = db.cursor()

    cursor.execute("SELECT * FROM BookList_Bad")

    rows = cursor.fetchall()

    isbnArray = []

    for row in rows:

        isbn = row[0]

        isbnArray.append(isbn)

    return isbnArray





if __name__ == "__main__":

    # cleanUpFireBase()
    isbnArray = readFile()

    db = connectToBookUp()

    addToDatabase(isbnArray, db)

    db.close()







