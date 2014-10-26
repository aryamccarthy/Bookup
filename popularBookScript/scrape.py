# scrape.py

# Gathers popular books from goodreads.com

# Usage
# $ python scrape.py <num pages> <ouput file>

import urllib2
from bs4 import BeautifulSoup
from sys import argv

# Initialize
if len(argv) != 3:
  print "Improper args. Use this:"
  print "$ python scrape.py <num pages> <output file>"
  exit(0)
else:
  numPages = int(argv[1])
  outputFile = argv[2]
soup = BeautifulSoup

# Get books from goodreads
with open(outputFile, 'w') as f:
  for page in range(1,numPages+1):
    print page
    response = urllib2.urlopen('https://www.goodreads.com/list/show/1.Best_Books_Ever?page=%s' % page)
    soup = BeautifulSoup(response)
    html = soup.prettify()
    titles = soup.select('a.bookTitle > span')
    for title in titles:
      f.write(title.contents[0].encode('utf8')+"\n")
