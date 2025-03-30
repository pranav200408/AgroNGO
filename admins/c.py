import socket
import threading
import socket
#import urllib2
from urllib.request import urlopen
#import MySQLdb as mdb
import sys
#import pulse

    
def push1(a,b,c,d,e):            
            con = mdb.connect('localhost', \
                              'root', \
                              'root', \
                              'farm' );
            cur = con.cursor()
            cur.execute("TRUNCATE TABLE `sens1`")
            cur.execute("""INSERT INTO sens1(a,b,c,d,e) \
                       VALUES(%s,%s,%s,%s,%s)""", (a,b,c,d,e))            
            con.commit()

def thk(a,b,c,d,e):
    myAPI = 'KZVJ5X269I764BPJ'
    baseURL = 'https://api.thingspeak.com/update?api_key=%s' % myAPI 
    #conn = urllib2.urlopen(baseURL + '&field1=%s&field2=%s&field3=%s&field4=%s' % (temp,hp,bp,vibration))
    conn =urlopen(baseURL + '&field1=%s&field2=%s&field3=%s&field4=%s&field5=%s' % (a,b,c,d,e))
    print (conn.read())   
    conn.close()
def n2():
    host = '192.168.43.53'
    port = 2000
    s = socket.socket()    
    s.connect((host ,port))    
    while True:
        var = s.recv(1024).decode("ascii")       
        data = s.recv(1024).decode("ascii")
        data= var+data
        print (data)
        a,b,c,d,e=data.split(',')
        c=int(c)
        c=float(c/1000)
        print(a,b,c,d,e)
        thk(a,b,c,d,e)


w2 = threading.Thread(name='n2', target=n2)


w2.start()

