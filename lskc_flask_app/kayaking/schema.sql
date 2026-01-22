-- Initialize the database.
-- Drop any existing data and create empty tables.

DROP TABLE IF EXISTS keybox;
DROP TABLE IF EXISTS container;

CREATE TABLE keybox (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  keyname TEXT UNIQUE NOT NULL,
  keycode TEXT NOT NULL
);

CREATE TABLE container (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  author_id INTEGER NOT NULL,
  created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  title TEXT NOT NULL,
  body TEXT NOT NULL,
  FOREIGN KEY (author_id) REFERENCES user (id)
);