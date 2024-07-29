<h1 style="color: #ff9200; font-size: 50px; font-weight: bold">
  🚀 Todo App built with Next.js with Laravel
</h1>

<div>
  <img src="https://img.shields.io/badge/PHP-8.2-blue" alt="PHP 8.2">
  <img src="https://img.shields.io/badge/Laravel-11-red" alt="Laravel 11">
  <img src="https://img.shields.io/badge/React-18-blue" alt="React 18.0.0">
  <img src="https://img.shields.io/badge/Next.js-14.0.0-blue" alt="Next.js 14.0.0">
  <img src="https://img.shields.io/badge/Tailwind CSS-3.4.7-blue" alt="Tailwind CSS 3.4.7">
<div>

# Description 📋

Finally organize your work and life with QuickTask.

# Table of contents

-   [Description 📋](#description-)
    -   [Table of contents](#table-of-contents)
    -   [Starting 🚀](#starting-)
        -   [Previous requirements 📋](#previous-requirements-)
        -   [Installation 🔧](#installation-)
        -   [Environment Variables](#environment-variables)
        -   [Use 📌](#use-)
    -   [Built with 🛠️](#built-with-)

# Starting 🚀

## Previous requirements 📋

### Tools

-   [Git](https://git-scm.com/)
-   [PHP-8](https://www.php.net/downloads.php)
-   [Node.js](https://nodejs.org/)
-   [Composer](https://getcomposer.org/)

### Installation 🔧

Local installation:

```bash
# Clone this repository
# linux (ubuntu) /var/www/html/
# windows:
#  - for laragon in www folder
#  - for xammp in htdocs folder
$ https://github.com/JefferGonzalez/todo-app-api.git

# Change directory to the project path
$ cd todo-app-api
```

Setup:

```bash
# Install dependencies
$ composer install
```

### .env file setup

```bash
Create an .env file and copy all content of .env.example

Then update .env file with you database credentials
```

## Use 📌

### Migrate the database

```bash
# Migrate the database
$ php artisan migrate
```

### Start the server

```bash
# Start the server
$ php artisan serve
```

Open your browser and go to the url of your project

```bash
http://localhost:8000
```

## Built with 🛠️

-   [Apache](https://www.apache.org/) - Web Server
-   [PHP](https://www.php.net/) - Programming Language
    -   [Laravel](https://laravel.com/) - PHP Framework
    -   [Composer](https://getcomposer.org/) - Dependency Management for PHP
-   [REACT.JS](https://beta.reactjs.org/) - Frontend Library
    -   [Next.js](https://nextjs.org/) - React Framework
    -   [Tailwind CSS](https://tailwindcss.com/) - CSS Framework
