# rigmanager_MVC
rigmanager.dev web application

*Developed from 15.02.2020 to 31.05.2020.*

This repository represents my early-stage application development using PHP MVC approach.
Future development was moved to Laravel framework. Find out more [here](https://github.com/Oleksii-T/rigmanager).

All architecture is written from scratch.
You can find a few screenshots of running web app in **rigmanager_photoes_DEMO.zip**.

Web app was made up with:
- Visual Studio Code 1.45.1
- XAMPP 7.2.30-0
 
Features had been carried out:
- Base authentication system.
- Mail account verification.
- CRUD of posts model.
- CRUD of users model.
- Partially complete fixed design written on raw CSS.
- Base file upload (files stored on web server and alias stored in DB).

What I missing:
- Security.
- Proper uploaded file system.
- Enhanced code structure and architecture.

In order to run application,
you need to firstly reset dependencies:
```
composer update
```
And initialize database, using
**rigmanager_db_init.sql** file (exported from PhpMyAdmin).
