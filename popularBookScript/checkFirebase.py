# Check for legit books in firebase
# Luke Oglesbee

import urllib2
from sys import argv

if len(argv) == 3: 
  in_file_name = argv[1]
  out_file_name = argv[2]
else:
  print "Invalid args"
  exit(0)

with open(in_file_name, 'r') as fin:
  with open(out_file_name, 'w') as fout:
    for line in fin:
      line = line.strip()
      try:
        res = urllib2.urlopen("http://localhost:8888/Bookup/Web_App/API/index.php/getBookFromFirebase/%s" % str(line))
      except:
        print "bad one"
        exit(0)
