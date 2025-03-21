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

### **3. Run Migrations**

Once the Docker containers are up and running, execute the migrations to set up the database schema:

```bash
docker-compose exec <app-name> php artisan migrate
```

This command runs all pending migrations to set up your database tables and other necessary structures.

### **4. Run Artisan Command**

After running the migrations, you can seed the postcode database using the following command:

```bash
php artisan app:import-postcodes
```

This command add all the postcodes to the database, with spaces removed.


### **5. Rebuild the Docker Container**

If you need to rebuild the Docker container (e.g., after making changes to the Dockerfile), run:

```bash
./vendor/bin/sail build --no-cache
```

Then start the containers again:

```bash
./vendor/bin/sail up
```

### **6. Stop the application**

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

## Caveats & Improvements

While the current implementation is functional, there are several areas where improvements can be made to enhance the quality, performance, and maintainability of the codebase. Below are some suggestions for further development:

### 1. **Error Handling and Validation**
   - **Current State**: The application relies on basic validation to check for the existence of a postcode, but error handling can be more robust.
   - **Improvement**: Consider implementing custom error messages for validation failures, such as when a postcode doesn't exist or when a postcode query fails. This would improve the user experience and provide more meaningful feedback.
   - **Future Work**: Implementing try catch blocks around the database queries, particularly the `firstOrFail()` method, to handle any unexpected database errors gracefully.

### 2. **API Rate Limiting**
   - **Current State**: The API does not currently have any rate limiting to prevent abuse or overuse of the delivery query endpoint.
   - **Improvement**: Implement API rate limiting to control the number of requests a user can make in a specific time frame (e.g., 100 requests per minute). This would prevent excessive API calls and improve the overall stability of the system.
   - **Future Work**: Use Laravel's built-in rate limiting features (via `ThrottleRequests` middleware) to prevent spamming of requests.

### 3. **Caching for Postcode and Store Queries**
   - **Current State**: Each request for a postcode and store data results in a new database query. This can be inefficient when the same data is queried multiple times.
   - **Improvement**: Cache the postcode lookups and store queries using Laravel's caching features. This can reduce the number of database queries and improve response times, especially when dealing with large numbers of stores or frequent postcode lookups.
   - **Future Work**: Implement cache expiry policies to ensure that cached data is fresh and updated periodically, without becoming stale over time.

### 4. **Geospatial Indexing for Store Locations**
   - **Current State**: The distance calculation for determining which stores fall within the delivery radius uses raw SQL and basic trigonometric functions. This can become inefficient as the number of stores grows.
   - **Improvement**: Utilise geospatial indexes (like `ST_Distance` or `GeoJSON` support) to perform more efficient location-based queries. Many modern relational databases, such as MySQL and PostgreSQL, offer built-in support for geospatial queries, which can significantly improve performance.
   - **Future Work**: Investigate and implement geospatial indexing for the store's latitude and longitude fields to speed up distance-based queries.

### 5. **Unit Testing and Test Coverage**
   - **Current State**: The current tests mock the database queries effectively, but there is no explicit unit test for edge cases or for testing the exact distance calculation logic.
   - **Improvement**: Expand test coverage to include edge cases, such as when postcodes are at the boundary of the delivery radius, or when multiple stores are equidistant from the user's location.
   - **Future Work**: Add integration tests for different database backends (e.g., MySQL, PostgreSQL) to ensure compatibility and reliability across environments.

### 6. **Distance Calculation Accuracy**
   - **Current State**: The distance calculation relies on the **Haversine formula**, which provides a reasonable approximation for small distances. However, this might not be as accurate as required for larger delivery areas or specific use cases.
   - **Improvement**: Consider using more accurate geospatial algorithms or third-party services (e.g., Google Maps API or Mapbox) to calculate distances, especially for larger geographic areas. This would improve precision for delivery radius checks.
   - **Future Work**: Investigate other distance algorithms like **Vincenty** or **Great Circle Distance** for better accuracy, especially in applications with global reach.

### 7. **Scalability and Performance**
   - **Current State**: While the system works for small numbers of stores and postcodes, the performance may degrade as the number of stores and postcodes grows, especially in large-scale environments.
   - **Improvement**: Consider optimising the database schema (e.g., using indexes on relevant columns) and optimising the query performance. For large datasets, a more scalable solution like **Elasticsearch** or a **NoSQL database** may be appropriate for handling the delivery queries efficiently.
   - **Future Work**: Look into database partitioning, sharding, or other strategies for handling larger datasets, especially if your application is expected to scale significantly.

### 8. **Documentation**
   - **Current State**: While the code is relatively straightforward, documentation could be improved to explain the overall architecture, especially around the delivery calculation logic and the API endpoints.
   - **Improvement**: Add detailed comments and documentation on the distance calculation formula, how the delivery radius is determined, and any assumptions made in the logic.
   - **Future Work**: Provide more detailed API documentation, including response structures, error codes, and usage examples. Tools like **Swagger** or **Postman** can be used to automatically generate API documentation.

### 9. **Support for Multiple Delivery Radius Configurations**
   - **Current State**: The system supports a single delivery radius per store.
   - **Improvement**: Extend the system to support multiple delivery zones per store, e.g., offering different delivery distances for different times of day or for different products.
   - **Future Work**: Implement functionality where stores can have tiered delivery areas (e.g., free delivery within 5km, paid delivery up to 10km, etc.).
