# Yealink_Phonebook
A MariaDB based phonebook, which creates an xml file for Yealink phones.

Until now, it should be considered a beta version. So feel free to report bugs.

It is also recommended to protect the website with a password.

# How to install it?

Just set up a MariaDB and Apache2 Server and install phpMyAdmin for convenience.
You should also consider installing all necessary and recommended modules for Apache.

On slow servers or huge DBs the generation of the XML file can be quite slow. 
Therefore, you should consider editing the php.ini and set the execution time to a higher number or to infinite, 0.
