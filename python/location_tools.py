#!/usr/bin/env python
# Script to retrieve places from the google places API, we will use these for "venues"
# Places API key is AIzaSyCwQN5dwVIEPE0-67GP-xfX6Dccpqmagfo

import urllib;
import string;
import json;
from sys import argv;

class locationTools:
	# location tools will provide location data to our PHP scripts to serve the front end
    def __init__(self): 
		# initialize some vars, set API key and URL template etc
		self.api_key = "AIzaSyCwQN5dwVIEPE0-67GP-xfX6Dccpqmagfo";
		self.url_template = "https://maps.googleapis.com/maps/api/place/search/json?location=%s&radius=%s&sensor=%s&keyword=%s&name=%s&types=%s&key=" + self.api_key;			
		
    def getLocation(self, ip_address):
        api_key = "ed2da6f194f9306f2dd3c1a8965edfc4bf5afa1c50d70ddcf1677ffb0d19cd97";
        url_template = "http://api.ipinfodb.com/v3/ip-city/?key=%s&ip=%s";
        url = url_template % (api_key, ip_address);
        # make the request, get the data
        page = urllib.urlopen(url);
        result = page.read();
        page.close(); # close our page handle
        return result; # no need to parse results, they are returned in an easy to explode() format


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
            
    def reverseGeoCode(self, latlng, sensor="false"):
        # take a coordinate and get the closest address possible for it
        url_template = "https://maps.googleapis.com/maps/api/geocode/json?latlng=%s&sensor=%s";
        url = url_template % (latlng, sensor);
        # get the results in memory
        page = urllib.urlopen(url);
        results = page.read();
        page.close(); # close page handle
        data = json.loads(results);
        for obj in data['results']:
            # parse the json results and get the address out of there
            print obj['formatted_address'];
            exit();

def main ():
    loc = locationTools(); # get our location class
    # now we can parse the command line args to return data to our PHP scripts
    try:
        com = argv[1];
        opts = argv[2]; # opts will come seperated by commas if there are more than one
    except:
        print "Please specify the desired location command and options";
    # now we can go through the various options
    if (argv[1] == "getloc"):
        print loc.getLocation(opts);
        
    elif (argv[1] == "getnearby"):
        print loc.getNearby(opts);
        
    elif (argv[1] == "geocode"):
        print loc.geoCode(opts);
        
    elif (argv[1] == "revgeocode"):
        loc.reverseGeoCode(opts);
        

if __name__ == '__main__':
    main();











