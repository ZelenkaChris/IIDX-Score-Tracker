import urllib2
import json
import codecs
import datetime
import re
import MySQLdb

regex = re.compile('.*\'.*')
print datetime.date.today()
dic = dict()
first = True
hasEntry = False

f1 = codecs.open('SongList_import.sql', 'w', 'utf-8');
f1.write("INSERT ignore INTO `SongList` VALUES")
f2 = codecs.open('SongData_import.sql', 'w', 'utf-8');
f2.write("INSERT INTO `SongData` VALUES")
d = urllib2.urlopen("http://json.iidx.me/chrisz/sp/search/?l=0u&d=E&r=F").read()
mydata = json.loads(d)
for x in mydata['musicdata']:
	id = x['data']['id']
	title = x['data']['title']
	if(not first and dic.has_key(id)):
		hasEntry = True
	dic[id] = title
	if(regex.match(title)):
		title = title.replace("'", "''")
		
	if not first:
		if not hasEntry:
			f1.write( ",\n('" + id + "', '" + 
								title + "')" )
		
		f2.write( ",\n('" + x['data']['id'] + "', '" + 
							x['data']['diff'] + "', '"  + 
							str(x['data']['level']) + "', '"  + 
							str(x['data']['notes']) + "', '" + 
							str(x['score']) + "', '" + 
							str(x['miss']) + "', '" + 
							str(x['clear']) + "', '" +
							str(datetime.date.today()) + "')" )
	else:
		f1.write( "\n('" + x['data']['id'] + "', '" + 
							title + "')" )
		
		f2.write( "\n('" + x['data']['id'] + "', '" + 
							x['data']['diff'] + "', '"  + 
							str(x['data']['level']) + "', '"  + 
							str(x['data']['notes']) + "', '" + 
							str(x['score']) + "', '" + 
							str(x['miss']) + "', '" + 
							str(x['clear']) + "', '" +
							str(datetime.date.today()) + "')" )
	first = False
	hasEntry = False
f1.write(';')
f2.write(';')

"""
INSERT INTO 'SongList' VALUES
	ID
	TITLE
"""

"""
INSERT INTO 'SongData' VALUE
	ID
	LEVEL
	SCORE
	MISS
	CLEAR
	DATE
"""

"""
(ID, TITLE),
(ID, DIFFICULTY, LEVEL, SCORE, MISS, CLEAR, DATE),
"""