# Campus Hustle

A student-driven web app that helps university students find **part-time jobs, internships, and opportunities**. Built as the **Advanced Web Technology (final project)**.

## ✨ Core Features
- Job listings with **search & filters** (type, category, location)
- **Student profiles** (bio, skills, LinkedIn, resume upload)
- **Apply** to jobs + track status (Submitted → Shortlisted → Rejected/Hired)
- **Save** jobs (bookmarks)
- **Employer portal** (post/edit jobs, view applicants)
- **Admin tools** (manage users & jobs)
- Responsive UI with **Bootstrap**


## 🧱 Tech Stack
**PHP , MySQL , Bootstrap **  
Local runtime: **XAMPP**

## 🛠️ Setup (Local)
1. **Clone**
   ```bash
   git clone https://github.com/SiumalVirajith/campus-hustle.git
   ```
2. **Move to web root**  
   XAMPP (Windows): `C:\xampp\htdocs\campus-hustle`
3. **Create DB**  
   - phpMyAdmin → create DB (e.g., `campus_hustle_db`)  
   - Import `database/schema.sql` then `database/seed.sql`
4. **Configure env**
   - Copy `config/env.sample.php` → `config/env.php` and fill:
     ```php
     return [
       'APP_URL' => 'http://localhost/campus-hustle/public',
       'DB_HOST' => '127.0.0.1',
       'DB_PORT' => '3306',
       'DB_NAME' => 'campus_hustle_db',
       'DB_USER' => 'root',
       'DB_PASS' => '',
       'UPLOAD_MAX_MB' => 5,
     ];
     ```
5. **Run**  
   Start **Apache** + **MySQL**, visit: `http://localhost/campus-hustle/public`

## 🔐 Roles (default)
- **Student:** browse, save, apply, profile  
- **Employer:** post/edit jobs, view applicants  
- **Admin:** manage users, archive jobs

## ✅ Checklist
- [ ] Register/Login works  
- [ ] Create/Update/Delete job (Employer)  
- [ ] Apply & status change flow  
- [ ] Save/Unsave jobs  
- [ ] Mobile responsive

## 🛡️ Security
- Prepared statements (mysqli/PDO)  
- `htmlspecialchars` on output  
- CSRF token on forms  
- Validate file uploads (PDF only, size limit)


## 🙏 Acknowledgements
University of Vavuniya — **Advanced Web Technology**  

