CREATE TABLE IF NOT EXISTS books (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    author_id TEXT NOT NULL,
    is_available BOOLEAN DEFAULT TRUE,
    seduc_code TEXT NOT NULL,
    genre TEXT NOT NULL,
    quantity TEXT NOT NULL,
    FOREIGN KEY (author_id) REFERENCES authors(id)
);