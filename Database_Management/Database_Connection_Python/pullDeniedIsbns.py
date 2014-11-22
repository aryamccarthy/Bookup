import sys
import MySQLdb

def collateByUsageLimit(db):
    cursor = db.cursor()
    cursor.execute("SELECT isbn_num FROM BookList_Bad WHERE reason = %s", 
        ("API Call returned Error"))

    isbns = [isbn for isbn in cursor.fetchall()]

    return isbns
        
if __name__ == "__main__":
    print "Meant to be accessed from manageDatabase.py"

