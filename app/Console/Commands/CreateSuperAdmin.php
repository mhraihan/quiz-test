<?php

namespace App\Console\Commands;

use App\Enums\UserEnum;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;

class CreateSuperAdmin extends Command
{
    protected $signature = 'quiz:create-super-admin';
    protected $description = 'Create a super admin user';

    public function handle(): void
    {
        try {
            $userInput = [
                'first_name' => $this->ask('Enter the First Name of the super admin:'),
                'last_name' => $this->ask('Enter the Last name of the super admin:'),
                'email' => $this->ask('Enter the email of the super admin:'),
                'password' => $this->secret('Enter the password of the super admin:'),
                'password_confirmation' => $this->secret('Confirm the password:'),
            ];

            $this->validateUserInput($userInput);
            $this->createSuperAdmin($userInput);
        } catch (\InvalidArgumentException $e) {
            $this->error($e->getMessage());
        } catch (ValidationException $e) {
            foreach ($e->errors() as $error) {
                $this->error($error[0]);
            }
        } catch (\Exception $e) {
            $this->error('An error occurred while creating the super admin.');
        }
    }

    private function validateUserInput(array $data): void
    {
        $validator = Validator::make($data, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    private function createSuperAdmin(array $data): void
    {
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'email_verified_at' => now(),
        ]);

        $user->syncRoles(UserEnum::SUPER_ADMIN->value);
        $this->info('Super admin user created successfully!');
    }
}
