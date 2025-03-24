# 📝 Simple Blog System (PHP Native)

## 📌 Description
A basic blog system built using native PHP and MySQL.  
The system allows admin users to manage blog posts easily (CRUD) with a modern and responsive UI using Bootstrap 5.

---

## 🚀 Features
- Display all blog posts (with pagination & live search)
- View a single post by ID
- Create a new post
- Edit existing posts
- Delete posts
- Admin login/register/logout system
- Toastr notifications for actions
- Responsive modern UI (2025 design)

---

## ⚙️ Technologies Used
- PHP (Native, without frameworks)
- MySQL
- HTML5 + CSS3
- Bootstrap 5
- Font Awesome
- Google Fonts
- Toastr.js

---

## 🗂️ Project Structure

```
simple-blog/ ├── config/ │ └── db.php ├── public/ │ ├── index.php │ ├── create.php │ ├── edit.php │ ├── delete.php │ ├── show.php │ ├── login.php │ ├── register.php │ ├── logout.php │ └── favicon.ico ├── sql/ │ └── blogdb.sql ├── style.css (optional) └── README.md 

```


---

## 🛠️ Setup Instructions

1. Clone the repository or download the project ZIP.
2. Copy the project folder into your local server (e.g., `htdocs` for XAMPP).
3. Start **Apache** and **MySQL** from XAMPP.
4. Open **phpMyAdmin** and create a new database named `BlogDB`.
5. Import the SQL file from `sql/blogdb.sql` to create the required tables (`posts`, `admins`).
6. Open `config/db.php` and update it with your local DB credentials.
7. Access the app from [http://localhost/simple-blog/public](http://localhost/simple-blog/public)

---

## 👤 Admin Access

- Register new admin:  
  `http://localhost/simple-blog/public/register.php`

- Login page:  
  `http://localhost/simple-blog/public/login.php`

---

## 📄 Author

Developed by **Amal Hamdi Abd-ElRazeq**  
**Course Task**: Simple Blog System using PHP Native  
**Date**: 24 March 2025
