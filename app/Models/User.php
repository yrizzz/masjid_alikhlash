<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $guarded = ['id'];

    /** Role → label. Urutan menentukan hierarki tampilan. */
    public const ROLES = [
        'super-admin' => 'Super Admin',
        'takmir'      => 'Takmir',
        'sekretaris'  => 'Sekretaris',
        'bendahara'   => 'Bendahara',
        'imam'        => 'Imam',
        'muadzin'     => 'Muadzin',
        'tpq'         => 'Pengajar TPQ',
        'editor'      => 'Editor',
        'volunteer'   => 'Volunteer',
        'jamaah'      => 'Jamaah',
    ];

    /** Role yang boleh masuk dashboard admin. */
    public const STAFF_ROLES = ['super-admin', 'takmir', 'sekretaris', 'bendahara', 'imam', 'muadzin', 'tpq', 'editor'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'birth_date'        => 'date',
            'last_seen_at'      => 'datetime',
            'is_active'         => 'boolean',
            'skills'            => 'array',
        ];
    }

    public function donations() { return $this->hasMany(Donation::class); }
    public function bookmarks() { return $this->hasMany(Bookmark::class); }
    public function volunteer() { return $this->hasOne(Volunteer::class); }
    public function business() { return $this->hasOne(UmkmBusiness::class); }
    public function kajianRegistrations() { return $this->hasMany(KajianRegistration::class); }
    public function roomBookings() { return $this->hasMany(RoomBooking::class); }
    public function quranBookmarks() { return $this->hasMany(QuranBookmark::class); }

    public function getRoleLabelAttribute(): string
    {
        return self::ROLES[$this->role] ?? ucfirst((string) $this->role);
    }

    public function isStaff(): bool
    {
        return in_array($this->role, self::STAFF_ROLES, true);
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['super-admin', 'takmir'], true);
    }

    /** Total donasi lunas milik jamaah ini. */
    public function getTotalDonationAttribute(): float
    {
        return (float) $this->donations()->where('status', 'paid')->sum('amount');
    }
}
