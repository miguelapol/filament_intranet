<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute; // <-- ¡Añade si no está! (Laravel 9+)
use Illuminate\Support\Facades\Hash;

use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'country_id',
        'state_id',
        'city_id',
        'address',
        'postal_code'
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //function password
    protected function password():Attribute{
        return Attribute::make(
            set: fn ($value) => Hash::make($value),
        );
    }
    public function Country()
    {
        //muchos a uno, donde solo se puede seleccionar unico pais
        return $this->belongsTo(Country::class);
    }
    public function Calendar()
    {
        //muchos a muchos
        return $this->belongsToMany(Calendar::class);
    }
    public function Departament()
    {
        return $this->belongsToMany(Departament::class);
    }
    public function Holydays()
    {
        //temer diferentes registros de vacaciones
        return $this->hasMany(Holiday::class);
    }
    public function timesheets(){
        return $this->hasMany(Timesheet::class);
    }
}
