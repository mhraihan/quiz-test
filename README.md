# Quiz Test - Laravel Quiz App

Quiz Test is a quiz application built with Laravel, allowing users to take quizzes and receive instant feedback on their answers. The application is designed to be easy to set up and customize, making it suitable for various quiz-based projects.

## Features

- User registration and authentication system
- Quiz creation with multiple-choice questions
- Dynamic quiz scoring and immediate feedback
- Admin panel for managing quizzes and users
- Responsive design for seamless user experience on different devices
- Flexible and customizable codebase

## Installation

1. Clone the repository to your local machine:

   ```shell
   git clone https://github.com/mhraihan/quiz-test.git
   ```

2. Navigate to the project directory:

   ```shell
   cd quiz-test
   ```

3. Install the dependencies using Composer:

   ```shell
   composer install
   ```

4. Create a copy of the `.env.example` file and rename it to `.env`. Update the necessary configuration values in the `.env` file, such as database credentials.

5. Generate an application key:

   ```shell
   php artisan key:generate
   ```

6. Run the database migrations and seed the database:

   ```shell
   php artisan migrate --seed
   ```

7. Start the local development server:

   ```shell
   php artisan serve
   ```

   You can now access the application by visiting `http://localhost:8000` in your web browser.

## Usage

- Register a new user account or log in with existing credentials.
- Navigate to the "Quizzes" section to view and attempt available quizzes.
- Click on a quiz to start taking it. Answer the questions and submit your answers.
- After submitting, you will receive instant feedback on your quiz performance.
- As an admin user, you can create new quizzes, manage existing quizzes, and view user results in the admin panel.

## Customization

- To customize the application, you can modify the views, styles, and scripts located in the `resources` directory.
- The routes, controllers, and models can be adjusted to fit your specific requirements in the `app` directory.
- For more advanced customization, refer to the official Laravel documentation at [https://laravel.com/docs](https://laravel.com/docs).

## Contributing

Contributions are welcome! If you find any issues or have suggestions for improvement, please submit an issue or create a pull request. Make sure to follow the existing coding style and conventions.

## License

This project is open-source and available under the [MIT License](https://opensource.org/licenses/MIT). Feel free to use, modify, and distribute the code as needed.

## Acknowledgements

- The Laravel framework: [https://laravel.com](https://laravel.com)

## Contact

For any inquiries or support, please contact the project maintainer:

MH Raihan

Email: me@mhraihan.com

GitHub: [https://github.com/mhraihan](https://github.com/mhraihan)

---

Feel free to customize the README file according to your specific project details and requirements. Remember to update the installation instructions, usage guidelines, and any other relevant information.
