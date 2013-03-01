#!/usr/bin/env python
################################################################################
# DealRank is the secret sauce for the deal site. It will include a number 
# of indicators for a deal and it will use [forumala name] to come up with a 
# ranking for the deal. This script will expect a deal id to be fed to it, and
# should be ran every time an indicator changes for a deal in the DB
################################################################################
# TODO:
  # probably write it
################################################################################

from math import sqrt;
import MySQLdb as mysql;

class DealRank:
  def __init__(self, deal_id, con):
    # the constructor needs to grab the dataz
    self.deal_id = deal_id;
    self.con = con;
    # go ahead and make a query mmk
    sql = " \
      SELECT \
      `deal_views`, `deal_thumbs_up`, `deal_thumbs_down`, \
      `deal_verified`, `thanks_count` \
      FROM `deals` \
      WHERE `deal_id` = %d \
    " % self.deal_id;
    cur = self.con.cursor(mysql.cursors.DictCursor);
    cur.execute(sql);
    res = cur.fetchall();
    for row in res:
      # the row object can be used with named indexes, eg row['deal_views']
      self.findRank(row);
      # this concludes our constructor method
  
  def findRank(self, row):
    # do the heavy lifting
    # first, for convenience, assign the variables
    verified = True if row['deal_verified'] == 1 else False; # python has odd ternary
    thanks = int(row['thanks_count']);
    thumbs = int(row['deal_thumbs_up'] - row['deal_thumbs_down']); # thumbs is the net up/down
    views = int(row['deal_views']);
    # now change the verified so a deal that is not verified has only half rank
    verified = 1 if verified == True else 0.5;
    # apply a very simple ranking algo
    pop = ((thanks + thumbs + views) * verified) / 2; 
    # now we will update the rank in the DB
    self.updateRank(pop);

  def updateRank(self, pop):
    # update the deal with its new rank
    # we will probably want a query to work with XD
    sql = " \
      UPDATE `deals` \
      SET `algo_ranking` = %d \
      WHERE `deal_id` = %d \
    " % (pop, self.deal_id);
    cur = self.con.cursor();
    cur.execute(sql);


class DB:
  def __init__(self, host, user, passwd, db):
    self.con = mysql.connect(host=host, user=user, passwd=passwd, db=db);
  

con = DB('localhost', 'root', '8ac0n90', 'deal_site');
DealRank(1, con.con);