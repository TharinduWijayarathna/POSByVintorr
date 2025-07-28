
# SparkPOS

SparkPOS is a powerful and user-friendly POS and invoicing system built with Laravel Inertia and Vue.js. This system is designed to simplify point-of-sale operations and invoicing for businesses.

## Features

- **Laravel Inertia Integration**: Provides a seamless single-page application experience.
- **Vue.js Frontend**: Interactive and responsive UI.
- **Laravel Telescope & Pulse**: Tools for monitoring and testing the system.
- **Scheduled Tasks**: Essential background tasks to ensure smooth operation.

## Prerequisites

- PHP 8.1 or higher
- Composer
- Node.js 20 or higher
- MySQL or compatible database

## Installation

1. **Clone the Repository**

   ```bash
   git clone https://github.com/Emergent-Spark/SparkPOS.git
   cd sparkpos
   ```

2. **Install Dependencies**

   Run the following commands to install the backend and frontend dependencies:

   ```bash
   composer install
   npm install
   ```

3. **Run the System**

   To start the development server, use the command:

   ```bash
   composer dev
   ```

   Alternatively, you can manually run the backend and frontend:

   ```bash
   php artisan serve
   npm run dev
   ```

4. **Set Up the Environment**

   Copy the `.env.example` file and configure your environment variables:

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Migrate the Database**

   ```bash
   php artisan migrate
   ```

## Scheduled Tasks

SparkPOS includes several scheduled tasks crucial for its operation. Ensure you set up the Laravel Scheduler to run these tasks:

```bash
php artisan schedule:work
```

## Monitoring and Testing

This project uses Laravel Telescope and Pulse for debugging and monitoring.

- **Pulse Check**: Run the following command to ensure the system's health:

  ```bash
  php artisan pulse:check
  ```

- **Access Telescope**: Navigate to `/telescope` on your application URL to access Laravel Telescope.

- **Access Pulse**: Navigate to `/pulse` on your application URL to access the Pulse dashboard.
