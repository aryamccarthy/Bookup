import sys
import json
from firebase import firebase
fbURL = 'https://bookup-v2.firebaseio.com/'

def getBookJson(isbn):
    fbConnection = firebase.FirebaseApplication(fbURL, None)
    result = fbConnection.get('/' + isbn, None)
    print result

if __name__ == "__main__":
    getBookJson(sys.argv[1])
