-- Database schema for the Course Registration project.
-- Run this once in phpMyAdmin (or `mysql -u root < schema.sql`) before using the site.

CREATE DATABASE IF NOT EXISTS my_courses
    DEFAULT CHARACTER SET utf8mb4
    DEFAULT COLLATE utf8mb4_unicode_ci;

USE my_courses;

CREATE TABLE IF NOT EXISTS users (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    name     VARCHAR(100) NOT NULL,
    email    VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS courses (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    title       VARCHAR(150) NOT NULL,
    description TEXT,
    teacher     VARCHAR(100)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS enrollments (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    user_id       INT NOT NULL,
    course_id     INT NOT NULL,
    date_enrolled DATE NOT NULL,
    UNIQUE KEY uniq_user_course (user_id, course_id),
    FOREIGN KEY (user_id)   REFERENCES users(id)   ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- demo courses so the Courses page isn't empty
-- (DELETE FROM first so re-running this file gives a clean course list;
--  this also wipes enrollments because of the ON DELETE CASCADE above)
DELETE FROM courses;

INSERT INTO courses (title, description, teacher) VALUES
('Operation Systems',          'Processes, memory, and how the OS manages them.',     'Dr. Ahmed'),
('Intro to Web Development',   'HTML, CSS, and JavaScript basics for the web.',       'Dr. Ali'),
('Database Basics',            'Tables, relationships, and writing simple SQL.',      'Dr. Abdulrahman'),
('Intro to PHP',               'Server-side basics: variables, forms, and MySQL.',    'Dr. Yasir'),
('Software Engineering',       'Requirements, design, testing, and teamwork.',        'Dr. Khalid'),
('Object-Oriented Programming','Classes, objects, inheritance, and polymorphism.',    'Dr. Omar'),
('Computer Networks',          'How the internet works: TCP/IP, routing, and DNS.',   'Dr. Faisal'),
('Data Structures',            'Arrays, lists, stacks, queues, trees, and big-O.',    'Dr. Salim');
