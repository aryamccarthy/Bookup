from firebase import firebase
import sys
import requests
import MySQLdb
import json
import recursiveJsonSearch as rJS
import time

def readFile():

    with open("Isbn_Txt_Files/list_of_50_isbn.txt") as file:
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

    file = open('book_object_list.txt', "w+")

    cursor = db.cursor()

    for isbn in isbnArray:

        time.sleep(2)

        print isbn

        googleQuery = 'https://www.googleapis.com/books/v1/volumes?q=isbn:' + str(isbn)

        googleRequest = requests.get(googleQuery)

        string = googleRequest.content
        file.write(string)
        file.write("\n")

        googleRequest = json.loads(googleRequest.content)

        if "error" in googleRequest:

            try:

                cursor.execute("INSERT INTO BookList_Bad VALUES (%s, %s)", (isbn, "API Call returned Error"))

                print "\t API CALL ERROR"

                db.commit()

            except MySQLdb.Error, e:

                print "\t API CALL ERROR - Not in DB"

        else:

            bookObject = rJS.find_key(googleRequest, 'title')
            title1 = bookObject[0]
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

                    print "\t Added to good"


                except MySQLdb.Error, e:

                    try:

                        cursor.execute("INSERT INTO BookList_Bad VALUES (%s, %s)", (isbn, e.args[1]))

                        print "\t added to bad"

                        print e.args[1]


                        db.commit()

                    except MySQLdb.Error, e:

                        print "\t tried bad"

                except:

                    cursor.execute("INSERT INTO BookList_Bad VALUES (%s, %s)", (isbn, "God Only Knows Man"))

                    print "\t God Only Knows Man"

                    db.commit()


            else:

                try:

                    cursor.execute("INSERT INTO BookList_Bad VALUES (%s, %s)", (isbn, "Not all API info available"))

                    db.commit()

                    print "\t not all info available"


                except MySQLdb.Error, e:

                    print "\t tried no info, fail"

                    print "\t" + e.args[1]


            del bookObject [:]

    file.close()

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







