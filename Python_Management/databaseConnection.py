__author__ = 'DRizzuto'
import MySQLdb
import sys

def connect():

    try:

        # db = MySQLdb.connect(unix_socket = "/var/run/mysqld/mysqld.sock",
        #                     host = "54.69.55.132",
        #                     user = "zfout",
        #                     passwd = "Forkusmaximus1",
        #                     db = "BookUpv3",
        #                     port = 3306)

        db = MySQLdb.connect(unix_socket ="/Applications/MAMP/tmp/mysql/mysql.sock",
                             host="54.69.55.132", user="zfout",
                             passwd="Forkusmaximus1",
                             db="BookUpv4")

        return db

    except MySQLdb.Error, e:

        print "Could not connect to database, got error:\n %s" % (e.args[1])
        print "Please check information in connectToDB.connect()"
        sys.exit(1)


if __name__ == "__main__":
    print "Meant to be accessed from manageDatabase.py"