# SCFExtractor Python Toolkit

## Installation

This module has been tested with Python 2.7.

### SCFExtractor installation on Ubuntu:

    sudo apt-get install python-mysqldb mysql-server python-matplotlib

### SCFViewer installation on Ubuntu:
            
    sudo apt-get install nginx php5-fpm 

    cd /etc/nginx/sites-enabled
    sudo ln -s <path-to-scf-extractor>/scf-viewer/nginx.conf .
    sudo service nginx restart

Add the following line at the bottom of hosts file (/etc/hosts):

    127.0.0.1 zanette.extractor

Edit scf-viewer/conf/conf.php file:
* Change $conf['database'] to your default databse connection;
* and $conf['databases'] to your databases (the array index is the name of your database)

Access the URL http://zanette.extractor 

## Getting started
  There are four main commands for scf extraction:
  
  * SCF extraction
        
        python main.py extract conf/<extract>.yml

  * Run Statistics
        
        python main.py statistics conf/<statistics>.yml

  * SCF extraction evaluation
        
        python main.py evaluate conf/<evaluate>.yml

  * Semaintic SCF process
        
        python main.py process conf/<process>.yml

  
## Special thanks
Leonardo Zilio and Carolina Scarton

## Copyright

Copyright (c) 2013 SCFExtractor Ltd. See LICENSE for details.
