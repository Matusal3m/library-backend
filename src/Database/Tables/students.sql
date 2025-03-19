CREATE TABLE IF NOT EXISTS students (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    enrollment_number TEXT NOT NULL UNIQUE,
    name TEXT NOT NULL,
    class_room TEXT NOT NULL,
    loan_id INTEGER, 
    FOREIGN KEY (loan_id) REFERENCES loan(id)
);