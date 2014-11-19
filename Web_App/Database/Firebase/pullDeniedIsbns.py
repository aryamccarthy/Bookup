import sys
import MySQLdb

def connectToBookUp():
    try:
        db = MySQLdb.connect(
            unix_socket = "/var/run/mysqld/mysqld.sock", 
            host = "54.69.55.132", 
            user = "zfout", 
            passwd = "Forkusmaximus1", 
            db = "BookUpv3",
            port = 3306)
        print "Successfully connected to BookUp db"
        return db

    except MySQLdb.Error, e:
        print "Could not connect to database, got error:\n %s" % (e.args[1])
        sys.exit(1)

def collateByUsageLimit(db):
    cursor = db.cursor()
    cursor.execute("SELECT isbn_num FROM BookList_Bad WHERE reason = %s", 
        ("API Call returned Error"))

    isbns = [isbn for isbn in cursor.fetchall()]
    out = open('rejected_isbns.txt', 'w')

    for isbn in isbns:
        out.write("%s\n" % isbn)

    out.close()

def readRejectedIsbns(input_file):
    with open(input_file) as isbn_file:
        isbns = isbn_file.read().splitlines()
    isbn_file.close()

    for isbn in isbns:
        print isbn

    return isbns
        
if __name__ == "__main__":
    #db = connectToBookUp() || run these to get info from db
    #collateByUsageLimit(db)
    #readRejectedIsbns('rejected_isbns.txt') || run to read info from file
