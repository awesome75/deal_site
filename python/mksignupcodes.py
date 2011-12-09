#!/usr/bin/env python

# create signup codes for people on the deals site

from random import randint as rand;
import MySQLdb as mysql;

class code:
	# our code object to track the id and string for each code
	def __init__(self, code, code_id):
		self.code = code;
		self.code_id = code_id;


class generateCodes:
	# generate codes for the site, we will use a simple method to make the codes:
	# we will generate 3 random letters, then three random numbers
	# that will be our signup code that is inserted into the db. it is a simple little
	# method so hopefully no one figures out how to generate their own signup codes XD

	def __init__(self, host, user, passwd, db):
		# the class should be constructed with a (host, user, pass, db) so we can
		# get access to MySQL and put the codes into the DB for the site to use
		self.host = host;
		self.user = user;
		self.passwd = passwd;
		self.db = db;

	def generateCodes(self, first_run='no', amount='1000'):
		# create the codes with the described algorithm
		# first we need to determine if the script has ran before, i was going
		# to build a fancy DB query function for this but it is easier to just ask the user
		if (first_run == 'no'):
			# we will need to get our starting ID from the DB to keep the codes unique
			# for now we won't worry about that, we will gen 1000 codes for now in case 
			# we need to have a fairly large beta
			pass;
		else:
			# otherwise we will just assume we can start the id for the codes at 0
			# by default we will generate 1000 codes, but the number can be set at runtime
			self.codes = [];
			for i in range(0, int(amount)):
				# build the codes until we hit the limit
				# chr(97) - chr(122) for letters
				code = "";
				for c in range(3):
					code += str(chr(rand(97,122)));
				# now for some numbers
				for c in range(3):
					code += str(rand(0,9));
				self.codes.append(code);
				# now we have a nice array full of promo codes to put into the database

	def insertCodes(self):
		# the table that this outputs to should be named `signup_codes`
		# it should at least contain fields id(int6), code(varchar6), redeemed(int1), user_id(int12)
		sql = "INSERT INTO `signup_codes` (code) VALUES('%s')"; # this is our query template
		con = mysql.connect(host=self.host, user=self.user, passwd=self.passwd, db=self.db);
		for code in self.codes:
			# we will need to go through each code and add it to the database
			cur = con.cursor();
			cur.execute(sql % code);
		# now that we have added the entries to the database we can close the cursor
		con.close();

def main():
	codes = generateCodes('localhost', 'deal', 'passwd', 'deal_site');
	codes.generateCodes(first_run='yes');
	codes.insertCodes();

if __name__ == "__main__":
	main();










