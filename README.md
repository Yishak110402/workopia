# 🚀 Workopia - Job Board Application

A modern, full-featured job board application built with Laravel and Tailwind CSS. Workopia allows users to browse job listings, create job postings, manage their profiles, and bookmark interesting opportunities.

## ✨ Features

### 🔍 Job Management

-   **Browse Jobs**: View all available job listings with pagination
-   **Job Details**: Comprehensive job information including company details, requirements, and benefits
-   **Create Jobs**: Authenticated users can post new job opportunities
-   **Edit Jobs**: Job creators can update their listings
-   **Delete Jobs**: Remove expired or filled positions

### 👤 User Experience

-   **User Authentication**: Secure login and registration system
-   **User Profiles**: Manage personal information and preferences
-   **Dashboard**: Centralized view of user's job postings and activities
-   **Bookmarks**: Save interesting jobs for later review

### 🏢 Company Features

-   **Company Profiles**: Detailed company information and descriptions
-   **Company Logos**: Upload and display company branding
-   **Contact Information**: Direct communication channels for applicants

### 🎨 Modern UI/UX

-   **Responsive Design**: Works seamlessly on all devices
-   **Tailwind CSS**: Beautiful, modern styling with utility classes
-   **Interactive Elements**: Hover effects, smooth transitions, and intuitive navigation
-   **Professional Layout**: Clean, organized interface for optimal user experience

## 🛠️ Technology Stack

### Backend

-   **Laravel 11** - Modern PHP framework for robust backend development
-   **MySQL** - Reliable database management
-   **Eloquent ORM** - Elegant database interactions
-   **Authentication** - Built-in Laravel authentication system
-   **Policies** - Role-based access control and authorization

### Frontend

-   **Blade Templates** - Laravel's powerful templating engine
-   **Tailwind CSS** - Utility-first CSS framework
-   **Alpine.js** - Lightweight JavaScript framework for interactivity
-   **Font Awesome** - Professional icon library

### Development Tools

-   **Composer** - PHP dependency management
-   **Artisan** - Laravel command-line interface
-   **PHPUnit** - Testing framework
-   **Vite** - Modern build tool for assets

## 📋 Requirements

-   **PHP**: 8.1 or higher
-   **Composer**: Latest version
-   **MySQL**: 8.0 or higher
-   **Node.js**: 18 or higher (for Vite)
-   **Web Server**: Apache/Nginx or Laravel's built-in server

## 🚀 Installation

### 1. Clone the Repository

```bash
git clone https://github.com/yourusername/workopia.git
cd workopia
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure Database

Edit `.env` file with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=workopia
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Run Migrations

```bash
php artisan migrate
```

### 6. Seed Database (Optional)

```bash
php artisan db:seed
```

### 7. Build Assets

```bash
npm run build
```

### 8. Start Development Server

```bash
php artisan serve
```

Visit `http://localhost:8000` to see your application!

## 🗄️ Database Structure

### Core Tables

-   **users** - User accounts and profiles
-   **jobs** - Job listings and details
-   **job_user_bookmarks** - User job bookmarks
-   **sessions** - User session management
-   **password_reset_tokens** - Password reset functionality

### Key Relationships

-   Users can create multiple job listings
-   Users can bookmark multiple jobs
-   Jobs belong to specific users (employers)

## 🔐 Authentication & Authorization

### User Roles

-   **Guest Users**: Can browse jobs and register/login
-   **Authenticated Users**: Can create jobs, manage profiles, and bookmark positions
-   **Job Creators**: Can edit and delete their own job postings

### Security Features

-   CSRF protection on all forms
-   Input validation and sanitization
-   Secure password hashing
-   Session-based authentication
-   Policy-based authorization

## 🎯 Key Features Explained

### Job Creation System

Users can create comprehensive job postings including:

-   Job title, description, and requirements
-   Salary information and job type
-   Remote work options
-   Company details and branding
-   Contact information for applicants

### Search & Filtering

-   Paginated job listings
-   Job type categorization
-   Location-based filtering
-   Remote work options

### User Dashboard

-   Overview of posted jobs
-   Quick access to job management
-   Profile settings and preferences
-   Bookmarked positions

## 🧪 Testing

Run the test suite to ensure everything works correctly:

```bash
php artisan test
```

## 📱 API Endpoints

### Public Routes

-   `GET /` - Home page
-   `GET /jobs` - Browse all jobs
-   `GET /jobs/{id}` - View specific job
-   `GET /login` - Login page
-   `GET /register` - Registration page

### Protected Routes (Authentication Required)

-   `GET /jobs/create` - Create new job
-   `GET /jobs/edit/{id}` - Edit existing job
-   `PUT /jobs/edit/{id}` - Update job
-   `DELETE /jobs/delete/{id}` - Delete job
-   `GET /dashboard` - User dashboard
-   `GET /bookmarks` - User bookmarks
-   `POST /bookmarks/{id}` - Bookmark a job

## 🚀 Deployment

### Production Considerations

1. Set `APP_ENV=production` in `.env`
2. Configure production database
3. Set up proper web server (Apache/Nginx)
4. Configure SSL certificates
5. Set up proper file permissions
6. Configure caching (Redis/Memcached)

### Environment Variables

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
DB_CONNECTION=mysql
CACHE_DRIVER=redis
SESSION_DRIVER=database
QUEUE_CONNECTION=redis
```

## 🤝 Contributing

We welcome contributions! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Development Guidelines

-   Follow PSR-12 coding standards
-   Write meaningful commit messages
-   Include tests for new features
-   Update documentation as needed

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

-   **Laravel Team** - For the amazing framework
-   **Tailwind CSS** - For the beautiful styling system
-   **Alpine.js** - For lightweight interactivity
-   **Font Awesome** - For the professional icons

## 📞 Support

If you have any questions or need help:

-   **Issues**: [GitHub Issues](https://github.com/yourusername/workopia/issues)
-   **Discussions**: [GitHub Discussions](https://github.com/yourusername/workopia/discussions)
-   **Email**: your.email@example.com

## 🌟 Star History

If you find this project helpful, please consider giving it a ⭐️ on GitHub!

---

**Built with ❤️ using Laravel and Tailwind CSS**
