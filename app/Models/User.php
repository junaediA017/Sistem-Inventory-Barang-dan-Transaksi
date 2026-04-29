<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'role',
        'password',
        'created_at',
        'updated_at',
        'id_cabang',
        'keterangan_cabang',
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
        'email_verified_at' => 'datetime'
    ];

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'U';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    protected $table = 'users';

    public static function getData($input)
    {
        $data = self::select(
            'users.id',
            'users.name',
            'users.username',
            'users.email',
            'users.role',
            'cabang.keterangan_cabang'
        )
            ->join('cabang', 'users.id_cabang', '=', 'cabang.id_cabang')
            ->where('users.id', '!=', auth()->user()->id);

        if (!empty($input['keyword'])) {
            $data->where(function ($query) use ($input) {
                $query->where('name', 'LIKE', '%' . $input['keyword'] . '%')
                    ->orWhere('username', 'LIKE', '%' . $input['keyword'] . '%')
                    ->orWhere('email', 'LIKE', '%' . $input['keyword'] . '%')
                    ->orWhere('role', 'LIKE', '%' . $input['keyword'] . '%')
                    ->orWhere('keterangan_cabang', 'LIKE', '%' . $input['keyword'] . '%');
            });
        }

        if (!empty($input['role'])) {
            $data->where('role', '=', $input['role']);
        }

        $orderColumns = [
            'name', 'email', 'username', 'role', 'keterangan_cabang'
        ];

        if (in_array($input['order_by'], $orderColumns)) {
            if ($input['order_direction'] === 'asc' || $input['order_direction'] === 'desc') {
                $data->orderBy($input['order_by'], $input['order_direction']);
            } else {
                $data->orderBy($input['order_by']);
            }
        } else {
            $data->orderBy('name', 'asc');
        }

        return $data->offset($input['offset'])
            ->limit(10)
            ->get();
    }

    public static function countData($input)
    {
        $data = self::select('id')
            ->where('id', '!=', auth()->user()->id);

        if (!empty($input['keyword'])) {
            $data->where(function ($query) use ($input) {
                $query->where('name', 'LIKE', '%' . $input['keyword'] . '%')
                    ->orWhere('username', 'LIKE', '%' . $input['keyword'] . '%')
                    ->orWhere('email', 'LIKE', '%' . $input['keyword'] . '%')
                    ->orWhere('role', 'LIKE', '%' . $input['keyword'] . '%');
            });
        }

        if (!empty($input['role'])) {
            $data->where('role', '=', $input['role']);
        }

        return $data->count();
    }

    public static function getKeteranganByID($id)
    {
        $result = User::join('cabang', 'users.id_cabang', '=', 'cabang.id_cabang')
            ->where('users.id', $id)
            ->select('cabang.keterangan_cabang')
            ->first();

        return $result;
    }
}
