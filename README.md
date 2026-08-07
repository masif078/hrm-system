# HRM System

A complete Human Resource Management (HRM) System built with Laravel. This application helps organizations manage employees, departments, attendance, payroll, recruitment, performance, assets, projects, and other HR operations from a centralized dashboard.

---

## Features

### Authentication & Authorization
- Secure Login & Logout
- Role-Based Access Control
- Admin, Employee & Client Dashboards
- Profile Management
- Password Change

### Employee Management
- Employee CRUD
- Department Management
- Designation Management
- Employee Profiles
- Search & Filtering

### Attendance Management
- Check In / Check Out
- Shift Management
- Holiday Management
- Working Hours Calculation
- Overtime Tracking
- Late Arrival Detection
- Attendance Reports
- Attendance Calendar

### Leave Management
- Leave Applications
- Leave Approval / Rejection
- Leave Balance Management
- Leave Reports

### Project & Task Management
- Project CRUD
- Task CRUD
- Employee Task Assignment
- Project Progress Tracking
- Task Status Management
- Deadline Tracking

### Payroll Management
- Salary Structure
- Payroll Processing
- Payslip Generation
- PDF Payslip Download
- Payroll Reports

### Performance Management
- KPI Management
- Goal Tracking
- Performance Reviews
- Employee Appraisals
- Salary Increment History
- Promotion Records

### Recruitment Management
- Job Openings
- Candidate Management
- Interview Scheduling
- Interview Feedback
- Offer Letter Management
- Employee Onboarding

### Asset Management
- Asset Categories
- Asset Assignment
- Asset Return
- Maintenance Records
- Warranty Tracking

### Notifications
- Database Notifications
- Email Notifications
- Leave Notifications
- Task Assignment Notifications

### Reports
- Employee Reports
- Attendance Reports
- Leave Reports
- Payroll Reports
- Performance Reports

### System Administration
- Company Settings
- Branch Management
- Company Policies
- Activity Logs
- Login History
- Permission Matrix

---

## Technologies Used

- Laravel
- PHP
- MySQL
- Blade Template Engine
- Bootstrap 5
- JavaScript
- HTML5
- CSS3
- Git
- GitHub

---

## Installation

Clone the repository

```bash
git clone https://github.com/masif078/hrm-system.git
```

Go to project directory

```bash
cd hrm-system
```

Install dependencies

```bash
composer install
```

Copy environment file

```bash
cp .env.example .env
```

Generate application key

```bash
php artisan key:generate
```

Configure the database in the `.env` file.

Run migrations

```bash
php artisan migrate
```

Start the development server

```bash
php artisan serve
```

Visit

```
http://127.0.0.1:8000
```

---

## Project Structure

```
app/
bootstrap/
config/
database/
public/
resources/
routes/
storage/
tests/
```

---

## Author

**Muhammad Asif**

GitHub:
https://github.com/masif078

---

## License

This project is developed for learning and educational purposes.