CREATE EXTENSION btree_gist;
CREATE INDEX idx_books_name ON books USING gist (name);
CREATE INDEX idx_books_id ON books (id);
CREATE INDEX idx_user_id ON users (id);
CREATE INDEX idx_authors_id ON authors (id);