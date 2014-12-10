__author__ = 'DRizzuto'

import os
import sys
import shutil

def getFilesFromDirectory():

    files = os.listdir('../Isbn_Files/Not_Read')

    for file in files:
        if file == ".DS_Store":
            files.remove(file)

    return files


def moveNot_ReadToRead(source):

    destination = "../Isbn_Files/Read/"

    shutil.move(source, destination)