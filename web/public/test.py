import mysql.connector

mydb = mysql.connector.connect(
    host="localhost",
    user="dev",
    passwd="dev",
    db="link_list",
    port="8989"
)

print (mydb)