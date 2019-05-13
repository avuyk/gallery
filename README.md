# Gallery
WIP **Simple gallery to show image files**

**Overview**

* On the home page a category listing.
* This category listing is linked to category pages.
* The category pages are filled with images.
* The images are linked to full size show pages.
* In the admin section, images and users can be managed.

**Some features**
* Built with Symfony 4
* Auto fill database with dummy data for development and showcasing.
* Admin and user login required.
* Search on keywords.
* Bootstrapped for all devices.

**Getting started**

`composer install`

To enable the (mandatory) use of a mysql database, copy .env to .env.local and change this line:
`DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/gallery_125400`.
Set `db_user` and `db_password` to your local mysql login credentials.

`bin/console app:ReloadAndStart`

This command deletes all old images from /images/gallery, drops the old database if present, recreates a database with migrations, fills the database with fake data, and starts a server at http://localhost:8000
*This process takes several minutes for retrieving image files and saving them locally.*

Recreation of the database is needed with reloading. When only reloading the fixtures, the id of tabel category_file keeps auto incrementing on top of old id's. Tabel image_category is a join table without entity, that Doctrine generates and uses for the many-to-many relationship between ImageFile and Category.

Faker bundle fetches dummy images from http://lorempixel.com and saves them locally.
Via Fixtures it fills the database with the image paths, image descriptions and file names.
