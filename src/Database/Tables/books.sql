CREATE TABLE IF NOT EXISTS books (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    author_id TEXT NOT NULL,
    is_available BOOLEAN NOT NULL,
    seduc_code TEXT NOT NULL,
    loan_id INTEGER, 
    FOREIGN KEY (loan_id) REFERENCES loan(id),
    FOREIGN KEY (author_id) REFERENCES author(id)
);