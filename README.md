
# 🎓 Learning Management System (LMS)

<p align="center">
<img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
<img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
<img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
<img src="https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
</p>
A comprehensive Learning Management System built with Laravel featuring role-based access control, course management, assignment submission, and student performance tracking.

## 🚀 Features

### 🔐 Authentication & Authorization

* User registration and login system
* Role-based access control (Admin, Lecturer, Student)
* Secure middleware protection
* Automatic role-based dashboard redirection

### 📊 Role-Based Dashboards

#### 👨‍💼 Admin Dashboard

* User management (view, edit, delete users)
* Role assignment and management
* System activity monitoring
* Platform statistics and analytics

#### 👨‍🏫 Lecturer Dashboard

* Course creation and management
* Lecture material uploads
* Assignment creation and management
* Student submission review and grading
* Performance analytics

#### 👨‍🎓 Student Dashboard

* Course enrollment and viewing
* Lecture material downloads
* Assignment submission system
* Grade and feedback viewing
* Progress tracking

### 📚 Course Management

* Create, edit, and delete courses
* Course categorization and organization
* Student enrollment management
* Course material organization

### 📝 Assignment System

* Assignment creation with deadlines
* File upload support for submissions
* Multiple submission formats (text, files, code)
* Automated deadline tracking
* Grading and feedback system

### 📁 Material Management

* Upload lecture materials (PDFs, videos, documents)
* Organize materials by course
* Download functionality for students
* Version control for materials

### 📈 Performance Tracking

* Assignment grading system
* Student progress indicators
* Performance analytics
* Grade reporting and export

## 🛠️ Technology Stack

* **Backend** : Laravel 10+
* **Frontend** : Blade Templates with Tailwind CSS
* **Database** : MySQL
* **Authentication** : Laravel Sanctum/Breeze
* **File Storage** : Laravel Storage
* **Styling** : Tailwind CSS

## 📋 Prerequisites

Before you begin, ensure you have the following installed:

* PHP >= 8.1
* Composer
* Node.js & NPM
* MySQL or another supported database
* Git

## ⚙️ Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/lms-project.git
   cd lms-project
   ```
2. **Install PHP dependencies**
   ```bash
   composer install
   ```
3. **Install Node dependencies**
   ```bash
   npm install
   ```
4. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
5. **Configure your `.env` file**
   ```env
   APP_NAME="Learning Management System"
   APP_ENV=local
   APP_KEY=base64:your-key-here
   APP_DEBUG=true
   APP_URL=http://localhost:8000

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=lms_database
   DB_USERNAME=your_username
   DB_PASSWORD=your_password

   MAIL_MAILER=smtp
   MAIL_HOST=your-mail-host
   MAIL_PORT=587
   MAIL_USERNAME=your-email
   MAIL_PASSWORD=your-password
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS=noreply@yourdomain.com
   ```
6. **Database Setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```
7. **Build assets**
   ```bash
   npm run dev
   ```
8. **Create storage link**
   ```bash
   php artisan storage:link
   ```
9. **Start the development server**
   ```bash
   php artisan serve
   ```

Visit `http://localhost:8000` to access the application.

## 👥 Default User Accounts

After seeding, you can login with these default accounts:

| Role     | Email            | Password | Access Level                 |
| -------- | ---------------- | -------- | ---------------------------- |
| Admin    | admin@lms.com    | password | Full system access           |
| Lecturer | lecturer@lms.com | password | Course & student management  |
| Student  | student@lms.com  | password | Course viewing & submissions |

## 🗂️ Project Structure

```
├── app/
│   ├── Http/Controllers/
│   │   ├── AdminController.php
│   │   ├── LecturerController.php
│   │   ├── StudentController.php
│   │   └── AssignmentController.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Course.php
│   │   ├── Assignment.php
│   │   └── Submission.php
│   └── Middleware/
│       ├── AdminMiddleware.php
│       ├── LecturerMiddleware.php
│       └── StudentMiddleware.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   └── views/
│       ├── admin/
│       ├── lecturer/
│       ├── student/
│       └── layouts/
└── routes/
    └── web.php
```

## 🎯 Main System Outputs

| Feature             | Description                           | Access               |
| ------------------- | ------------------------------------- | -------------------- |
| Authentication      | Login/register with role assignment   | All users            |
| Admin Panel         | User management and system oversight  | Admin only           |
| Course Management   | Create and manage courses             | Lecturers            |
| Assignment System   | Create, submit, and grade assignments | Lecturers & Students |
| Material Library    | Upload and download course materials  | All roles            |
| Grade Tracking      | View and manage student performance   | Lecturers & Students |
| Dashboard Analytics | Role-specific data visualization      | All roles            |

## 🔒 Security Features

* CSRF protection on all forms
* SQL injection prevention with Eloquent ORM
* XSS protection with Blade templating
* File upload validation and sanitization
* Role-based access control middleware
* Secure password hashing

## 🧪 Testing

Run the application tests:

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature
```

## 📱 Responsive Design

The application is fully responsive and optimized for:

* Desktop computers
* Tablets
* Mobile devices

Built with Tailwind CSS for consistent, modern styling across all devices.

## 👨‍💻 About the Developer

This Learning Management System was developed by  **[Your Name]** .

**Contact Information:**

* 📧 Email: [godfreyj.sule1.email@example.com](mailto:Godfreyj.sule1@example.com)
* 🐱 GitHub: [@odafe32](https://github.com/odafe32)
* 💼 LinkedIn: [Joseph Godfrey](https://www.linkedin.com/in/joseph-godfrey-a06370248/)

I'm passionate about creating educational technology solutions that make learning more accessible and efficient. This LMS project represents my commitment to building robust, user-friendly applications that serve the educational community.

Feel free to reach out if you have any questions, suggestions, or would like to collaborate on educational technology projects!

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](https://claude.ai/chat/LICENSE) file for details.

## 🆘 Support

If you encounter any issues or have questions:

1. Check the [documentation](https://claude.ai/chat/docs/)
2. Search existing [issues](https://github.com/yourusername/lms-project/issues)
3. Create a new issue with detailed information

## 🗺️ Roadmap

* [ ] notifications
* [ ] Mobile app development
* [ ] Advanced analytics dashboard
* [ ] Multi-language support
* [ ] API development for third-party integrations

## 👏 Acknowledgments

* Laravel community for the excellent framework
* Tailwind CSS for the utility-first CSS framework
* Contributors and testers who helped improve this project

---

**Built with ❤️ using Laravel and Tailwind CSS**
