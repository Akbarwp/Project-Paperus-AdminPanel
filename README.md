# Paperus Backend

Paperus is a website designed to showcase company profiles and facilitate online product sales. Utilizing Laravel, Laravel Breeze, Filament, and Livewire, this site offers a modern and responsive interface, allowing users to easily access company information and explore and purchase the products offered. These features support a smooth and efficient user experience when interacting with the company. [Paperus Frontend](https://github.com/Akbarwp/Project-Paperus-ECommerce) can be accessed at the following link.

## Tech Stack

- **Laravel 8**
- **MySQL Database**
- **Filament 2**

## Features

- Main features available in this application:
  - CRUD Product & stock
  - CRUD Product complement (Materials, Finishing, Categories)
  - Sales Management
  - CRUD Employees & Class
  - CRUD User
  - Generate reports

## Installation

Follow the steps below to clone and run the project in your local environment:

1. Clone repository:

    ```bash
    git clone https://github.com/Akbarwp/Project-Paperus-AdminPanel.git
    ```

2. Install dependencies use Composer and NPM:

    ```bash
    composer install
    npm install
    ```

3. Copy file `.env.example` to `.env`:

    ```bash
    cp .env.example .env
    ```

4. Generate application key:

    ```bash
    php artisan key:generate
    ```

5. Setup database in the `.env` file:

    ```plaintext
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=database_name
    DB_USERNAME=root
    DB_PASSWORD=
    ```

6. Run migration database:

    ```bash
    php artisan migrate
    ```

7. Run seeder database:

    ```bash
    php artisan db:seed
    ```

8. Run website:

    ```bash
    npm run watch
    php artisan serve
    ```

## Screenshot

- ### **Product page**

<img src="https://github.com/user-attachments/assets/5330a937-115e-49ed-9821-a95a98434014" alt="Halaman Produk" width="" />
&nbsp;&nbsp;&nbsp;
<img src="https://github.com/user-attachments/assets/b24af32e-5534-47c5-97f4-19c54cffdd0f" alt="Halaman Tambah Produk" width="" />
<br><br>

- ### **Restock page**

<img src="https://github.com/user-attachments/assets/691caef0-930a-4749-bb25-99e5fa68bf12" alt="Halaman Restok" width="" />
<br><br>

- ### **Sales page**

<img src="https://github.com/user-attachments/assets/e4efc90c-94c0-47cc-80dd-0d35ef34eb39" alt="Halaman Sales" width="" />
&nbsp;&nbsp;&nbsp;
<img src="https://github.com/user-attachments/assets/dc529f61-773f-400b-8bd9-ae13ec55ae31" alt="Halaman Detail Sales" width="" />
<br><br>

- ### **Employee page**

<img src="https://github.com/user-attachments/assets/0b1b991b-aab9-4362-be4c-60fc20984a21" alt="Halaman Pegawai" width="" />
<br><br>

- ### **User page**

<img src="https://github.com/user-attachments/assets/9971145d-f175-47d7-b453-5051b97489c9" alt="Halaman User" width="" />
&nbsp;&nbsp;&nbsp;
<img src="https://github.com/user-attachments/assets/765139b2-1b1c-472e-ba57-283b9984e18d" alt="Halaman Detail User" width="" />
<br><br>

- ### **Report page**

<img src="https://github.com/user-attachments/assets/ebb7547b-3326-4240-a06b-fedf488b67da" alt="Halaman Laporan" width="" />
&nbsp;&nbsp;&nbsp;
<img src="https://github.com/user-attachments/assets/34f5d25c-b38f-4551-a033-2a2f5f5104c3" alt="Laporan Penjualan" width="" />
<br><br>
