create database searchdb;

grant all on searchdb.* to searchdb@localhost identified by 'searchdb';

use searchdb;

create table search (
        id int not null auto_increment primary key,
        title varchar(255),
        URL varchar(255) unique,
        mainbody text,
        created datetime
        );