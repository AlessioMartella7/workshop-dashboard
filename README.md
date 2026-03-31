# Workshop Management System (Laravel + Inertia + Vue)

A modern web application to manage company workshops, registrations, and employee participation, built with **Laravel 13**, **Inertia.js**, and **Vue.js**.

## 🚀 Features

- **Role-based Access Control**: Distinct interfaces for Admins (HR/Managers) and Employees.
- **Workshop Management**: Full CRUD for workshops (Title, Description, Date, Capacity).
- **Smart Registration**: One-click sign-up/cancel system with real-time seat availability tracking.
- **AI-Powered Copywriting**: Integrated Groq/Llama 3 AI assistant to help Admins generate professional workshop descriptions.
- **Responsive UI**: Built with Tailwind CSS and Vue 3.

---

## 🛠️ Tech Stack

- **Backend**: Laravel 13.x (PHP 8.2+)
- **Frontend**: Vue 3 (Composition API)
- **Bridge**: Inertia.js
- **Database**: MySQL/MariaDB
- **Tools**: Laravel Sail (Docker), Vite, Tailwind CSS, OpenAI PHP Client (for Groq AI).

---

## 📦 Installation & Setup

Follow these steps to get the project running locally using **Laravel Sail**.

### 1. Clone the repository
```bash
git clone <your-repo-url>
cd workshop-manager
```
### 2. Install Dependencies
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install
```
### 3. Environment Configuration
```bash
cp .env.example .env
```
Update .env with your Groq API Key to enable AI features:
```bash
GROQ_API_KEY=your_gsk_key_here
GROQ_BASE_URL=[https://api.groq.com/openai/v1](https://api.groq.com/openai/v1)
GROQ_MODEL=llama-3.1-8b-instant
```
### 4. Start the Application
```bash
./vendor/bin/sail up -d
```
### 5. Generate Application Key & Migrate
```bash
./vendor/bin/sail php artisan key:generate
./vendor/bin/sail php artisan migrate
```
### 6. Install Frontend Assets
```bash
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
```

---

### 🧪 Seeding Test Data
To quickly populate the database with a test Admin, an Employee, and several Workshops, run the seeders:
```bash
./vendor/bin/sail php artisan db:seed
```
Default Credentials:

Admin: admin@example.com / password

Employee: user@example.com / password

---

### 🚦 Running Tests
To ensure everything is working correctly, run the built-in test suite:
```bash
./vendor/bin/sail php artisan test
```

---

### 🤖 AI Integration Note
This project uses Groq (OpenAI compatible) to generate descriptions. If the AI button returns an error, ensure your GROQ_API_KEY is valid and the model specified in the WorkshopController is currently supported by the Groq API.
