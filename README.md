# gallery
WIP gallery

**Gallery to show image files, made in Symfony.**

**Overview**

* A home page with a category listing, linked to paginated category pages with a thumbnail gallery of image files. 
* The thumbnails are linked to full size show pages. 
* An admin section to upload images and set description.

**Some features**
* Auto fill database with dummy data for development and showcasing. 
* Admin and user login required. 
* Search on keywords. 
* Bootstrapped for all devices. 

**Getting started**

Use command: bin/console app:ReloadAndStart 
This command deletes all old images from /images/gallery, drops the old database if present, recreates a database with migrations, fills the database with fake data, and starts a server at http://localhost:8000 
*The process takes several minutes for retrieving image files and saving them locally.*
 
Recreation of the database is needed because, when only reloading the fixtures, the id of tabel category_file keeps auto incrementing on top of old id's. Tabel category_file is a junction table without entity, that Doctrine generates and uses for the many-to-many relationship between files and categories.

Faker bundle fetches dummy images from http://lorempixel.com and saves them locally. 
Via Fixtures it fills the database with the image paths, image descriptions and file names.


