<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\AddRowNumber;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, AddRowNumber, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'prefixname',
        'firstname',
        'middlename',
        'lastname',
        'suffixname',
        'username',
        'email',
        'password',
        'photo',
        'type',
        'email_verified_at',
    ];

    protected $appends = [
        'fullname', 'middleinitial', 'avatar'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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

    /**
     * Get Full Name
     *
     * @return string
     */
    public function getFullnameAttribute(): string
    {
        $firstName = $this->attributes['firstname'];
        $middleName = $this->attributes['middlename'];
        $lastName = $this->attributes['lastname'];

        $middleInitial = $this->getInitials($middleName);

        if ($middleName) {
            return "{$firstName} {$middleInitial} {$lastName}";
        }

        return "{$firstName} {$lastName}";
    }

    /**
     * Get avatar
     *
     * @return string
     */
    public function getAvatarAttribute(): string
    {
        $photo = $this->attributes['photo'];
        return $photo ? asset($photo) : '';
    }

    /**
     * Get middle initial
     *
     * @return string
     */
    public function getMiddleinitialAttribute(): string
    {
        $middleName = $this->attributes['middlename'];

        return $this->getInitials($middleName);
    }


    /**
     * Get initials
     *
     * @param string $value
     *
     * @return string
     */
    private function getInitials(?string $value): string
    {
        if (!$value) return '';
        $parts = explode(' ', $value);

        $initials = '';
        foreach ($parts as $part) {
            if (!empty($part)) {
                $initials .= strtoupper($part[0]) . '.'; // Get the first letter and convert to uppercase
            }
        }

        return $initials;
    }
}
