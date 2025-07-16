# API Documentation - Authentication Endpoints

## ResponseHelper Improvements

The ResponseHelper has been improved with the following methods:

### Available Methods:
- `ResponseHelper::success($message, $data, $statusCode)` - Success response
- `ResponseHelper::error($message, $data, $statusCode)` - Error response  
- `ResponseHelper::validationError($message, $errors)` - Validation error (422)
- `ResponseHelper::unauthorized($message)` - Unauthorized (401)
- `ResponseHelper::notFound($message)` - Not found (404)
- `ResponseHelper::serverError($message)` - Server error (500)
- `ResponseHelper::jsonResponse($success, $message, $data, $statusCode)` - General response

## Authentication Endpoints

### 1. User Registration
**Endpoint:** `POST /api/register`

**Request Body:**
```json
{
    "name": "John Doe",
    "email": "john@example.com", 
    "password": "password123",
    "phone": "08123456789" // optional
}
```

**Response (201):**
```json
{
    "success": true,
    "message": "User registered successfully",
    "data": {
        "token": "1|abcdef...",
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "phone": "08123456789",
            "qr_code": "ABCD123456",
            "qr_image_url": "/storage/qr_codes/ABCD123456.png",
            "role": "user",
            "points": 0
        }
    }
}
```

### 2. User Login
**Endpoint:** `POST /api/login`

**Request Body:**
```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

**Response (200):**
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "token": "2|xyz789...",
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com", 
            "phone": "08123456789",
            "qr_code": "ABCD123456",
            "qr_image_url": "/storage/qr_codes/ABCD123456.png",
            "role": "user",
            "points": 0
        }
    }
}
```

### 3. User Logout
**Endpoint:** `POST /api/logout`

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
    "success": true,
    "message": "Successfully logged out",
    "data": null
}
```

### 4. Get User Profile
**Endpoint:** `GET /api/profile`

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
    "success": true,
    "message": "User profile retrieved successfully",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "phone": "08123456789", 
            "qr_code": "ABCD123456",
            "qr_image_url": "/storage/qr_codes/ABCD123456.png",
            "role": "user",
            "points": 0
        }
    }
}
```

## QR Code Generation

Upon registration, the system automatically:
1. Generates a unique 10-character QR code
2. Creates a PNG image (300x300px) of the QR code
3. Stores it in `storage/app/public/qr_codes/`
4. Returns the accessible URL via `qr_image_url` field

## Error Responses

**Validation Error (422):**
```json
{
    "success": false,
    "message": "Validation failed",
    "data": {
        "email": ["The email field is required."],
        "password": ["The password field is required."]
    }
}
```

**Unauthorized (401):**
```json
{
    "success": false,
    "message": "Invalid credentials",
    "data": null
}
```

**Server Error (500):**
```json
{
    "success": false,
    "message": "Registration failed: Database connection error",
    "data": null
}
```

## Database Changes

Added `qr_image_url` field to the users table to store the QR code image URL.

## Required Setup

1. Make sure Laravel Sanctum is installed: `composer require laravel/sanctum`
2. Make sure QR Code package is installed: `composer require simplesoftwareio/simple-qrcode` 
3. Create storage link: `php artisan storage:link`
4. Run migrations: `php artisan migrate`
