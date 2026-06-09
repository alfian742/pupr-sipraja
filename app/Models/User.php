<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
        'role',
        'last_login_at',
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

    // Relasi ke contacts yang dimodifikasi oleh user ini
    public function modifiedContacts()
    {
        return $this->hasMany(Contact::class, 'modified_by');
    }

    // Relasi ke contracts yang dibuat oleh user ini
    public function createdContracts()
    {
        return $this->hasMany(Contract::class, 'created_by');
    }

    // Relasi ke contracts yang diperbarui oleh user ini
    public function updatedContracts()
    {
        return $this->hasMany(Contract::class, 'updated_by');
    }

    // Relasi ke departments yang dimodifikasi oleh user ini
    public function modifiedDepartments()
    {
        return $this->hasMany(Department::class, 'modified_by');
    }

    // Relasi ke download_centers yang dimodifikasi oleh user ini
    public function modifiedDownloadCenters()
    {
        return $this->hasMany(DownloadCenter::class, 'modified_by');
    }

    // Relasi ke faqs yang dimodifikasi oleh user ini
    public function modifiedFAQs()
    {
        return $this->hasMany(FAQ::class, 'modified_by');
    }

    // Relasi ke ls_payments yang dibuat oleh user ini
    public function createdLsPayments()
    {
        return $this->hasMany(LsPayment::class, 'created_by');
    }

    // Relasi ke ls_payments yang diperbarui oleh user ini
    public function updatedLsPayments()
    {
        return $this->hasMany(LsPayment::class, 'updated_by');
    }

    // Relasi ke main_performance_indicators yang dimodifikasi oleh user ini
    public function modifiedMainPerformanceIndicators()
    {
        return $this->hasMany(MainPerformanceIndicator::class, 'modified_by');
    }

    // Relasi ke organization_profiles pada bagian struktur organisasi yang dimodifikasi oleh user ini
    public function modifiedOrganizationStructures()
    {
        return $this->hasMany(OrganizationProfile::class, 'organization_structure_modified_by');
    }

    // Relasi ke organization_profiles pada bagian profil organisasi yang dimodifikasi oleh user ini
    public function modifiedOrganizationProfiles()
    {
        return $this->hasMany(OrganizationProfile::class, 'profile_modified_by');
    }

    // Relasi ke personnel_profiles yang dimodifikasi oleh user ini
    public function modifiedPersonnelProfiles()
    {
        return $this->hasMany(PersonnelProfile::class, 'modified_by');
    }

    // Relasi ke realizations yang diverifikasi oleh user ini
    public function verifiedRealizations()
    {
        return $this->hasMany(Realization::class, 'verified_by');
    }

    // Relasi ke realizations yang dibuat oleh user ini
    public function createdRealizations()
    {
        return $this->hasMany(Realization::class, 'created_by');
    }

    // Relasi ke realizations yang diperbarui oleh user ini
    public function updatedRealizations()
    {
        return $this->hasMany(Realization::class, 'updated_by');
    }

    // Relasi ke regional_performance_indicators yang dimodifikasi oleh user ini
    public function modifiedRegionalPerformanceIndicators()
    {
        return $this->hasMany(RegionalPerformanceIndicator::class, 'modified_by');
    }

    // Relasi ke blog_articles sebagai penulis artikel
    public function blogArticles()
    {
        return $this->hasMany(BlogArticle::class, 'user_id');
    }

    // Relasi ke blog_articles yang dibuat oleh user ini
    public function createdBlogArticles()
    {
        return $this->hasMany(BlogArticle::class, 'created_by');
    }

    // Relasi ke blog_articles yang diperbarui oleh user ini
    public function updatedBlogArticles()
    {
        return $this->hasMany(BlogArticle::class, 'updated_by');
    }

    // Relasi ke blog_categories yang dibuat oleh user ini
    public function createdBlogCategories()
    {
        return $this->hasMany(BlogCategory::class, 'created_by');
    }

    // Relasi ke blog_categories yang diperbarui oleh user ini
    public function updatedBlogCategories()
    {
        return $this->hasMany(BlogCategory::class, 'updated_by');
    }
}
