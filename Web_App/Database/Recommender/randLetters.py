import random


def randomRate(n):
	lets = letters()
	nums = numbers(100)
	with open("init.sql",'w') as fout:
		fout.write("DELETE FROM Rating;\n")
		fout.write("INSERT INTO Rating VALUES")
		for i in range(n-1):
			let = random.randint(0,len(lets)-1)
			num = random.randint(0,len(nums)-1)
			rat = random.randint(-1,1)
			fout.write( '("%s", %i, NOW(), "%s"),\n' % (lets[let],rat,nums[num]))
		let = random.randint(0,len(lets)-1)
		num = random.randint(0,len(nums)-1)
		rat = random.randint(-1,1)
		fout.write( '("%s", %i, NOW(), "%s")\n' % (lets[let],rat,nums[num]))
		fout.write("ON DUPLICATE KEY\nUPDATE rating=VALUES(rating);")

def letters():
	letters = [chr(x) for x in range(65,65+26)]
	letterNames = []
	for i in letters:
		for j in range(3):
			letterNames.append('%s%s' % (i,letters[j]))
			# print '("%s%s", "%s"),' % (i,letters[j],"pass")
	return letterNames

def numbers(n):
	numberNames = []
	for i in range(1,n):
		numberNames.append('%03i' % i)
		# print '("%03i"),' % i
	return numberNames

randomRate(5000)