<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    public static $roles = [
        'member_1' => 'Member',
        'editor' => 'Editor', 
        'admin' => 'Admin'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public static function createOrFindUser($email, $password = '', $login = false)
    {        
        $existingUser = User::where('email', $email)->first();        
        
        if(!empty($existingUser)){
            $userId = $existingUser->id;           
                        
            if($login){
                if(!empty($password)){                    
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'error' => 'This email is already taken.',
                    ]);
                } else {
                    Auth::login($existingUser);
                }                
            }
        } else {
            $_ex_email = explode('@',$email);
            $user = User::create([
                'name' => $_ex_email[0],
                'email' => $email,
                'password' => Hash::make(!empty($password) ? $password : md5(rand(111111, 9999999) ) ),
                'role' => 'member_1',
            ]);
            if($login){
                Auth::login($user);
            }
            $userId = $user->id;
        }
        return $userId; 
    }
}
