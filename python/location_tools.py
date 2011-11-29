#!/usr/bin/env python
# Script to retrieve places from the google places API, we will use these for "venues"
# Places API key is AIzaSyCwQN5dwVIEPE0-67GP-xfX6Dccpqmagfo

import urllib;
import string;

class locationTools:
	# location tools will provide location data to our PHP scripts to serve the front end
	def __init__(self): 
		# initialize some vars, set API key and URL template etc
		self.api_key = "AIzaSyCwQN5dwVIEPE0-67GP-xfX6Dccpqmagfo";
		self.url_template = "https://maps.googleapis.com/maps/api/place/search/xml?location=%s&radius=%s&sensor=%s&keyword=%s&name=%s&types=%s&key=" + self.api_key;			
		
	def getNearby(self, location, radius=1000, sensor="false", keyword="", name="", qtype=""):
		# get nearby results for a location
		# first build the url
		url = self.url_template % (location, radius, sensor, keyword, name, qtype);		
		# now we have a url, let's use urllib to get the results from google
		page = urllib.urlopen(url);
		xml_results = page.read(); # xml_results now contains our raw location search data
		page.close(); # close our URL handle
		self.parseLocationData(xml_results);

	def parseLocationData(self):
		pass;		


def main ():
	loc = locationTools();
	loc.getNearby('-33.8670522,151.1957362');

main();











