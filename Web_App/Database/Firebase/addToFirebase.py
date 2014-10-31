from firebase import firebase
import requests
import MySQLdb

def readFile():

    openFile = ("Isbn_Dumps/isbn5000.txt", "rw+")

    with open("Isbn_Dumps/isbn5000.txt") as file:
      isbnArray = file.read().splitlines()

    return isbnArray

def addToFirebaseAndMysqlDatabase(isbnArray):

    fbURL = 'https://blistering-torch-3821.firebaseio.com/'

    # db = MySQLdb.connect(host="localhost", user="root", passwd="root", db="BookUp")

    # cursor = db.cursor()

    for isbn in isbnArray:

      try:

        googleQuery = 'https://www.googleapis.com/books/v1/volumes?q=isbn:' + str(isbn)

        googleRequest = requests.get(googleQuery);

        print googleRequest.content

        # firebaseConn = firebase.FirebaseApplication(fbURL, None)

        # firebaseResult = firebaseConn.post(str(isbn), googleRequest.content)

        # mysqlQuery = """INSERT INTO BookList VALUE (%s), (isbn)"""

        # cursor.execute(mysqlQuery)

        # db.commit()

        # print isbn;

      except:

        print isbn + " not added"

if __name__ == "__main__":

    # isbnArray = readFile()

    print isbnArray

    isbnArray = ['9780006479901']


    addToFirebaseAndMysqlDatabase(isbnArray)






