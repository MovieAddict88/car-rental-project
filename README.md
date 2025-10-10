# Car Rental Management System (CRMS)

## About The Project

The Car Rental Management System (CRMS) is a full-stack PHP and MySQL application designed to provide a complete solution for managing a car rental business. It features a public-facing website for customers to browse and book cars, a secure user dashboard for managing bookings, and a comprehensive admin panel for managing the entire system.

### Built With

*   PHP (OOP/MVC)
*   MySQL
*   Bootstrap 5
*   FPDF for PDF generation
*   PHPMailer for email notifications

## Getting Started

To get a local copy up and running, follow these simple steps.

### Prerequisites

*   PHP 8.0 or higher
*   MySQL
*   A web server (like Apache or Nginx)

### Installation

1.  **Clone the repo**
    ```sh
    git clone https://github.com/your_username/your_repository.git
    ```

2.  **Install Dependencies (FPDF & PHPMailer)**

    This project uses FPDF and PHPMailer for some of its advanced features. These libraries need to be installed manually.

    *   **FPDF:**
        1.  Download the latest version of FPDF from [fpdf.org](http://www.fpdf.org/en/download.php).
        2.  Create a directory `assets/vendor/fpdf`.
        3.  Extract the contents of the downloaded zip file into the `assets/vendor/fpdf` directory.

    *   **PHPMailer:**
        1.  Download the latest version of PHPMailer from its [GitHub repository](https://github.com/PHPMailer/PHPMailer).
        2.  Create a directory `assets/vendor/PHPMailer`.
        3.  Extract the contents of the downloaded zip file into the `assets/vendor/PHPMailer` directory. Make sure the final path looks like `assets/vendor/PHPMailer/src/PHPMailer.php`.

3.  **Database Setup & Installation**
    1.  Navigate to the `install` directory in your browser (e.g., `http://localhost/crms/install/setup.php`).
    2.  Fill in your database credentials (host, username, password, and desired database name). The installer will create the database for you.
    3.  Set up your admin account by providing an email and password.
    4.  Click "Install Now". The installer will create the database tables and generate the `config/config.php` file.

4.  **Ready to Go!**
    Once the installation is complete, you will be redirected to the homepage. You can log in with your new admin credentials to start managing the system.

## Features

### User Side
*   Browse and search for cars.
*   View car details, including photos and pricing.
*   Register and log in.
*   Book a car for a specified date range.
*   View booking history and status.
*   Download PDF invoices for confirmed bookings.
*   Submit feedback.

### Admin Side
*   Secure login and dashboard with system statistics.
*   Manage cars (Add, Edit, Delete, Update availability).
*   Manage users (View, Suspend, Delete).
*   Manage bookings (Approve, Reject, Mark as Completed).
*   Manage payments and view transaction history.
*   Generate reports on bookings and export to CSV.
*   View and manage user feedback.
*   Configure system settings, including site details and SMTP credentials.