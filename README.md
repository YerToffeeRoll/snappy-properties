# snappy-properties
Property System - A task to showcase my PHP, MySQL and Javascript skills. We have borrowed some symfony components to create a lightweight custom framework whose assets are generated by npm webpack.

### INSTRUCTIONS

#### Requirements
PHP 7.4
MYSQL 5.7

#### Installation
Create a database and add the details in the .env file and run the sql statements below.

Run composer install
Run npm install
Run npm run dev to build the assets.


Go to "http://<your-domain>/admin/properties/import"

This will import the proprty data to the database.


Routes:

/admin/properties
/admin/properties/create
/admin/properties/{id}/edit
/admin/properties/{id}/delete
/admin/properties/import

#### Future work
Tests could be written, possibly use PHPUnit.
Routes can be moved from the Kernel to a config file.  
Custom exception need to be written and used.  
When running the import the validation should be used.  
When saving to the database the parameters functionality should be added for more security.  
Image upload is shallow and needs improving.  
UpdatedAt needs to be updated when record is updated.  
A show page can be added to view the property.  
Ideally I would pull more of the data out of the import controller.  
Database needs indexing properly.  
Pagination isn't the best and needs improvement.  

#### SQL

```
create table properties
(
    id             int auto_increment
        primary key,
    propertyTypeId int unsigned  null,
    county         varchar(255)  not null,
    country        varchar(255)  not null,
    town           varchar(255)  not null,
    postcode       varchar(255)  null,
    description    text          not null,
    address        text          null,
    latitude       varchar(255)  null,
    longitude      varchar(255)  null,
    imageFull      varchar(255)  not null,
    imageThumbnail varchar(255)  not null,
    numBedrooms    int default 0 not null,
    numBathrooms   int default 0 not null,
    price          int default 0 not null,
    type           varchar(255)  not null,
    uuid           varchar(255)  null,
    createdAt      datetime      not null,
    updatedAt      datetime      not null
);
```
```
create table propertyTypes
(
    id          int default 0 not null,
    title       varchar(25)   not null,
    description text          null,
    createdAt   datetime      null,
    updatedAt   datetime      null
);
```
