ALTER TABLE t_persons ADD created_at TIMESTAMP DEFAULT NOW();
UPDATE t_persons SET created_at = "2011-06-18";
ALTER TABLE t_persons ADD submitted_at DATETIME;

