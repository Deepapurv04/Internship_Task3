# Internship_Task3 – Search, Pagination & UI Enhancements (ApexPlanet Internship)
I enhanced the blog system (built in Task 2) with advanced features to improve usability and user experience.

## ✅ Task 3 Features Implemented
1. Search Functionality (Dashboard)
2. Pagination (Dashboard)
3. UI Enhancements with Bootstrap 5

## 💻 Technologies Used
- PHP (v8+)
- MySQL (phpMyAdmin)
- Bootstrap 5 (via CDN)
- WAMP Server (Apache, MySQL, PHP)
- HTML, CSS
- Git & GitHub
- VS Code

## 📂 File Structure

Internship_Task3/
├── db.php → MySQL connection
├── index.php → Optional home/landing page
├── register.php → New user registration
├── login.php → User login
├── logout.php → Logout and destroy session
├── dashboard.php → Search + Pagination + Bootstrap UI
├── blog.php → User’s personal posts
├── post_create.php → Add new post
├── post_edit.php → Edit existing post
├── post_delete.php → Delete a post
└── README.md

## 🚀 How to Run Locally
1. Clone or download this repository:https://github.com/arijit0654/Internship_Task3.git
2. Place the folder inside WAMP directory: C:\wamp64\www\Internship_Task3
3. Start WAMP server (Apache & MySQL should be green)
4. Open phpMyAdmin → Import the `blog_system` SQL database
5. Visit: http://localhost/Internship_Task3/register.php

## 🔗 Demo Preview
> Dashboard now includes:
> - A search input to filter posts in real-time  
> - Clean pagination UI (5 posts per page)  
> - Posts from all users with timestamps and author name  
> - Links to "My Blog", "Create Post", and "Logout"
