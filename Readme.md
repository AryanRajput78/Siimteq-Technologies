_Task_

You are tasked with building a secure user authentication system in PHP. This system should allow users to register, login, and reset their passwords securely. It should also include the following security features:

_Features_

1. Password Hashing: User passwords should be securely hashed and stored in the database. You can use PHP's password_hash() function for this.
2. Password Salting: Implement password salting to protect against rainbow table attacks. Use a unique salt for each user.
3. User Registration: Users should be able to register by providing a username and password. Validate the input data to ensure it meets security standards (e.g., strong passwords, no SQL injection vulnerabilities).
4. User Login: Registered users should be able to log in with their username and password.
5. Session Management: Implement secure session management to keep users authenticated across different pages of the website.
6. Password Reset: Allow users to reset their passwords if they forget them. This should involve sending a secure email with a reset link.
7. Protection Against Brute Force Attacks: Implement protection mechanisms to prevent brute force attacks on the login page, such as rate limiting or CAPTCHA.
8. Secure Database: Ensure that the database connection is secure and that user inputs are properly sanitized to prevent SQL injection.
9. Logging: Implement logging of login attempts, password reset requests, and other relevant security events for auditing purposes.

_File Structure_

- `assets`: Static file storage. (e.g. images, javascript files, stylesheets etc.)
- `includes`:
- `templates`: Store reusable files in templates.
- `pages`: Scripts for different sections of the site.
- `logs`: For storing log files.
