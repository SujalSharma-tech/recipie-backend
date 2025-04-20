# Recipe Backend

## Overview

This project is a backend server for managing recipes, built with Laravel. It provides a comprehensive set of APIs for user authentication, recipe management, comments, ratings, and more. The application is designed to be scalable, secure, and easy to maintain.

## Features

- **User Management**: 
    - User registration and login using email and password.
    - Password reset functionality.
    - User profile management, including updating profile details.
    - Authentication using Laravel Sanctum for API token-based authentication.

- **Recipe Management**:
    - CRUD (Create, Read, Update, Delete) operations for recipes.
    - Search functionality to filter recipes by name, ingredients, or tags.
    - Pagination support for listing recipes.

- **Comments and Ratings**:
    - Users can leave comments on recipes.
    - Users can rate recipes on a scale of 1 to 5.
    - Average rating calculation for each recipe.

- **Database Support**:
    - PostgreSQL is used as the primary database.
    - Database migrations and seeders for easy setup.

- **Queue Management**:
    - Asynchronous task handling using Laravel Queues.
    - Example: Sending email notifications for user registration or password reset.

- **File Storage**:
    - Local file storage for recipe images and other assets.
    - Configurable storage options for production environments.

## Requirements

- PHP 8.0 or higher
- Composer
- PostgreSQL
- Node.js and npm

## Installation

1. Clone the repository:
        ```bash
        git clone https://github.com/your-repo/recipe-backend.git
        cd recipe-backend/server
        ```

2. Install dependencies:
        ```bash
        composer install
        npm install
        ```

3. Configure the environment:
        - Copy `.env.example` to `.env`:
            ```bash
            cp .env.example .env
            ```
        - Update the `.env` file with your database credentials, mail server settings, and other configurations.

4. Generate the application key:
        ```bash
        php artisan key:generate
        ```

5. Run database migrations:
        ```bash
        php artisan migrate
        ```

6. Start the development server:
        ```bash
        php artisan serve
        ```

## API Documentation

### Authentication Endpoints

1. **Register a new user**
     - **Endpoint**: `POST /api/register`
     - **Request Body**:
         ```json
         {
             "name": "John Doe",
             "email": "john@example.com",
             "password": "password",
             "password_confirmation": "password"
         }
         ```
     - **Response**:
         ```json
         {
             "message": "User registered successfully",
             "token": "your-auth-token"
         }
         ```

2. **Login**
     - **Endpoint**: `POST /api/login`
     - **Request Body**:
         ```json
         {
             "email": "john@example.com",
             "password": "password"
         }
         ```
     - **Response**:
         ```json
         {
             "token": "your-auth-token"
         }
         ```

3. **Logout**
     - **Endpoint**: `POST /api/logout`
     - **Headers**:
         ```
         Authorization: Bearer your-auth-token
         ```
     - **Response**:
         ```json
         {
             "message": "Logged out successfully"
         }
         ```

4. **Get User Profile**
     - **Endpoint**: `GET /api/user`
     - **Headers**:
         ```
         Authorization: Bearer your-auth-token
         ```
     - **Response**:
         ```json
         {
             "id": 1,
             "name": "John Doe",
             "email": "john@example.com",
             "created_at": "2023-01-01T00:00:00.000000Z"
         }
         ```

### Recipe Endpoints

1. **Create a Recipe**
     - **Endpoint**: `POST /api/recipes`
     - **Headers**:
         ```
         Authorization: Bearer your-auth-token
         ```
     - **Request Body**:
         ```json
         {
             "title": "Spaghetti Carbonara",
             "description": "A classic Italian pasta dish.",
             "ingredients": ["Spaghetti", "Eggs", "Pancetta", "Parmesan"],
             "steps": ["Boil pasta", "Cook pancetta", "Mix eggs and cheese", "Combine everything"],
             "tags": ["Italian", "Pasta"]
         }
         ```
     - **Response**:
         ```json
         {
             "message": "Recipe created successfully",
             "recipe": {
                 "id": 1,
                 "title": "Spaghetti Carbonara",
                 "description": "A classic Italian pasta dish."
             }
         }
         ```

2. **Get All Recipes**
     - **Endpoint**: `GET /api/recipes`
     - **Response**:
         ```json
         [
             {
                 "id": 1,
                 "title": "Spaghetti Carbonara",
                 "average_rating": 4.5
             },
             {
                 "id": 2,
                 "title": "Chicken Curry",
                 "average_rating": 4.8
             }
         ]
         ```

3. **Get a Single Recipe**
     - **Endpoint**: `GET /api/recipes/{id}`
     - **Response**:
         ```json
         {
             "id": 1,
             "title": "Spaghetti Carbonara",
             "description": "A classic Italian pasta dish.",
             "ingredients": ["Spaghetti", "Eggs", "Pancetta", "Parmesan"],
             "steps": ["Boil pasta", "Cook pancetta", "Mix eggs and cheese", "Combine everything"],
             "tags": ["Italian", "Pasta"],
             "comments": [
                 {
                     "user": "Jane Doe",
                     "comment": "Delicious recipe!",
                     "rating": 5
                 }
             ]
         }
         ```

4. **Update a Recipe**
     - **Endpoint**: `PUT /api/recipes/{id}`
     - **Headers**:
         ```
         Authorization: Bearer your-auth-token
         ```
     - **Request Body**:
         ```json
         {
             "title": "Updated Recipe Title"
         }
         ```
     - **Response**:
         ```json
         {
             "message": "Recipe updated successfully"
         }
         ```

5. **Delete a Recipe**
     - **Endpoint**: `DELETE /api/recipes/{id}`
     - **Headers**:
         ```
         Authorization: Bearer your-auth-token
         ```
     - **Response**:
         ```json
         {
             "message": "Recipe deleted successfully"
         }
         ```

### Comment and Rating Endpoints

1. **Add a Comment**
     - **Endpoint**: `POST /api/recipes/{id}/comments`
     - **Headers**:
         ```
         Authorization: Bearer your-auth-token
         ```
     - **Request Body**:
         ```json
         {
             "comment": "This recipe is amazing!",
             "rating": 5
         }
         ```
     - **Response**:
         ```json
         {
             "message": "Comment added successfully"
         }
         ```

2. **Get Comments for a Recipe**
     - **Endpoint**: `GET /api/recipes/{id}/comments`
     - **Response**:
         ```json
         [
             {
                 "user": "John Doe",
                 "comment": "Loved it!",
                 "rating": 5
             }
         ]
         ```

## Directory Structure

- **app/**: Contains the core application code, including models, controllers, and services.
- **database/**: Contains database migrations, seeders, and factories for testing.
- **public/**: Publicly accessible files, such as images and the entry point for the application.
- **resources/**: Contains views, CSS, and JavaScript assets.
- **routes/**: Defines API and web routes.
- **tests/**: Contains unit and feature tests for the application.

## Contributing

Contributions are welcome! Please fork the repository, create a feature branch, and submit a pull request.

## License

This project is licensed under the MIT License. See the `LICENSE` file for details.