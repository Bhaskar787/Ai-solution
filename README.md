# 🤖 AI Solution

**AI Solution** is a dynamic PHP-based web platform designed to showcase artificial intelligence–related services, events, and projects.  
It provides an integrated interface for users to explore AI solutions, view portfolios, read articles, and contact the organization directly through the website.

---

## 🌐 Features

- 🏠 **Home Page** – Highlights key AI services and company information  
- 🧠 **Solutions Section** – Showcases AI-powered tools and technologies offered  
- 📄 **Articles Page** – Displays AI-related articles and resources  
- 🎟️ **Events Management** – View and manage events stored in a MySQL database  
- 💬 **Feedback & Contact Forms** – Uses **PHPMailer** for secure email communication  
- 🧑‍💼 **Admin Panel** – Manage website content, feedback, and events through a dedicated admin interface  
- 💾 **Database Integration** – Includes SQL scripts for easy setup of required tables  

---

## 🧰 Tech Stack

| Category | Technology |
|-----------|-------------|
| Frontend | HTML5, CSS3, JavaScript |
| Backend | PHP |
| Database | MySQL |
| Email Handling | PHPMailer |
| Version Control | Git & GitHub |

---

## 🗂️ Project Structure

```
AI Solution/
├── admin/                      # Admin dashboard and management tools
├── css/                        # Frontend styles
├── includes/                   # Reusable PHP components
├── PHPMailer/                  # Email handling library
├── articles.php
├── contact.php
├── events.php
├── feedback.php
├── index.php                   # Landing page
├── navbar.php
├── footer.php
├── portfolio.php
├── solutions.php
├── create_email_errors_table.sql
├── create_events_table.sql
├── create_processed_emails_table.sql
├── ai.svg
└── TODO.md
```

---

## 🚀 Getting Started

### Prerequisites
- PHP 7.4 or higher
- MySQL Server
- A web server (Apache or XAMPP/WAMP recommended)
- Internet connection for PHPMailer (if using SMTP)

### Installation Steps
1. **Clone the repository**
   ```bash
   git clone https://github.com/Bhaskar787/Ai-Solution.git
   ```
2. **Move the project** into your web server directory (`htdocs` in XAMPP).
3. **Create a database** in MySQL ( `ai_solution_db`).
4. **Import SQL files** (`create_events_table.sql`, etc.) into the database.
5. **Configure PHPMailer** (inside `PHPMailer` or config file) with your SMTP credentials.
6. **Start Apache and MySQL**, then open the app in your browser:
   ```
   http://localhost/Ai-Solution
   ```


