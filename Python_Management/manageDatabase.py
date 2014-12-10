import MySQLdb
import sys

import databaseInteraction as dbI
import databaseConnection as dbC

import cronJob as cJ

def main(argv):

    if len(argv) != 1:
        print "Pass \"0\" to manually add isbn to database"
        print "Pass \"1\" to read Files"
        print "Pass \"2\" to Try API Calls"
        sys.exit(1)

    if argv[0] == '0':

        isbn = raw_input('Insert Isbn > ')

        if len(isbn) != 13:
            print "isbn not proper length"
            main(argv)
            sys.exit(1)

        isbnArray = []

        isbnArray.append(isbn)

        db = dbC.connect()

        dbI.addToDatabase(isbnArray, db)

    elif argv[0] == '1':

        cJ.main(argv)

    elif argv[0] == '2':

        cJ.main(argv)

    else:
        print "Pass \"0\" to manually add isbn to database"
        print "Pass \"1\" to read Files"
        print "Pass \"2\" to Try API Calls"
        sys.exit(1)

if __name__ == "__main__":

    main(sys.argv[1:])