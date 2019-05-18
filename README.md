# Gallery
WIP **Simple gallery to show image files**

This image gallery was set up to explore and exercise possibilities of Symfony, Twig and Doctrine. The project logo is a well fed pet, because, just like this pet project, it has consumed more resources than it should have. Styling of this project is kept to a minimum by use of Bootstrap features. With this ongoing project I like to develop and exercise some new skills. Next is the implementation of security and user management. 

**Overview**

* On the home page a linked category listing.
* Linked to show pages filled with images.
* In the admin section, images can be managed.

**Features so far**
* Built with Symfony 4
* Custom Command, Exceptions, FormType, Repository queries, Services and Twig extension.
* Fixtures fill the database with dummy data.
* Implementation of a Doctrine many-to-many relationship.
* JQuery ajax calls to an API endpoint on 'manage images' page.

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
