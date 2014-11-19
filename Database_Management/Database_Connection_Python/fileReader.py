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

    destination = "/../Isbn_Txt_Files/Read/"

    shutil.move(destination, source)