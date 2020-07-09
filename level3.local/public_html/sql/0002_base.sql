create table if not exists `authors`
(
    `id`          serial       not null,
    `author_name` varchar(255) not null unique
)
    engine = innodb
    character set utf8
    collate utf8_general_ci;


create table if not exists `pairs`
(
    `id`         serial not null,
    `id_books`   bigint unsigned,
    `id_authors` bigint unsigned,

    FOREIGN KEY (id_books) REFERENCES books (id)
        on delete set null,
    FOREIGN KEY (id_authors) REFERENCES authors (id)
        on delete set null
)
    engine = innodb
    character set utf8
    collate utf8_general_ci;
