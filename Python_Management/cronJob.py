__author__ = 'DRizzuto'

import commandLine as cL
import databaseInteraction as dbI
import databaseConnection as dbC
import fileReader as fR

import sys
import getopt

def readIsbnFiles():

    files = cL.getFilesFromDirectory()

    if not files:
        sys.exit()

    db = dbC.connect()

    for file in files:
        print file

        source = '../Isbn_Files/Not_Read/' + str(file)

        isbnArray = fR.readGivenFile(source)

        for isbn in isbnArray:
            print isbn

            dbI.addToDatabase(isbnArray, db)

            dbI.updateCronJobLog(file, db)

        cL.moveNot_ReadToRead(source)

    db.close()


def tryBadIsbns():

    db = dbC.connect()

    isbnArray = dbI.collateByUsageLimit(db)

    dbI.addToDatabase(isbnArray, db)

def main(argv):

    if len(argv) != 1:
        print "Pass \"1\" to read Files"
        print "Pass \"2\" to Try API Calls"
        sys.exit(1)
    else:
        if argv[0] == '1':
            readIsbnFiles()
            # print "read files"
        elif argv[0] == '2':
            tryBadIsbns()
            # print "API calls"
        else:
            print "Pass \"1\" to read Files"
            print "Pass \"2\" to Try API Calls"
            sys.exit(1)


if __name__ == "__main__":

    main(sys.argv[1:])