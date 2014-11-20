__author__ = 'DRizzuto'

import shutil

def readGivenFile(source):

    with open(source) as file:
      isbnArray = file.read().splitlines()

    moveNot_ReadToRead(source)

    return isbnArray


def checkNot_ReadFolder():
    pass


def moveNot_ReadToRead(source):

    destination = "../Isbn_Txt_Files/Read/"

<<<<<<< HEAD
    shutil.move(source, destination)
=======
    shutil.move(source, destination)
>>>>>>> 7b51988e03b1c48e653755864eefc8de747085ec
