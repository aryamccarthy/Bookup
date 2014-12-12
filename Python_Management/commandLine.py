__author__ = 'DRizzuto'

import os
import datetime
import sys
import shutil

def takeDump():

    databaseName = "BookUpv4"

    today = datetime.datetime.now()

    year = today.year

    month = today.month

    day = today.day

    dumpDate = str(year) + "_" + str(month) + "_" + str(day) + "-" + databaseName + ".sql"

    print dumpDate

    command = "mysqldump -u zfout -h 54.69.55.132 -pForkusmaximus1 " +databaseName + " > " + dumpDate

    print command

    os.system(command)

    destination = "../Dump_Files/"

    shutil.move(dumpDate, destination)


def getFilesFromDirectory():

    files = os.listdir('../Isbn_Files/Not_Read')

    for file in files:
        if file == ".DS_Store":
            files.remove(file)

    return files


def moveNot_ReadToRead(source):

    destination = "../Isbn_Files/Read/"

    shutil.move(source, destination)



if __name__ == "__main__":
    print "Meant to be accessed from manageDatabase.py"