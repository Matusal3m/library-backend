CREATE TABLE IF NOT EXISTS books (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    author_id TEXT NOT NULL,
    is_available BOOLEAN DEFAULT TRUE,
    seduc_code TEXT NOT NULL,
    FOREIGN KEY (author_id) REFERENCES author(id)
);