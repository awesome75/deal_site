#!/usr/bin/env python
# Script to retrieve places from the google places API, we will use these for "venues"
# Places API key is AIzaSyCwQN5dwVIEPE0-67GP-xfX6Dccpqmagfo

import urllib;
import string;
import json;

class locationTools:
	# location tools will provide location data to our PHP scripts to serve the front end
	def __init__(self): 
		# initialize some vars, set API key and URL template etc
		self.api_key = "AIzaSyCwQN5dwVIEPE0-67GP-xfX6Dccpqmagfo";
		self.url_template = "https://maps.googleapis.com/maps/api/place/search/json?location=%s&radius=%s&sensor=%s&keyword=%s&name=%s&types=%s&key=" + self.api_key;			
		
	def getNearby(self, location, radius=500, sensor="false", keyword="", name="", qtype=""):
		# get nearby results for a location
		# first build the url
		url = self.url_template % (location, radius, sensor, keyword, name, qtype);		
		# now we have a url, let's use urllib to get the results from google
		page = urllib.urlopen(url);
		results = page.read(); # results now contains our raw location search data
		page.close(); # close our URL handle
		return results;

	def parseLocationData(self, json_results):
		# parse the returned data					
		locs = json.loads(json_results);
		i = 0;
		for loc in locs['results']:
			print loc;
			break;

	def geoCode(self, address, sensor="false"):
		# take an address and geocode it for the database
		url_template = "https://maps.googleapis.com/maps/api/geocode/json?address=%s&sensor=%s";
		url = url_template % (address, sensor);
		# now we get read the page into memory
		page = urllib.urlopen(url);
		results = page.read();
		page.close(); # close our page handle
		data = json.loads(results);
		for obj in data['results']:
			return "%s,%s" % (obj['geometry']['location']['lat'], obj['geometry']['location']['lng']);



def main ():
	loc = locationTools();




main();











