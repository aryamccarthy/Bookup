# Title: addToFirebase.py
# Summary: python script for querying google books api
# Owner: Danny Rizzuto
# Version: 1.1
# Last Modified: 10/27/2014
# Last Modified By: Zack Fout
# Notes: added functionality to query google books api by title

from firebase import firebase
import requests
import MySQLdb

fbURL = 'https://blistering-torch-3821.firebaseio.com/'
booksURL = 'https://www.googleapis.com/books/v1/volumes'

def readFile():

    openFile = ("Isbn_Dumps/isbn5000.txt", "rw+")

    with open("Isbn_Dumps/isbn5000.txt") as file:
      isbnArray = file.read().splitlines()

    return isbnArray

def parseQuery(query): # replaces whitespaces with html encoding
    if " " in query:
        separator = "%20"
        queryArr = query.split(' ')
        return separator.join(queryArr)
    else:
        return query

def addByIsbn(isbnArray): # queries by isbn number and inserts into firebase
    for isbn in isbnArray:
        googleQuery = googleURL + str(isbn)

def addToFirebaseAndMysqlDatabase(isbnArray):

    fbURL = 'https://blistering-torch-3821.firebaseio.com/'

    # db = MySQLdb.connect(host="localhost", user="root", passwd="root", db="BookUp")

    # cursor = db.cursor()

    for isbn in isbnArray:

      try:

        googleQuery = 'https://www.googleapis.com/books/v1/volumes?q=isbn:' + str(isbn)

        googleRequest = requests.get(googleQuery);
        firebaseConn = firebase.FirebaseApplication(fbURL, None)
        firebaseConn.post(str(isbn), googleRequest.content)

def addByTitle(title): # queries by title and inserts into firebase
   normalizedQuery = parseQuery(title)
   queryTerm = "?q=" + normalizedQuery
   googleQuery = booksURL + queryTerm
   googleRequest = requests.get(googleQuery)
   fbConnection = firebase.FirebaseApplication(fbURL, None)
   fbConnection.post(str(title), googleRequest.content)

        # mysqlQuery = """INSERT INTO BookList VALUE (%s), (isbn)"""

        # cursor.execute(mysqlQuery)

        # db.commit()

        print isbn;

      except:

        print isbn + " not added"

if __name__ == "__main__":
<<<<<<< HEAD
    #addByTitle("Fahrenheit 451")
    isbnArray = readFile()

    # print isbnArray

    # isbnArray = ['9780141182957']


    addToFirebaseAndMysqlDatabase(isbnArray)

