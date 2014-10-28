from firebase import firebase
import requests

def readFile():

    openFile = ("5000isbn.txt", "rw+")

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

    isbnArray = ['9780141182957']


    addToFirebase(isbnArray)






