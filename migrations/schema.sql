-- Create database
CREATE DATABASE IF NOT EXISTS campus_hustle CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE campus_hustle;

-- Users
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('student','employer') NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Jobs
CREATE TABLE IF NOT EXISTS jobs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  employer_id INT NOT NULL,
  title VARCHAR(150) NOT NULL,
  description TEXT NOT NULL,
  category VARCHAR(100) NOT NULL,
  location VARCHAR(100) NOT NULL,
  salary VARCHAR(50),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_jobs_employer FOREIGN KEY (employer_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Applications
CREATE TABLE IF NOT EXISTS application (
  id INT AUTO_INCREMENT PRIMARY KEY,
  job_id INT NOT NULL,
  student_id INT NOT NULL,
  applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_app_job FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
  CONSTRAINT fk_app_student FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT uq_app UNIQUE (job_id, student_id)
) ENGINE=InnoDB;

-- Seed demo employer (password: demo1234)
INSERT INTO users (name,email,password,role) VALUES
('Demo Employer','employer@example.com', '$2y$10$QKx8S9cA1w5rMP4s5wz5uO8Gq4b1VQf5VdxXqgkWTl2xN5k7mZlLy', 'employer')
ON DUPLICATE KEY UPDATE email=email;

USE campus_hustle;

-- Get the employer id for employer@example.com
SET @emp := (SELECT id FROM users WHERE email='employer@example.com' LIMIT 1);

-- Safety check (optional): should show a number, not NULL
SELECT @emp AS employer_id;

-- Insert 50 jobs for that employer
INSERT INTO jobs (employer_id, title, description, category, location, salary) VALUES
(@emp,'Frontend Developer Intern','Build features with Bootstrap + PHP. Learn by doing.','Software','Colombo','Rs. 45,000'),
(@emp,'Junior PHP Developer','Assist in building PHP + MySQL features and fixing bugs under mentorship.','Software','Colombo','Rs. 120,000'),
(@emp,'React Developer','Build reusable components and hooks for SPA.','Software','Kandy','Rs. 165,000'),
(@emp,'Laravel Developer','Develop modules, queues, and mailers with Laravel.','Software','Galle','Rs. 185,000'),
(@emp,'Python Developer','Automate data pipelines and backend services.','Software','Trincomalee','Rs. 175,000'),
(@emp,'IT Support Technician','Resolve tickets, maintain inventory, assist users.','Software','Ratnapura','Rs. 95,000'),
(@emp,'DevOps Engineer','Set up CI/CD, Docker, and monitoring dashboards.','Software','Negombo','Rs. 200,000'),
(@emp,'QA Tester','Write test cases, run regressions, report issues.','Software','Matara','Rs. 110,000'),
(@emp,'Back-end Engineer (Node.js)','Design REST endpoints, optimize queries, document APIs.','Software','Kurunegala','Rs. 190,000'),
(@emp,'Junior Data Engineer','ETL development, warehouse maintenance.','Software','Kegalle','Rs. 140,000'),
(@emp,'Data Analyst (Junior)','Clean datasets, build dashboards, present insights.','Software','Anuradhapura','Rs. 130,000'),
(@emp,'Mobile App Developer','Build Android apps and integrate REST APIs.','Software','Jaffna','Rs. 170,000'),
(@emp,'Full-Stack Developer','Develop end-to-end web features (PHP/JS), write tests, deploy.','Software','Galle','Rs. 180,000'),
(@emp,'Systems Administrator','Maintain servers, backups, and monitoring.','Software','Colombo','Rs. 155,000'),
(@emp,'Cloud Intern','Assist with cloud deployments and documentation.','Software','Nuwara Eliya','Rs. 85,000'),
(@emp,'Cybersecurity Analyst (Junior)','Monitor alerts, triage incidents, update SOPs.','Software','Polonnaruwa','Rs. 150,000'),
(@emp,'Graphic Designer','Design social posts, brochures, and ads.','Design','Colombo','Rs. 115,000'),
(@emp,'UI Designer (Intern)','Craft polished UI screens from wireframes.','Design','Kandy','Rs. 85,000'),
(@emp,'UX Researcher','Plan studies, analyze data, propose improvements.','Design','Galle','Rs. 160,000'),
(@emp,'Product Designer','End-to-end feature design with prototypes.','Design','Jaffna','Rs. 185,000'),
(@emp,'Motion Graphics Artist','Create animations for campaigns and videos.','Design','Negombo','Rs. 150,000'),
(@emp,'Brand Designer','Develop brand assets and guidelines.','Design','Matara','Rs. 140,000'),
(@emp,'Illustrator','Vector art and icons for product and marketing.','Design','Kurunegala','Rs. 120,000'),
(@emp,'Web Designer','Responsive pages with accessibility best practices.','Design','Anuradhapura','Rs. 125,000'),
(@emp,'Creative Director (Assoc.)','Guide creatives, review assets, manage timelines.','Design','Batticaloa','Rs. 210,000'),
(@emp,'Visual Designer','Design UI states, banners, presentation decks.','Design','Colombo','Rs. 135,000'),
(@emp,'3D Artist (Junior)','Model and render simple scenes and products.','Design','Trincomalee','Rs. 130,000'),
(@emp,'UI/UX Designer','Wireframing, prototyping, usability testing.','Design','Ratnapura','Rs. 170,000'),
(@emp,'Marketing Intern','Assist campaigns, schedule posts, basic analytics.','Marketing','Colombo','Rs. 80,000'),
(@emp,'Social Media Executive','Run pages, respond to DMs, report metrics.','Marketing','Kandy','Rs. 110,000'),
(@emp,'Content Writer','Write blogs, emails, and landing copy.','Marketing','Galle','Rs. 100,000'),
(@emp,'SEO Specialist','Keyword research, on-page fixes, audit reports.','Marketing','Jaffna','Rs. 145,000'),
(@emp,'Digital Marketing Associate','Assist paid campaigns and tracking.','Marketing','Negombo','Rs. 130,000'),
(@emp,'Performance Marketer','Optimize ROAS across channels.','Marketing','Matara','Rs. 170,000'),
(@emp,'Email Marketing Coordinator','Automations, segmentation, A/B tests.','Marketing','Kurunegala','Rs. 120,000'),
(@emp,'Brand Executive','Coordinate brand campaigns and events.','Marketing','Anuradhapura','Rs. 140,000'),
(@emp,'PR & Communications Officer','Press releases, media lists, coverage reports.','Marketing','Batticaloa','Rs. 135,000'),
(@emp,'Community Manager','Moderate groups, grow engagement, report insights.','Marketing','Colombo','Rs. 125,000'),
(@emp,'Market Research Analyst','Surveys, interviews, dashboards.','Marketing','Trincomalee','Rs. 150,000'),
(@emp,'Event Coordinator','Plan logistics, vendor management, on-site ops.','Marketing','Ratnapura','Rs. 115,000'),
(@emp,'Accounts Intern','AP/AR assistance, reconciliations, vouchers.','Finance','Colombo','Rs. 90,000'),
(@emp,'Junior Accountant','GL entries, bank recs, month-end support.','Finance','Kandy','Rs. 135,000'),
(@emp,'Finance Analyst','Budget vs actuals, KPI reports, variance analysis.','Finance','Galle','Rs. 180,000'),
(@emp,'Payroll Executive','Payroll processing, EPF/ETF, payslips.','Finance','Jaffna','Rs. 130,000'),
(@emp,'Treasury Assistant','Cash flow, deposits, bank liaison.','Finance','Negombo','Rs. 145,000'),
(@emp,'Audit Associate','Fieldwork, working papers, findings.','Finance','Matara','Rs. 150,000'),
(@emp,'Tax Associate','Return prep, schedules, correspondence.','Finance','Kurunegala','Rs. 155,000'),
(@emp,'Credit Control Officer','Chasing overdues, setting limits, reports.','Finance','Anuradhapura','Rs. 140,000'),
(@emp,'Budget Analyst (Junior)','Assist annual budget and forecasts.','Finance','Batticaloa','Rs. 165,000'),
(@emp,'Procurement Executive','POs, vendor negotiation, inventory checks.','Finance','Colombo','Rs. 150,000'),
(@emp,'Risk & Compliance Assistant','Policy checks, registers, training logs.','Finance','Trincomalee','Rs. 160,000'),
(@emp,'Cost Accountant (Trainee)','Costing sheets, BOM updates, variance.','Finance','Ratnapura','Rs. 125,000');



ALTER TABLE application
  ADD COLUMN status ENUM('pending','accepted','rejected') NOT NULL DEFAULT 'pending' AFTER student_id,
  ADD COLUMN decided_at TIMESTAMP NULL DEFAULT NULL AFTER applied_at;

ALTER TABLE application ADD CONSTRAINT uq_app UNIQUE (job_id, student_id);
