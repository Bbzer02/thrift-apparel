# Thrift Apparel Shop

Laravel storefront for a thrift apparel business: public landing and hero slides, product browsing by category, shopping-style flows, orders, and sign-in for shoppers/vendors. Frontend uses Blade templates with Vite-built CSS and JavaScript.

## Features

- Marketing landing page with configurable hero imagery  
- Shop catalog, items, and category-aware listings  
- User accounts and order-related models for checkout workflows  
- Admin-oriented controllers for catalog and landing content  

## Stack

- **PHP** 8.2+, **Laravel**, **Vite**, **MySQL** (or your chosen DB)

## Local setup

1. Install PHP, Composer, and Node.js.  
2. `composer install`  
3. Copy `.env.example` to `.env`, set `APP_NAME`, database credentials, and mail if needed.  
4. `php artisan key:generate`  
5. `php artisan migrate`  
6. `npm install && npm run build` (or `npm run dev` while developing)  
7. `php artisan serve`

## Environment

Do not commit `.env`. Copy from `.env.example` and adjust for your environment.

## License

Application code in this repository is provided as-is for the project. Laravel and third-party packages remain under their respective licenses.
