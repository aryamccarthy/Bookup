# Title: addToFirebase.py
# Summary: python script for querying google books api
# Owner: Danny Rizzuto
# Version: 1.1
# Last Modified: 10/27/2014
# Last Modified By: Zack Fout
# Notes: added functionality to query google books api by title

from firebase import firebase
import requests

fbURL = 'https://blistering-torch-3821.firebaseio.com/'
booksURL = 'https://www.googleapis.com/books/v1/volumes'

def readFile():

    filePath = '';

    isbnArray = []

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

if __name__ == "__main__":
    #addByTitle("Fahrenheit 451")






