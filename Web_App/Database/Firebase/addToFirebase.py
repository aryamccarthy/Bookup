from firebase import firebase
import requests

def readFile():

    filePath = '';

    isbnArray = []

    return isbnArray

def addToFirebase(isbnArray):


    fbURL = 'https://blistering-torch-3821.firebaseio.com/'

    for isbn in isbnArray:

        googleQuery = 'https://www.googleapis.com/books/v1/volumes?q=isbn:' + str(isbn)

        print googleQuery;

        googleRequest = requests.get(googleQuery);

        #print googleRequest.content

        firebaseConn = firebase.FirebaseApplication(fbURL, None)

        firebaseResult = firebaseConn.post(str(isbn), googleRequest.content)

if __name__ == "__main__":

    # isbnArray = readFile()

    # isbnArray = ['0553571338']

    # addToFirebase(isbnArray)








