<?php

namespace App\Actions\Fortify;

use App\Models\StudentDetail;
use App\Models\TeacherDetail;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        if($input['user_type'] === 'Student') {
            Validator::make($input, [
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'address' => ['sometimes', 'nullable', 'string', 'max:255'],
                'contact_number' => ['sometimes', 'nullable', 'numeric', Rule::unique(StudentDetail::class)],
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)],
                'password' => $this->passwordRules(),
            ])->validate();

            $user = DB::transaction(function() use ($input) {
                $user = User::create([
                    'name' => "{$input['first_name']} {$input['last_name']}",
                    'email' => $input['email'],
                    'password' => Hash::make($input['password']),
                    'user_type' => $input['user_type']
                ]);
        
                $user->studentDetail()->create([
                    'first_name' => ucwords($input['first_name']),
                    'last_name' => ucwords($input['last_name']),
                    'address' => $input['address'],
                    'contact_number' => $input['contact_number'],
                ]);

                return $user;
            });

        } else {
            Validator::make($input, [
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'address' => ['sometimes', 'nullable', 'string', 'max:255'],
                'contact_number' => ['sometimes', 'nullable', 'numeric', Rule::unique(TeacherDetail::class)],
                'email' => [ 'required', 'string', 'email', 'max:255', Rule::unique(User::class)],
                'password' => $this->passwordRules(),
                'file' => ['required', File::image()->max(30 * 1024)]
            ])->validate();

            $user = DB::transaction(function() use ($input) {
                $user = User::create([
                    'name' => "{$input['first_name']} {$input['last_name']}",
                    'email' => $input['email'],
                    'password' => Hash::make($input['password']),
                    'user_type' => $input['user_type']
                ]);
                
                $filePath = Storage::put('teachers-attachments', $input['file'], 'private');
    
                $user->teacherDetail()->create([
                    'first_name' => ucwords($input['first_name']),
                    'last_name' => ucwords($input['last_name']),
                    'address' => $input['address'],
                    'contact_number' => $input['contact_number'],
                    'is_verified' => false,
                    'file' => $filePath
                ]);

                return $user;
            });
        }

        return $user;
    }
}
