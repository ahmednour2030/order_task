<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Enterprise-Grade Laravel Order & Payment System with JWT Security in Dockerized Architecture

> Production-ready Laravel application with JWT authentication, fully Dockerized for development and production.

---

> All API endpoints are provided with a ready-to-use Postman collection inside the project
> 
> ```bash
> postman-collection/OrderTask.postman_collection.json


---
## Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Environment Variables](#environment-variables)
- [Docker Setup](#docker-setup)
- [Running the Application](#running-the-application)
- [Database Migration & Seeding](#database-migration--seeding)
- [API Authentication](#api-authentication)
- [Testing](#testing)
- [Contributing](#contributing)
- [License](#license)

---

## Overview

This is a modern Laravel application designed with JWT authentication and fully containerized using Docker. The project is optimized for scalability, maintainability, and rapid deployment. It provides a clean API structure with secure authentication mechanisms and can be easily integrated with frontend frameworks like React or Vue.js.

---

## Features

- JWT-based authentication
- RESTful API endpoints
- Role-based access control (RBAC)
- Dockerized for consistent development and production
- Environment-based configuration
- Database migrations and seeding
- Easy integration with CI/CD pipelines

---

## Tech Stack

- **Backend:** Laravel 12 (PHP 8.2)
- **Database:** MySQL 8 / PostgreSQL (configurable)
- **Cache:** Redis (optional)
- **Containerization:** Docker & Docker Compose
- **Authentication:** JWT via `tymon/jwt-auth` package
- **Queue / Jobs:** Laravel queues with Redis (optional)

---

## Prerequisites

Make sure the following are installed:

```bash
# Docker
docker --version

# Docker Compose
docker-compose --version

# Git
git --version

# PHP (optional if not using Docker)
php -v

# Composer (optional if not using Docker)
composer --version

````

``` bash
git clone git@github.com:ahmednour2030/order_task.git

OR

git clone https://github.com/ahmednour2030/order_task.git

cd order_task
```

``` bash
cp .env.example .env
```

``` bash
docker-compose up -d --build
```
``` bash
docker-compose exec php composer install

docker-compose exec php php artisan key:generate

docker-compose exec php php artisan jwt:secret
```

``` bash
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=laravel
```
``` bash
docker-compose exec app php artisan migrate --seed
```

``` bash
docker-compose exec php php artisan test
```

