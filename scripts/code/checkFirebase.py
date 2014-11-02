# Check for legit books in firebase
# Luke Oglesbee

import urllib2
import re
import json
from sys import argv
from bs4 import BeautifulSoup

__verbose = True

if len(argv) == 2: 
  in_file_name = argv[1]
else:
  print "Invalid args"
  exit(0)
good_file = "../data/onlyGoodIsbns.txt"
bad_file = "../data/onlyBadsbns.txt"
soup = BeautifulSoup

with open(in_file_name, 'r') as f_in:
  with open(good_file, 'w') as f_good, open(bad_file, 'w') as f_bad:
    for line in f_in:
      line = line.strip()
      if __verbose: print line
      try:
        res = urllib2.urlopen("http://localhost:8888/Bookup/Web_App/API/index.php/getBookFromFirebase/%s" % str(line))
      except:
        print "url didn't work..."
        exit(0)
      src = res.read()
      src = re.sub(r"\\n","",src)
      src = re.sub(r"\\","",src)
      src = src.strip('"')
      try:
        obj = json.loads(src)
      except:
        if __verbose: print "malformed json"
      if 'kind' in obj and obj['kind'] == "books#volumes" and 'totalItems' in obj and obj['totalItems'] != 0:
        if __verbose: print "  +++ good"
        f_good.write(line+"\n")
        if __verbose:
          json.dump(obj, f_good, indent=2)
          f_good.write('\n\n')
      else:
        if __verbose: print "  --- bad"
        f_bad.write(line+"\n")
        if __verbose:
          json.dump(obj, f_bad, indent=2)
          f_bad.write('\n\n')
