from firebase import firebase
import requests
import MySQLdb
import json

def readFile():

    with open("Isbn_Txt_Files/list_of_1000_isbn.txt") as file:
      isbnArray = file.read().splitlines()

    return isbnArray

def addToFirebase(isbnArray):

    fbURL = 'https://blistering-torch-3821.firebaseio.com/'

    # db = MySQLdb.connect(host="localhost", user="root", passwd="root", db="BookUp")

    # cursor = db.cursor()

    for isbn in isbnArray:


        googleQuery = 'https://www.googleapis.com/books/v1/volumes?q=isbn:' + str(isbn)

        googleRequest = requests.get(googleQuery);

        # print googleRequest.content

        jsontest = json.loads(googleRequest.content)

        #print jsontest

        if "error" not in jsontest:

            if jsontest['totalItems'] is not 0:

                # print "json was not an error"

                firebaseConn = firebase.FirebaseApplication(fbURL, None)

                firebaseResult = firebaseConn.post(str(isbn), googleRequest.content)

                with open("Isbn_Txt_Files/list_of_Isbn_in_FB.txt", "a") as myfile:
                    textString = "{}\n".format(isbn)
                    myfile.write(textString)
            else:

                print isbn + " not added"
                print "There were no Items"

        else:
    
            print isbn + " not added"
            print "json was an error"

def cleanUpFireBase():

    fbURL = 'https://bookup-v2.firebaseio.com/'

    firebaseConn = firebase.FirebaseApplication(fbURL, None)

    result = firebaseConn.get('/Name', None)

if __name__ == "__main__":

    # cleanUpFireBase()

    isbnArray = readFile()

    # isbnArray = ['9781904233657']
    addToFirebase(isbnArray)







