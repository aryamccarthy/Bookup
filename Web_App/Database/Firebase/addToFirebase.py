from firebase import firebase
import requests

fbURL = 'https://blistering-torch-3821.firebaseio.com/'
booksURL = 'https://www.googleapis.com/books/v1/volumes'

def readFile():

    filePath = '';

    isbnArray = []

    return isbnArray

def parseQuery(query):
    if " " in query:
        separator = "%20"
        queryArr = query.split(' ')
        return separator.join(queryArr)
    else:
        return query

def addByIsbn(isbnArray):
    for isbn in isbnArray:

        # googleQuery = 'https://www.googleapis.com/books/v1/volumes?q=isbn:' + str(isbn)

        print googleQuery;

        googleRequest = requests.get(googleQuery);

        #print googleRequest.content

        firebaseConn = firebase.FirebaseApplication(fbURL, None)

        firebaseResult = firebaseConn.post(str(isbn), googleRequest.content)

def addByTitle(title):
   normalizedQuery = parseQuery(title)
   queryTerm = "?q=" + normalizedQuery
   googleQuery = booksURL + queryTerm
   googleRequest = requests.get(googleQuery)
   fbConnection = firebase.FirebaseApplication(fbURL, None)
   fbConnection.post(str(title), googleRequest.content)

if __name__ == "__main__":
    addByTitle("Fahrenheit 451")
