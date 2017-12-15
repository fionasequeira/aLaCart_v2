# aLaCart

## Getting Started

#### Installations
bitnami-mappstack-7.0.23-0-osx-x86_64-installer.dmg

#### Dependancies

- Apache - 2.4.18
- PHP 5.6.31
- PostgreSQL - 9.6
- Bitnami MAPP Stack 7.0.23-0
- phpPgAdmin 5.1

## Procedure

Download the mappstack image and Application at the Document Root 
$ cd <enter path to your Document Root>

Run the MAPP image file installed
Set Apache Web Server Port - 8888
Set SSL Port - 8445
Set Databse Server Port - 5434

Set PostgreSQL credentials
username- postgres
password- test

Open the Bitnami Application -> Manage Servers, the following servers must be running.
PostgreSQL Database
Apache Web Server

#### Cloning the Repository to this location-

- $ cd /Users/Fiona/Desktop/WebApp/Application/apache2/htdocs/
- $ git clone https://github.com/fionasequeira/aLaCart.git
OR
- copy all the contents app,includes and assets folders into htdocs


Open a browser and enter - http://localhost:8888/app/login.php



Delete Image confirmation.png
