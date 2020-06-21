# snappy-properties
Property System - A task to showcase my PHP, MySQL and Javascript skills.

### INSTRUCTIONS

#### Requirements
PHP 7.4
MYSQL 8.0

#### Installation
Create a database and add the details in the .env file and run the sql statements below.

Run composer install
Run npm install
Run npm run dev to build the assets.



Routes:

/admin/properties/import
/admin/properties


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
