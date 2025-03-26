CREATE TABLE IF NOT EXISTS loans (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    student_id INTEGER NOT NULL,
    book_id INTEGER NOT NULL,
    started_at INTEGER NOT NULL,
    finish_date INTEGER NOT NULL,
    extended_at INTEGER,
    returned_at INTEGER,
    is_active BOOLEAN NOT NULL,
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (book_id) REFERENCES books(id)
);         