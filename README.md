# Task Management Application

A simple PHP-based task management application.

## Getting Started

1. **Clone the repository:**

    ```shell
    git clone git@github.com:Fabdoc27/Task-Manager.git
    cd Task-Manager
    ```

2. **Configure the database:**

    Update `config.php` with your database credentials if necessary.

3. **Import the database:**

    Create a `todo` database and a `tasks` table:

    ```sql
    CREATE DATABASE todo;
    USE todo;
    CREATE TABLE tasks (
        id INT AUTO_INCREMENT PRIMARY KEY,
        task VARCHAR(255) NOT NULL,
        date DATE NOT NULL,
        complete TINYINT(1) DEFAULT 0
    );
    ```

4. **Run the project:**

    Start the PHP development server:

    ```shell
    php -S localhost:8080
    ```

Open your browser and navigate to [http://localhost:8080](http://localhost:8080).
