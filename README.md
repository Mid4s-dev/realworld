# Modern RealWorld Blog Application

A modern blog application built with Laravel 12.0, Sanctum authentication, and Tailwind CSS v4.0, inspired by the [RealWorld example app](https://github.com/gothinkster/realworld).

## ✨ Features

- 🔐 **User Authentication** - Register, login, logout with Laravel Sanctum
- 📝 **Article Management** - Create, read, update, delete articles with rich text
- 🏷️ **Tag System** - Categorize articles with tags
- 💬 **Comments** - Add and manage comments on articles
- ❤️ **Favorites** - Like/favorite articles
- 👥 **User Following** - Follow other users
- 📱 **Responsive Design** - Modern UI with Tailwind CSS
- 🔍 **Search & Filter** - Filter articles by tags and authors
- 📊 **Feed System** - Personal feed and global feed

## 🛠 Tech Stack

- **Backend**: Laravel 12.0 with PHP 8.2+
- **Authentication**: Laravel Sanctum
- **Database**: SQLite (easily configurable for MySQL/PostgreSQL)
- **Frontend**: Tailwind CSS v4.0
- **Build Tool**: Vite
- **JavaScript**: ES6+ with Axios for AJAX

## 🚀 Quick Start

### Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js & npm

### Installation

1. **Clone the repository**
   ```bash
   git clone git@github.com:Mid4s-dev/realworld.git
   cd realworld
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install JavaScript dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database setup**
   ```bash
   php artisan migrate
   php artisan db:seed --class=BlogSeeder
   ```

6. **Build assets**
   ```bash
   npm run build
   ```

7. **Start the development server**
   ```bash
   php artisan serve
   ```

Visit `http://localhost:8000` to see your blog!

## 📖 Usage

### Test Accounts
The seeder creates test users:
- Email: `john@example.com` / Password: `password`
- Email: `jane@example.com` / Password: `password`  
- Email: `bob@example.com` / Password: `password`

### Main Features

1. **Browse Articles**: Visit the homepage to see all articles
2. **Sign Up/Login**: Create an account or sign in
3. **Create Articles**: Use "New Article" to write posts
4. **Interact**: Like articles, follow users, add comments
5. **Profile**: Manage your profile and settings

## 🏗 Project Structure

```
├── app/
│   ├── Http/Controllers/
│   │   ├── Api/              # API endpoints
│   │   ├── HomeController.php
│   │   ├── WebArticleController.php
│   │   └── WebAuthController.php
│   └── Models/               # Eloquent models
├── database/
│   ├── migrations/           # Database schema
│   └── seeders/             # Test data
├── resources/
│   ├── css/                 # Tailwind CSS
│   ├── js/                  # JavaScript
│   └── views/               # Blade templates
└── routes/
    ├── api.php              # API routes
    └── web.php              # Web routes
```

## 🔌 API Endpoints

The application includes a full REST API:

- `POST /api/users/login` - User login
- `POST /api/users` - User registration  
- `GET /api/articles` - Get articles
- `POST /api/articles` - Create article
- `GET /api/articles/{slug}` - Get article
- `PUT /api/articles/{slug}` - Update article
- `DELETE /api/articles/{slug}` - Delete article
- `POST /api/articles/{slug}/favorite` - Favorite article
- `POST /api/profiles/{username}/follow` - Follow user

## 🎨 Customization

### Styling
- Edit `resources/css/app.css` for custom styles
- Modify `tailwind.config.js` for Tailwind configuration

### Database
- Change database driver in `.env` file
- Update `config/database.php` for custom configurations

## 🤝 Contributing

1. Fork the project
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📝 License

This project is open source and available under the [MIT License](LICENSE).

## 🙏 Acknowledgments

- Inspired by [RealWorld](https://github.com/gothinkster/realworld)
- Built with [Laravel](https://laravel.com/)
- Styled with [Tailwind CSS](https://tailwindcss.com/)

---

**Live Demo**: [Your deployed application URL here]

**Repository**: [https://github.com/Mid4s-dev/realworld](https://github.com/Mid4s-dev/realworld)

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
