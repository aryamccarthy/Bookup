import requests
from random import choice
from sys import stdout

def randomRate(n):
  email = []
  with open('email.txt','r') as fin:
    for line in fin:
      email.append(line.strip())
  isbn = []
  with open('isbn.txt','r') as fin:
    for line in fin:
      isbn.append(line.strip())
  rating = [-1,1]

  for i in range(n):
    payload = {'email':choice(email),'isbn':choice(isbn[:15]),'rating':choice(rating)}
    r = requests.post('http://localhost:8888/Bookup/Web_App/API/index.php/submitBookFeedback', data=payload)
    stdout.write('.')
    stdout.flush()
    if i%50 == 0:
      stdout.write('\n')
  print "\ndone"

randomRate(100)