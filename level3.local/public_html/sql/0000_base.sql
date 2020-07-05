-- Create all start tables --

-- Table versions --
create table if not exists `versions`
(
    `id`      serial       not null,
    `name`    varchar(255) not null,
    `created` timestamp default current_timestamp,
    primary key (id)
)
    engine = innodb
    character set utf8
    collate utf8_general_ci;

-- Table books --
create table if not exists `books`
(
    `id`               serial       not null,
    `book_name`        varchar(255) not null,
    `year`             int(4),
    `picture`          varchar(255),
    `author_name`      varchar(255) not null,
    `number_of_clicks` INT(11) UNSIGNED DEFAULT 0,
    `is_active`        INT(1) UNSIGNED  DEFAULT 1
)
    engine = innodb
    character set utf8
    collate utf8_general_ci;


