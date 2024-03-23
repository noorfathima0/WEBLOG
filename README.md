#WEBLOG 
WEBLOG is a web application that allows users to create, manage, and organize their journal entries online. It provides a user-friendly interface for users to write, edit, and view their entries, as well as manage their account settings.

## Features

- User Authentication: Users can register for an account and securely log in using their username and password.
- Journal Entry Management: Users can create, edit, and delete journal entries.
- Account Settings: Users can update their profile information, including username, email, bio, and profile picture.
- Dashboard: Provides an overview of the user's journal entries, statistics, and quick links to frequently used features.
- Basic Text Formatting: Users can format their journal entries with basic formatting options such as bold, italic, and underline.
- Entry Preview: Users can preview their journal entries before saving or publishing them.
- Simple Tagging: Users can add simple tags or labels to their journal entries for categorization purposes.

## Installation

1. Clone the repository:
    git clone <repository-url>

2. Navigate to the project directory:
    cd WEBLOG


3. Set up the database:
   - Create a MySQL database named `weblogdb`.
   - Import the SQL dump file `weblogdb.sql` located in the `database` directory to set up the database schema.

4. Configure the database connection:
   - Update the database connection settings in the `includes/db.php` file with your MySQL database credentials.

5. Run the application:
   - Start your local server (e.g., Apache, Nginx).
   - Open the application in your web browser by navigating to `http://localhost/weblog`.

## Usage

- Register for a new account or log in with your existing credentials.
- Explore the dashboard to manage your journal entries and account settings.
- Create new journal entries, edit existing ones, and customize your account.
- Enjoy journaling online with ease!

## Contributing

Contributions are welcome! If you encounter any issues or have suggestions for improvements, please feel free to open an issue or submit a pull request.

