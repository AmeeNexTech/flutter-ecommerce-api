# 🛒 E-commerce Backend (Laravel 12)

This project is the backend foundation for building a professional e-commerce platform using Laravel 12, featuring secure token-based authentication with Laravel Sanctum.

The goal is to develop a solid user management system that integrates seamlessly with a modern frontend, while being extendable for managing products and commercial features in future phases.

---

## ✅ Completed Features (Part 1)

### 🔐 Login and Logout
- Endpoints:
  - `POST /api/auth/login`
  - `POST /api/auth/logout`
- Authentication is handled via **Sanctum** using secure API tokens for each session.

---

### 🧾 User Registration
- `POST /api/auth/register`
- Includes OTP verification to confirm user identity.
- Related endpoints:
  - `POST /api/auth/verify-registration-otp`
  - `POST /api/auth/resend-otp`

---

### 🔄 Password Reset Flow
- A secure flow using OTP to recover accounts:
  - `POST /api/auth/forgot-password`
  - `POST /api/auth/verify-reset-otp`
  - `POST /api/auth/reset-password`
  - `POST /api/auth/resend-reset-otp`
- Supports storing the email and OTP temporarily in frontend for smooth UX within OTP validity duration.

---

### 🗑️ Delete Account
- `POST /api/auth/delete-account`
- Requires password confirmation before permanently deleting the account and invalidating all tokens.

---

## 🚀 Future Plans

### 🛍️ Product Management (Part 2)
A full-featured module for adding, editing, and displaying products will be developed in the next phase, with elegant UI/UX.

### 🖥️ Frontend Integration
Build a modern dynamic Flutter frontend app, featuring:
- Real-time validation
- Smart state management
- Smooth and responsive navigation

### 🛠️ Additional Features (Upcoming)
- User profile update
- Detailed account view
- Notifications and alerts
- Role-based access (Admin/User)

---

## ⚙️ Installation

```bash
# Install dependencies
composer install

# Set up the database
php artisan migrate

# Run the development server
php artisan serve
```

---

## 🤝 Contribution

Contributions are welcome!  
Feel free to open an issue or submit a pull request to enhance the project.

---

## 📄 License

This project is open-source and available for use under a suitable license (to be determined).
