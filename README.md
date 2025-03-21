# Store Locator

## Introduction

This project is a simple store locator built with **Laravel**. It allows users to find stores near a given postcode or determine if a store can deliver to a specific postcode.

---

## Getting Started

Follow the steps below to get the application up and running on your local machine.

### **1. Install Dependencies**

Run the following command to install all required dependencies:

```bash
composer install

```

### **2. Start the Application**

The application uses Laravel Sail for containerisation. To start the app, use the following command:

```bash
./vendor/bin/sail up
```

To run in detached mode (in the background), use the -d flag:

```bash
./vendor/bin/sail up -d
```

### **3. Rebuild the Docker Container**

If you need to rebuild the Docker container (e.g., after making changes to the Dockerfile), run:

```bash
./vendor/bin/sail build --no-cache
```

Then start the containers again:

```bash
./vendor/bin/sail up
```

### **4. Stop the application**

```bash
docker compose down -v
```


## Running PHPStan
PHPStan is configured to run at level 6 to ensure high code quality and adherence to best practices.

To run PHPStan and analyse the code, execute:

```bash
./vendor/bin/phpstan analyse
```

If you want to run PHPStan on a specific file, you can specify the path:

```bash
./vendor/bin/phpstan analyse app/Http/Controllers/StoreController.php
```

## Caveats & Improvements
While the current implementation is functional, there are several areas where improvements can be made to enhance the quality, performance, and maintainability of the codebase. Below are some suggestions for further development: