INSERT INTO authors (author_name)
SELECT author_name
FROM books;

INSERT IGNORE INTO authors(author_name)
select SUBSTRING_INDEX(author_name, ', ', 1)
FROM authors;

INSERT IGNORE INTO authors(author_name)
select SUBSTRING_INDEX(few_authors, ', ', -1)
FROM (select SUBSTRING_INDEX(author_name, ', ', 2) as few_authors
      FROM authors) as few_authors_result;

INSERT IGNORE INTO authors(author_name)
select SUBSTRING_INDEX(few_authors, ', ', -1)
FROM (select SUBSTRING_INDEX(author_name, ', ', 3) as few_authors
      FROM authors) as few_authors_result;

INSERT IGNORE INTO authors(author_name)
select SUBSTRING_INDEX(few_authors, ', ', -1)
FROM (select SUBSTRING_INDEX(author_name, ', ', 4) as few_authors
      FROM authors) as few_authors_result;

INSERT IGNORE INTO authors(author_name)
select SUBSTRING_INDEX(few_authors, ', ', -1)
FROM (select SUBSTRING_INDEX(author_name, ', ', 5) as few_authors
      FROM authors) as few_authors_result;

INSERT IGNORE INTO authors(author_name)
select SUBSTRING_INDEX(few_authors, ', ', -1)
FROM (select SUBSTRING_INDEX(author_name, ', ', 6) as few_authors
      FROM authors) as few_authors_result;

DELETE
FROM authors
WHERE author_name LIKE '%, %';

INSERT INTO pairs (`id_books`, `id_authors`)
SELECT books.id as books_id, authors.id as authors_id
FROM books
         INNER JOIN authors ON books.author_name LIKE concat('%', authors.author_name, '%');

ALTER TABLE books
    DROP author_name;

