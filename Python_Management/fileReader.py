__author__ = 'DRizzuto'

import shutil

def readGivenFile(source):

    with open(source) as file:
      isbnArray = file.read().splitlines()

    return isbnArray