# Enterprise HRM System (SaaS Edition)

A modern, high-end Human Resource Management (HRM) SaaS Application built with Laravel. This enterprise-grade platform empowers organizations to manage employees, departments, attendance, payroll, recruitment, performance, assets, projects, company policies, and system administration from a unified, zero-scroll SaaS dashboard.

---

## 🌟 Key SaaS Design & Architectural Highlights

- **Modern SaaS Aesthetics:** Styled with a dark sidebar (`#0B0F19`), high-contrast dark table headers (`#0F172A`), soft rounded cards, and a unified blue-themed color palette.
- **Zero-Scroll Responsive Tables:** Every table from Employees to Asset Assignments is engineered with `table-layout: auto` and `w-100 overflow-hidden` for 100% screen fit without horizontal scrollbars.
- **Centered Modal Overlays:** Blurred backdrop modal popups (`backdrop-filter: blur(4px)`) for Create & Edit forms across Categories, Policies, Asset Assignments, Tasks, and Branches.
- **Slide-over Drawers with Sticky Footers:** Slide-in right drawer components (480px width) with sticky footers for complex data entry (Add New Asset, Add Employee).
- **Instant AJAX Operations:** Form submissions process via background `fetch` requests, prepending or updating DOM cards and rows instantly without a full page reload.
- **Minimalist Action Icon System:** Replaced cluttering text buttons with clean, color-coded icon action buttons (`Pencil` for Edit, `Trash` for Delete, `Eye` for View Details, `Box-Arrow-In-Left` for Check-in) with 10px–12px flex gap spacing.
- **Color-Coded Status Pill Badges:** Soft background pill badges (`bg-success-subtle` for Active/Approved/Connected, `bg-warning-subtle` for In Progress/Pending/Updated, `bg-danger-subtle` for Expired/Rejected/Deleted, `bg-primary-subtle` for Assigned/Logged In).

---

## 🚀 System Features & Modules

### 🔐 1. Authentication & Authorization
- **Role-Based Access Control (RBAC):** Dedicated views and permissions for **Admin**, **Employee**, and **Client** roles.
- **Integrated Sidebar Profile Component:** Clean profile display block at the bottom of the sidebar with user avatar, bold name, and role badge.
- **Top App Bar Dropdown:** Quick access profile badge with instant **Sign Out (Logout)** dropdown.
- **Permission Matrix:** Customizable role-to-module permission checkboxes.

### 👥 2. Employee & Department Management
- **Employee Directory:** Complete employee profiles, contact info, department, designation, and status tracking.
- **Department & Designation Management:** Organize company hierarchy into structured units with manager assignments.
- **Branch Management:** Multi-office branch location management with assigned branch managers and active status indicators.

### ⏱️ 3. Attendance & Shift Management
- **Instant Check-In / Check-Out:** Live workplace attendance logging.
- **Shift & Holiday Management:** Shift schedules, working hours calculation, and holiday calendars.
- **Overtime & Late Arrival Tracking:** Automated detection of overtime hours and late arrivals.
- **Attendance Calendar & Reports:** Interactive monthly view of employee attendance records.

### 🌴 4. Leave Management
- **Leave Application Modal:** Centered application popup for employees.
- **Instant AJAX Approvals:** Admins approve or reject leave applications with instant badge state updates.
- **Leave Balance Tracking:** Automated entitlement calculation per leave type (Annual, Casual, Sick).

### 🚀 5. Project & Task Management
- **Project Tracking:** Project creation, client assignment, progress tracking bars, and deadline management.
- **Task Management SaaS Modal:** Centered modal popup for creating and assigning tasks with priority badges (Low, Medium, High).
- **Task Status Workflows:** Track task progression across To Do, In Progress, and Completed states.

### 💰 6. Payroll & Compensation
- **Salary Structure Management:** Base salary, allowances, deductions, and tax calculation formulas.
- **Payroll Processing:** Automated monthly payroll run for all active employees.
- **Payslip Generation & PDF Download:** Instant PDF payslip generation and download.
- **Payroll Reports & Exports:** Filterable payroll history with CSV/PDF export.

### 📈 7. Performance & Appraisals
- **KPI & Goal Tracking:** Key Performance Indicator assignment and progress monitoring.
- **Performance Reviews:** Appraisal scoring, manager feedback, and appraisal cycles.
- **Increment & Promotion Records:** Historical log of salary raises and job title promotions.

### 🎯 8. Recruitment & Candidate Pipeline
- **Job Openings:** Publish job requisitions across departments.
- **Candidate Tracking:** Track job applications through Screening, Interview, Offer, and Hired stages.
- **Offer Letter Generation & Onboarding:** Issue digital offer letters and guide new hire onboarding checklists.

### 📦 9. Asset & Inventory Management
- **Asset Categories Modal:** Centered modal popup for defining hardware and software asset categories.
- **Company Assets Inventory Drawer:** Slide-over right drawer for registering hardware, serial numbers, cost, purchase dates, and color-coded warranty badges (Red for Expired, Green for Active).
- **Asset Details Modal:** Centered specification popup displaying full asset details and serial QR information.
- **Asset Assignments & Returns:** Check-out asset modal, check-in return modal, vertical date/condition stacking, and employee avatar tracking.

### 📜 10. Company Policies
- **SaaS Policy Cards Grid:** Clean card layout featuring rounded pill category tags (Leave Policy, Attendance Policy, Code of Conduct, IT & Security, Remote Work).
- **Create & Edit Policy Modals:** Centered modal overlays with blurred backdrops for adding or updating company rules.

### 🛡️ 11. System Administration & Diagnostics
- **System Settings & Health Dashboard:** 2-Column SaaS dashboard with organization details, SMTP mail parameters, system health diagnostics (Database latency, Storage Disk Space progress bar with Blue/Orange/Red color logic, SMTP host status, Backup directory status), and environment details (PHP version, Laravel framework, Active users, Server OS).
- **System Activity Audit Trail:** High-end activity log with color-coded action category badges (Blue for Login, Green for Created, Orange for Updated, Red for Deleted), Operator avatar initials, stacked `M d, Y` / `H:i:s` timestamps, monospace IP badges with desktop icons, and search/filtering controls.
- **Login History:** Real-time log of user login sessions, IP addresses, and user-agent details.

---

## 🛠️ Technologies Used

- **Backend Framework:** Laravel 10 / 11
- **Programming Language:** PHP 8.2+
- **Database Engine:** MySQL 8.0
- **Templating Engine:** Laravel Blade Components & Layouts
- **Frontend Styling:** Bootstrap 5.3, Bootstrap Icons (`bi`), Custom Vanilla CSS
- **Asynchronous Scripting:** JavaScript (Fetch API AJAX, Bootstrap Modals & Offcanvas APIs)
- **PDF Generation:** DomPDF / Laravel PDF
- **Version Control:** Git & GitHub

---

## 💻 Installation & Setup Guide

### 1. Clone the Repository
```bash
git clone https://github.com/masif078/hrm-system.git
cd hrm-system
```

### 2. Install Composer Dependencies
```bash
composer install
```

### 3. Environment Configuration
Copy the example `.env` file and configure your database credentials:
```bash
cp .env.example .env
php artisan key:generate
```

Edit your `.env` file with database settings:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hrm_system
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Run Migrations & Seeders
```bash
php artisan migrate --seed
```

### 5. Clear Caches & Serve Application
```bash
php artisan view:clear
php artisan cache:clear
php artisan serve
```

Access the application at: **`http://127.0.0.1:8000`**

---

## 📁 Directory Structure

```
hrm-system/
├── app/
│   ├── Http/Controllers/       # Module Controllers (Asset, Policy, Setting, Branch, Payroll...)
│   ├── Models/                 # Eloquent Models (User, Employee, Asset, CompanyPolicy...)
│   └── Providers/
├── bootstrap/
├── config/
├── database/
│   ├── migrations/             # Database Schema Migrations
│   └── seeders/
├── public/                     # Public Assets (CSS, JS, Images)
├── resources/
│   ├── views/                  # Blade Templates
│   │   ├── asset-assignments/   # Check-out & Check-in Modals
│   │   ├── asset-categories/    # Category Modals
│   │   ├── assets/              # Slide-over Drawer & Details Modal
│   │   ├── branches/            # Branch Modals & Table
│   │   ├── company-policies/    # Policy Cards & Modals
│   │   ├── components/          # Reusable Components (Avatar, Breadcrumb, Empty State...)
│   │   ├── layouts/             # App Layout, Sidebar & Top Navigation
│   │   └── settings/            # Settings Dashboard & Activity Audit Trail
├── routes/
│   └── web.php                 # Application Web Routes
└── storage/
```

---

## 👨‍💻 Author

**Muhammad Asif**  
GitHub: [https://github.com/masif078](https://github.com/masif078)

---

## 📄 License

This project is developed for educational and professional learning purposes.