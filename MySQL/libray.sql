# Таблица "Авторы"
CREATE TABLE authors
(
  id   BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL
)
  DEFAULT CHARSET = utf8
  ENGINE = INNODB;

INSERT INTO authors (id, name)
VALUES
  (1, 'Автор 1'),
  (2, 'Автор 2'),
  (3, 'Автор 3'),
  (4, 'Автор 4');

# Таблица "Книги"
CREATE TABLE book
(
  id   BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL
)
  DEFAULT CHARSET = utf8
  ENGINE = INNODB;

INSERT INTO book (id, name)
VALUES
  (1, 'Книга 1'),
  (2, 'Книга 2'),
  (3, 'Книга 3'),
  (4, 'Книга 4');

# Связующая таблица
CREATE TABLE book_authors
(
  book_id   BIGINT UNSIGNED NOT NULL,
  author_id BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (author_id, book_id),
  KEY book_id (book_id),
  CONSTRAINT book_authors_1 FOREIGN KEY (author_id) REFERENCES authors (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT book_authors_2 FOREIGN KEY (book_id) REFERENCES book (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
)
  ENGINE = INNODB;

INSERT INTO book_authors (book_id, author_id)
VALUES
  (1, 1),
  (1, 2),
  (2, 1),
  (2, 3),
  (2, 4),
  (3, 1),
  (3, 4),
  (4, 4);


# 1. Вывести список книг у которых кол-во авторов 3
SELECT
  book.id,
  book.name,
  GROUP_CONCAT(authors.name SEPARATOR ', ') AS authors,
  count(authors.name)                       AS count
FROM book
  JOIN book_authors ON book.id = book_authors.book_id
  JOIN authors ON book_authors.author_id = authors.id
GROUP BY book.id, book.name
HAVING count = 3;

# Этот запрос выводит полный список книг (столбцы: "id книги", "название книги", "авторы", "кол-во авторов")
SELECT
  book.id,
  book.name,
  GROUP_CONCAT(authors.name SEPARATOR ', ') AS authors,
  count(authors.name)                       AS count
FROM book
  JOIN book_authors ON book.id = book_authors.book_id
  JOIN authors ON book_authors.author_id = authors.id
GROUP BY book.id, book.name;