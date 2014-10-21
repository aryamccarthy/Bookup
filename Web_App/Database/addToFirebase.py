from firebase import firebase

def readFile():

    filePath = '';

    isbnArray = []

    return isbnArray

def addToFirebase(isbnArray):

    firebaseURL = 'https://blistering-torch-3821.firebaseio.com/'

    firebaseConn = firebase.FirebaseApplication(firebaseURL, None)

    for isbn in isbnArray:

        url = '/' + str(isnb)

        //google

        googleRequestDecode = ''

        firebaseResult = firebaseConn.post(url, googleRequestDecode)

if __name__ == "__main__":

    isbnArray = readFile()

    addToFirebase(isbnArray)








