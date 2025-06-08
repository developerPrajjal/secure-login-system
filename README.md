
# 🔐 Secure PHP Login System with OTP & reCAPTCHA

A robust and secure PHP login system implementing modern best practices including:

- User registration with secure password hashing (bcrypt)
- Login with brute-force protection (lockout after multiple failed attempts)
- Google reCAPTCHA v2 integration to block bots and spam
- Two-factor authentication (2FA) with OTP (One-Time Password) verification
- Session management with proper logout support
- Clean, responsive UI with consistent styling for login and registration forms

---

## 🚀 Features

| Feature                         | Description                                                    |
| -------------------------------|---------------------------------------------------------------|
| **Secure Registration**         | Passwords hashed using PHP’s `password_hash()` (bcrypt)       |
| **Login Protection**            | Limits login attempts, blocks after 5 failed tries for 5 mins |
| **Google reCAPTCHA v2**         | Prevents automated login attempts and spam                    |
| **OTP 2FA Verification**        | Adds an extra security layer by requiring a one-time OTP      |
| **Session Handling**            | Manages user sessions securely with session start and destroy |
| **User Feedback**               | Clear and concise error/success messages                       |
| **Responsive UI**               | Modern, clean design with CSS styles for usability             |

---

## 📋 Requirements

- PHP 7.4+ (with `mysqli` extension)
- MySQL or MariaDB
- Apache or any web server (XAMPP recommended for local dev)
- Composer (optional, if you extend with libraries)
- Google reCAPTCHA v2 keys (site key and secret key)
- Mail server configured for OTP email (or adapt to use APIs like SendGrid)

---

## 🛠️ Installation & Setup

### 1. Clone the repository

```bash
git clone https://github.com/your-username/secure-login-system.git
cd secure-login-system
```

2. Setup the database
Create a MySQL database, e.g., secure_login_db.

Run the following SQL to create the users table:

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  failed_attempts INT DEFAULT 0,
  last_attempt DATETIME DEFAULT NULL
);

3. Configure database connection
Edit db.php and update the following constants:

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "secure_login_db";

4. Setup Google reCAPTCHA
Register your site at [Google reCAPTCHA Admin](https://www.google.com/recaptcha/admin)

Update the keys in:

login.php — Replace data-sitekey attribute value.

recaptcha_verify.php — Replace the secret key in the verification request.

5. Configure mail for OTP
By default, the system uses PHP’s mail() function to send OTP to a placeholder email.

For development, you can output OTP to logs or screen.

For production, configure SMTP or use an email API for reliable delivery.

6. Run the project
Place the project folder inside your web server root (e.g., htdocs for XAMPP).

Start Apache and MySQL.

Open browser: http://localhost/secure-login-system/login.php


🧭 Usage Guide
Page                                	Purpose
register.php	                Register new users
login.php        	            Login with username & password
otp.php	                      Verify the OTP sent to email
dashboard.php	                User landing page after login
logout.php	                  Logout user and destroy session

🔧 How It Works
User registers with a unique username and a strong password.

Password is hashed with password_hash() and stored securely.

User attempts login with credentials and completes CAPTCHA.

After successful password verification, an OTP is generated and emailed.

User enters OTP on otp.php for two-factor authentication.

On successful OTP verification, user is redirected to the dashboard.

Failed login attempts are counted and lockout is applied after 5 failures for 5 minutes.

Sessions manage user login state securely.

⚙️ Folder Structure
secure-login-system/
├── assets/
│   └── style.css             # CSS styles for the UI
├── db.php                   # Database connection script
├── login.php                # Login page and processing
├── logout.php               # Logout script
├── otp.php                  # OTP verification page
├── register.php             # User registration page
├── recaptcha_verify.php     # Google reCAPTCHA verification helper
├── dashboard.php            # User dashboard (protected)
├── log_failed_login.php     # Optional failed login logging
└── README.md                # This documentation


📌 Security Considerations
Passwords use PHP's strong hashing (bcrypt).

Brute-force attacks are mitigated by login attempt limits.

Google reCAPTCHA prevents bot abuse.

OTP adds a second authentication factor.

Always serve over HTTPS in production.

Keep sensitive keys and configs out of version control or use environment variables.

📫 Contact / Support
If you have any questions, issues, or feedback, feel free to open an issue or contact me:

Email: [Email](prajjal.tech@gmail.com)

GitHub: @developerPrajjal




