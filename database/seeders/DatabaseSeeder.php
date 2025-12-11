<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Prompt;
use App\Models\Answer;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Sample Q&A pairs for testing
        $qaData = [
            [
                'question' => 'What is Laravel?',
                'answer' => 'Laravel is a free, open-source PHP web application framework with expressive, elegant syntax. It follows the MVC architectural pattern and provides features like routing, authentication, sessions, caching, and more.'
            ],
            [
                'question' => 'How do I install Laravel?',
                'answer' => 'You can install Laravel using Composer by running: composer create-project laravel/laravel your-project-name. Alternatively, you can use the Laravel installer: composer global require laravel/installer, then laravel new your-project-name.'
            ],
            [
                'question' => 'What is the difference between GET and POST?',
                'answer' => 'GET is used to request data from a server and parameters are visible in the URL. POST is used to send data to a server and parameters are included in the request body, making it more secure for sensitive information.'
            ],
            [
                'question' => 'How do I connect to a database in Laravel?',
                'answer' => 'Configure your database connection in the .env file with DB_CONNECTION, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, and DB_PASSWORD. Laravel supports MySQL, PostgreSQL, SQLite, and SQL Server out of the box.'
            ],
            [
                'question' => 'What is Eloquent ORM?',
                'answer' => 'Eloquent ORM is Laravel\'s built-in Object-Relational Mapping system that provides a simple ActiveRecord implementation for working with your database. Each database table has a corresponding Model which is used to interact with that table.'
            ],
            [
                'question' => 'How do I create a migration in Laravel?',
                'answer' => 'Use the Artisan command: php artisan make:migration create_table_name. This creates a new migration file in database/migrations. Edit the up() method to define your table structure, then run php artisan migrate to execute it.'
            ],
            [
                'question' => 'What is middleware in Laravel?',
                'answer' => 'Middleware provides a convenient mechanism for inspecting and filtering HTTP requests entering your application. For example, authentication middleware verifies that the user is authenticated before allowing access to certain routes.'
            ],
            [
                'question' => 'How do I create a controller?',
                'answer' => 'Use the Artisan command: php artisan make:controller ControllerName. This creates a new controller in app/Http/Controllers. You can add methods to handle different routes and logic.'
            ],
            [
                'question' => 'What is Blade templating engine?',
                'answer' => 'Blade is Laravel\'s simple yet powerful templating engine. It allows you to use template inheritance and sections. Blade views are compiled into plain PHP code and cached, making them very fast. Blade files use the .blade.php extension.'
            ],
            [
                'question' => 'How do I validate form data?',
                'answer' => 'Use the validate() method on the request object: $request->validate([\'field\' => \'required|email\']). Laravel provides many validation rules including required, email, min, max, unique, and more. Validation errors are automatically redirected back with error messages.'
            ],
            [
                'question' => 'What is a service provider?',
                'answer' => 'Service providers are the central place of all Laravel application bootstrapping. They bind things into the service container, register event listeners, middleware, and even routes. Your application\'s service providers are in app/Providers.'
            ],
            [
                'question' => 'How do I run scheduled tasks?',
                'answer' => 'Define your scheduled tasks in app/Console/Kernel.php in the schedule() method. Then add a single cron entry to your server: * * * * * php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1. Laravel will evaluate your scheduled tasks and run the tasks that are due.'
            ],
            [
                'question' => 'What is artisan?',
                'answer' => 'Artisan is the command-line interface included with Laravel. It provides helpful commands for building your application. You can view all available commands by running php artisan list, and get help for a specific command with php artisan help command-name.'
            ],
            [
                'question' => 'How do I send emails in Laravel?',
                'answer' => 'Configure your mail driver in .env (MAIL_MAILER, MAIL_HOST, etc.). Create a Mailable class with php artisan make:mail MailableName. Use Mail::to($email)->send(new MailableName()) to send. Laravel supports SMTP, Mailgun, Postmark, Amazon SES, and sendmail.'
            ],
            [
                'question' => 'What is the difference between public and storage folders?',
                'answer' => 'The public folder is web-accessible and contains your front-end assets (CSS, JS, images). The storage folder stores generated files like logs, cache, and uploaded files. To make storage files accessible, create a symbolic link: php artisan storage:link.'
            ],
        ];

        foreach ($qaData as $data) {
            $prompt = Prompt::create([
                'question' => $data['question'],
                'usage_count' => rand(0, 20),
                'is_favorite' => rand(0, 10) > 7, // 30% chance of being favorite
            ]);

            Answer::create([
                'prompt_id' => $prompt->id,
                'answer' => $data['answer'],
                'is_primary' => true,
                'helpfulness_score' => rand(-2, 10),
            ]);
        }

        $this->command->info('Sample Q&A pairs created successfully!');
    }
}
