from firebase import firebase
import sys
import requests
import MySQLdb
import json

def readFile():

    with open("Isbn_Txt_Files/list_of_1000_isbn.txt") as file:
      isbnArray = file.read().splitlines()

    return isbnArray

def connectToBookUp():

    try:

        db = MySQLdb.connect(host="localhost", user="root", passwd="root", db="BookUp")

        return db

        # cursor = db.cursor()
        #
        # cursor.execute("INSERT INTO BookList VALUES('Testme')")
        #
        # db.commit()
        #
        # cursor.execute("SELECT * FROM BookList")
        #
        # rows= cursor.fetchall()
        #
        # for row in rows:
        #     print row,

        db.close()

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

    fbURL = 'https://bookup-v3.firebaseio.com/'

    cursor = db.cursor()

    for isbn in isbnArray:

        googleQuery = 'https://www.googleapis.com/books/v1/volumes?q=isbn:' + str(isbn)

        googleRequest = requests.get(googleQuery)

        # print googleRequest.content

        jsontest = json.loads(googleRequest.content)

        #print jsontest

        if "error" not in jsontest:

            if jsontest['totalItems'] is not 0:

                pass

                # print "json was not an error"

                # firebaseConn = firebase.FirebaseApplication(fbURL, None)
                #
                # firebaseResult = firebaseConn.post(str(isbn), googleRequest.content)
                #
                # with open("Isbn_Txt_Files/list_of_Isbn_in_FB.txt", "a") as myfile:
                #     textString = "{}\n".format(isbn)
                #     myfile.write(textString)
            else:

                cursor.execute("INSERT INTO BookList_Bad VALUES (%s, %s)", ("Bad API Call"))

                db.commit()

                print "\t" + isbn + " not added bc No Items"


        else:

            cursor.execute("INSERT INTO BookList_Bad VALUES (%s, %s)", ("Bad API Call"))

            db.commit()

            print "\t" + isbn + " not added bc Bad API Call"


        # try:
        #
        #     cursor.execute("INSERT INTO BookList VALUES (%s)", (isbn))
        #
        #     db.commit()
        #
        #     print isbn + " added to database"
        #
        # except MySQLdb.Error, e:
        #
        #     print "\t" + isbn + " not added to database"


def cleanBookList_Bad(db):

    cursor = db.cursor()

    cursor.execute("SELECT * FROM BookList_Bad")

    rows = cursor.fetchall()

    isbnArray = []

    mode = 1

    for row in rows:

        isbn = row[0]

        isbnArray.append(isbn)

        addToDatabase(isbnArray, db, mode)





if __name__ == "__main__":

    # cleanUpFireBase()
    # isbnArray = readFile()

    db = connectToBookUp()

    isbnArray = ['9781904233657']
    addToDatabase(isbnArray, db)

    db.close()







