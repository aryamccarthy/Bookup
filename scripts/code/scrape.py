# scrape.py

# Gathers popular books from goodreads.com

# Usage
# $ python scrape.py <num pages> <ouput file>

import urllib2
import re
from bs4 import BeautifulSoup
from sys import argv

# Initialize
if len(argv) != 4:
  print "Improper args. Use this:"
  print "$ python scrape.py <num pages> <output file>"
  exit(0)
else:
  minPage = int(argv[1])
  maxPage = int(argv[2])
  outputFile = argv[3]
soup = BeautifulSoup

# Get books from goodreads
# with open(outputFile, 'w') as f:
#   for page in range(1,numPages+1):
#     print page
#     response = urllib2.urlopen('https://www.goodreads.com/list/show/1.Best_Books_Ever?page=%s' % page)
#     soup = BeautifulSoup(response)
#     html = soup.prettify()
#     titles = soup.select('a.bookTitle > span')
#     authors = soup.select('a.authorName > span')
#     if len(titles) != len(authors):
#       print "Number of titles found not the same as number authors found... Exiting."
#       exit(0)
#     for i in range(len(titles)):
#       f.write(titles[i].contents[0].encode('utf8')+'\n')
#       f.write(authors[i].contents[0].encode('utf8')+'\n')

isbns = []
with open(outputFile, 'w') as f:
  for page in range(minPage,maxPage+1):
    print "page: "+str(page)
    response = urllib2.urlopen('https://www.goodreads.com/list/show/1.Best_Books_Ever?page=%s' % page)
    soup = BeautifulSoup(response)
    html = soup.prettify()
    titles = soup.select('a.bookTitle')
    for title in titles:
      # f.write(title.span.contents[0].encode('utf8')+'\n')
      response = urllib2.urlopen('https://www.goodreads.com'+title['href'])
      soup = BeautifulSoup(response)
      isbn = soup.select('div.infoBoxRowItem > span.greyText > span')
      if len(isbn) == 0:
        print "no isbn..."
        continue
      isbn = re.sub(r'\D','',str(isbn[0]))
      isbns.append(isbn)
      f.write(isbn+'\n')

