# Symfony Base Login System

## Installation Guide

Follow the steps below to set up the project:

### 1. Clone the repository

```bash
git clone https://github.com/your-repository.git
cd your-repository
```

### 2. Set up the environment variables

- Edit the `.env` file to configure the database and mailer:

  ```bash
  # Database configuration
  DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/symfony_login?charset=utf8mb4"

  # Mailer configuration
  MAILER_DSN=smtp://username:password@smtp.example.com:587
  ```

### 3. Install dependencies

Run the following command to install all necessary packages:

```bash
composer install
```

### 4. Run migrations

If the project uses a database, run migrations to set up the necessary tables:

```bash
php bin/console doctrine:migrations:migrate
```

### 5. Build CSS (Optional)

If you make changes to the CSS, run the following command to rebuild the styles:

```bash
php bin/console tailwind:build
```

---

This guide should help you get the project up and running quickly. If you encounter any issues, please refer to the documentation or contact the maintainer.
