# nationalhunt_php

This repository contains a PHP contact form script that allows users to submit their information along with a message through a web form. The script performs input validation, reCAPTCHA verification to avoid spam entries, and sends email notifications upon successful submission.

## Table of Contents
- [Description](#description)
- [Features](#features)
- [Prerequisites](#prerequisites)
- [setup](#setup)
- [Usage](#usage)
- [FormValidation](#formvalidation)
- [Troubleshooting](#Troubleshooting)
- [Contributing](#contributing)
- [License](#license)
- [Note](#Note)
 
### Description
This PHP script allows users to submit a form with their name, phone number, email, subject, and optional message.There ia a client who owns a saloon and her competitors are using bots for spam entries so i have performed validation on email so that user cannot provide duplicate email and she wouldn't get duplicate and wrong entries. The script performs validation on the form data and inserts the information into a MySQL database if it passes validation. It then sends an email notification using the PHPMailer library to the user and a designated recipient.

#### Features
- User-friendly web form for submitting contact information.
- Input validation for name, phone number, email, and subject fields.
- Integration with reCAPTCHA to prevent spam submissions.
- Sending email notifications to the user and the website owner upon successful submission.
- Avoiding duplicate email entries in the database.

#### Prerequisites
- A web server with PHP support (e.g., Apache, Nginx).
- A MySQL database.
- PHPMailer library (`composer require phpmailer/phpmailer`).

#### setup
- Clone this repository to your local machine or server.
- Make sure you have Composer installed.
- Run composer install to install the required dependencies.
- Obtain reCAPTCHA API keys:
- Sign up for reCAPTCHA: https://www.google.com/recaptcha
- Replace '6LfOI-MnAAAAAIvnqhtJUh5dTcoEs9XoPT_DgY1i' with your reCAPTCHA Site Key in 
  contact_form.php.
- Replace '6LfOI-MnAAAAABArP6Z-4cQhlchC-2zMUG9X5U-h' with your reCAPTCHA Secret Key in 
  contact_form.php.
- Configure email settings in contact_form.php:
- Update SMTP server settings (Host, Username, Password, etc.).
- Set sender and recipient email addresses.

#### Usage
- Users can access the contact form by visiting contact_form.php in a web browser.
- Fill in the required fields: Name, Phone Number, Email, and Subject.
- Optionally provide a Message.
- Complete the reCAPTCHA challenge.
- Click the "Submit" button.
- If successful, the user will be redirected to thank_you.php.

####  FormValidation
Name: Accepts only alphabetic characters and white spaces.
Phone Number: Must start with 7, 8, or 9, followed by 9 digits.
Email: Duplicate amils are not allowed.Must be a valid email format.
Subject: Cannot be empty.
Message: Optional field for additional information.

##### Troubleshooting
If the form is not submitting or sending emails, check your SMTP settings and ensure your email provider allows sending emails from scripts.
If you encounter any issues with validation, review the regular expressions used in the script.

#### Contributing
Contributions are welcome! If you find any bugs or want to improve the script, feel free to open an issue or submit a pull request.

#### License
This project is licensed under the MIT License.

#### Note
- The script provided serves as a basic example. Additional security measures and error handling could be implemented for a production environment.
- This script assumes that you're using a Gmail SMTP server for sending emails. If you're using a different email provider, update the SMTP settings accordingly.
- Remember to replace the placeholders (like version numbers, your name, and specific details) with actual information. Providing clear instructions and information in your README file will make it easier for others to understand and use your PHP form submission and validation script.



