
import pymysql as mdb
import serial
import time
serialPort = serial.Serial("COM2", 9600, timeout=0.5)
h=''
while True:
    time.sleep(2)
    mydb = mdb.connect(
          host="127.0.0.1",
          user="root",
          passwd="",
          database="tms"
        )

    mycursor = mydb.cursor()

    sql = "SELECT comm FROM churn"


    mycursor.execute(sql)
    fine=0
    myresult = mycursor.fetchall()
    mydb.close()
    for x in myresult:            
        notice=str(x[0])
        
    
    if h!=notice:
        h=notice
        print(notice)
        notice='#how are you*'
        serialPort.write(notice.encode())
##        var=(serialPort.readline().decode())
##        if var!='':
##            print(var)
        
        
        

          
