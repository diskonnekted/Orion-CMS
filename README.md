# Orion CMS

Orion CMS is a lightweight, modular, and performance-oriented content management system built with native PHP and MySQL. Designed for flexibility and ease of use, it offers a robust foundation for building websites, blogs, and portfolios with a focus on modern development standards and clean architecture.

## Overview

Orion CMS provides a streamlined experience for both developers and content managers. It avoids the bloat of larger legacy systems while maintaining compatibility with essential workflows. The system is built with a clear separation of concerns, making it easy to extend via themes and plugins.

## Key Features

- **Lightweight Core:** Optimized for speed and minimal resource usage.
- **Modular Architecture:** Extend functionality through a robust plugin system.
- **Theme Engine:** Flexible theming support with a structure familiar to WordPress developers.
- **Modern Admin Interface:** Clean, responsive dashboard built with Tailwind CSS.
- **Post & Page Management:** Full lifecycle management including draft/publish statuses.
- **Media Manager:** Centralized handling of uploads and media assets.
- **User Roles:** Granular permission system (Administrator, Editor, Author, Contributor, Subscriber).
- **Customizable Appearance:** Dynamic color schemes and menu management.
- **SEO Friendly:** Built-in permalink structures and clean markup.

## System Requirements

- **PHP:** Version 7.4 or higher.
- **Database:** MySQL 5.7+ or MariaDB 10.2+.
- **Web Server:** Apache (with mod_rewrite enabled) or Nginx.
- **Extensions:** PHP PDO, PHP Zip (for plugin imports).

## Installation

Follow these steps to set up Orion CMS on your local or production environment.

1.  **Clone the Repository**
    Clone the project to your web server's document root.
    ```bash
    git clone https://github.com/diskonnekted/Orion-CMS.git
    ```

2.  **Database Configuration**
    - Create a new MySQL database for the project.
    - Rename `orion-config-sample.php` to `orion-config.php`.
    - Open `orion-config.php` and update the database connection details:
      ```php
      define('DB_NAME', 'your_database_name');
      define('DB_USER', 'your_database_user');
      define('DB_PASSWORD', 'your_database_password');
      define('DB_HOST', 'localhost');
      ```

3.  **Database Initialization**
    Import the provided schema to initialize the database tables. You can verify if an `install.php` or SQL dump is provided in the `orion-includes` or root directory, or ensure the application has permissions to create tables on first run (refer to specific setup scripts if available).

4.  **Access the Application**
    Open your web browser and navigate to your installation URL (e.g., `http://localhost/orion-cms`).

## Directory Structure

- **orion-admin/**: Contains the administrative backend files and logic.
- **orion-content/**: User-generated content and extensions.
    - **themes/**: Theme directories.
    - **plugins/**: Plugin directories.
    - **uploads/**: Media uploads.
- **orion-includes/**: Core library files, database abstraction, and helper functions.
- **assets/**: Static assets for the core system.

## Usage

### Admin Dashboard
Access the admin panel at `/orion-admin/`. Log in with your administrator credentials to manage content, users, and system settings.

### Managing Themes
Themes are located in `orion-content/themes/`. To activate a theme, navigate to **Appearance > Themes** in the admin dashboard.

### Managing Plugins
Plugins are located in `orion-content/plugins/`. To install a new plugin, navigate to **Plugins > Add New** and upload a compatible ZIP file.

## Contributing

Contributions are welcome. Please ensure your code follows the existing style guidelines and includes appropriate error handling.

1.  Fork the repository.
2.  Create a feature branch (`git checkout -b feature/AmazingFeature`).
3.  Commit your changes (`git commit -m 'Add some AmazingFeature'`).
4.  Push to the branch (`git push origin feature/AmazingFeature`).
5.  Open a Pull Request.

## License

This project is licensed under the MIT License - see the LICENSE file for details.
