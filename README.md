# Course Registration Website

A university course registration web app where students can create an account, browse courses, enroll, and manage their enrollments.

**Tech stack:** PHP · MySQL · HTML · CSS · JavaScript

## Design Philosophy

A minimal, easy-to-use interface. Students should be able to find and enroll in a course in as few steps as possible.

## Features

- **Student accounts:** register, log in, and log out
- **Browse courses:** see each course's title, description, and teacher
- **Enroll in courses:** with a check that stops duplicate enrollments
- **My Enrollments:** view the courses you're enrolled in and the date you enrolled, and cancel any of them
- **Profile:** update your name and email, or delete your account (this also removes your enrollments)
- **Form validation:** client-side checks in JavaScript, plus server-side checks in PHP

## Security

- Passwords are hashed with `password_hash()` and checked with `password_verify()`
- All database queries that take user input use prepared statements
- The session ID is regenerated on login
- Output is escaped with `htmlspecialchars()`

## Project Structure

```
PHP + MySQL/      The working application (PHP pages, schema.sql, style.css, validate.js)
HTML/             Static HTML mockups of each page, made before the PHP version
CSS/              Stylesheet used by the mockups
JAVASCRIPT/       Validation script used by the mockups
```

## Getting Started

1. Install [XAMPP](https://www.apachefriends.org/) (or any PHP + MySQL setup).
2. Copy the `PHP + MySQL` folder into `htdocs` and rename it, for example to `course-registration`.
3. Create the database: open phpMyAdmin, then import `schema.sql`.
   This creates the `my_courses` database and adds sample courses.
4. If your MySQL username or password isn't the XAMPP default (`root` with no password), edit `db.php`.
5. Open `http://localhost/course-registration/` in your browser.

## Author

Ahmed, Computer Science student at Saudi Electronic University (CS361 project)
