<?php

namespace App\Users\Entities;

use App\Core\Entities\Provider;
use Dotworkers\Configurations\Configurations;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

/**
 * Class User
 *
 * This class allows to interact with users table
 *
 * @package App\Users\Entities
 * @author  Eborio Linarez
 */
class User extends Authenticatable
{

    /**
     * Config Spatie
     *
     */

    use HasRoles;

    /**
     * Table
     *
     * @var string
     */
    protected $table = 'users';
    //protected $guard_name = 'web';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'uuid',
        'status',
        'whitelabel_id',
        'ip',
        'tester',
        'web_register',
        'referral_code',
        'reference',
        'last_login',
        'last_deposit',
        'last_deposit_amount',
        'last_debit',
        'last_debit_amount',
        'last_deposit_currency',
        'last_debit_currency',
        'main',
        'first_deposit',
        'first_deposit_amount',
        'first_deposit_currency',
        'register_currency',
        'type_user',
        'action',
        'confirmation_email',
        'theme'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * Dates
     *
     * @var string[]
     */
    protected $dates = ['last_login', 'last_deposit', 'last_debit'];

    /**
     * Relationship with Profile entity
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function routeNotificationForSns($notification)
    {
        return '+584127620563';
    }

    /**
     * Scope conditions
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $id User ID
     * @param string $username User username
     * @param string $dni User DNI
     * @param string $email User email
     * @param string $firstName User first name
     * @param string $lastName User last name
     * @param string $gender User gender
     * @param int $level Level ID
     * @param int $phone User phone
     * @param string $referralCode Referral code
     * @param int $wallet Wallet ID
     * @return string
     */
    public function scopeConditions(
        $query,
        $id,
        $username,
        $dni,
        $email,
        $firstName,
        $lastName,
        $gender,
        $level,
        $phone,
        $referralCode,
        $wallet
    ) {
        if (! empty($id)) {
            $query->where('id', $id);
        }

        if (! empty($username)) {
            $query->where('username', 'like', "$username%");
        }

        if (! empty($dni)) {
            $query->where('dni', 'like', "%$dni%");
        }

        if (! empty($email)) {
            $query->where('email', 'like', "$email%");
        }

        if (! empty($firstName)) {
            $query->where(\DB::raw('lower(first_name)'), 'like', "%$firstName%");
        }

        if (! empty($lastName)) {
            $query->where(\DB::raw('lower(last_name)'), 'like', "%$lastName%");
        }

        if (! empty($gender)) {
            if ($gender != '*') {
                $query->where('gender', $gender)->whereNotNull('gender');
            } else {
                $query->whereNotNull('gender');
            }
        }

        if (! empty($level) && $level != '*') {
            $query->where('level', $level);
        }

        if (! empty($phone)) {
            $query->where('phone', $phone);
        }

        if (! empty($referralCode)) {
            $query->where('referral_code', $referralCode);
        }

        if (! empty($wallet)) {
            $query->join('user_currencies', 'users.id', '=', 'user_currencies.user_id')
                ->where('user_currencies.wallet_id', $wallet);
        }

        return $query;
    }

    /**
     * Score web register
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param null|bool $webRegister Web register
     */
    public function scopeWebRegister($query, $webRegister)
    {
        if (! is_null($webRegister)) {
            $query->where('users.web_register', $webRegister);
        }
    }

    /**
     * Scope whitelabel
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return mixed
     */
    public function scopeWhitelabel($query)
    {
        return $query->where('whitelabel_id', Configurations::getWhitelabel());
    }

    /**
     * Set password attribute
     *
     * @param string $password User password
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    /**
     * Relationship with Provider entity
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function providers()
    {
        return $this->belongsToMany(Provider::class, 'exclude_providers_users')->withTimestamps();
    }

    /**
     * Referrals
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function referrals()
    {
        return $this->belongsToMany(User::class, 'referrals', 'user_id', 'referral_id')->withTimestamps();
    }

    /**
     * @return Attribute
     */
    protected function typeUser()
    : Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => match ($attributes['type_user']) {
                1, 2 => 'agent',
                5 => 'user',
                default => null,
            },
        );
    }

}
