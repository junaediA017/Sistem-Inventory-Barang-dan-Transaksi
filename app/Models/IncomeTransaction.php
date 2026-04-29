<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomeTransaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'supplier',
        'reference_number',
        'remarks',
        'keterangan',
        'created_at',
        'id_cabang'
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
    protected $table = 'income_transactions';


    public static function getData($input)
    {
        if (auth()->user()->id_cabang == 1) {
            $data = self::select(
                'income_transactions.id',
                'income_transactions.supplier',
                'income_transactions.reference_number',
                'income_transactions.remarks',
                'income_transactions.keterangan',
                'income_transactions.created_at',
                'income_transactions.id_cabang',
                'cabang.keterangan_cabang'
            )->join('cabang', 'income_transactions.id_cabang', '=', 'cabang.id_cabang');
        } else {
            $data = self::select(
                'income_transactions.id',
                'income_transactions.supplier',
                'income_transactions.reference_number',
                'income_transactions.remarks',
                'income_transactions.keterangan',
                'income_transactions.created_at',
                'income_transactions.id_cabang',
                'cabang.keterangan_cabang'
            )
                ->join('cabang', 'income_transactions.id_cabang', '=', 'cabang.id_cabang')    
                ->where('income_transactions.id_cabang', '=', auth()->user()->id_cabang);
        }

        if (!empty($input['keyword'])) {
            $data->where(function ($query) use ($input) {
                $query->where('supplier', 'LIKE', '%' . $input['keyword'] . '%')
                    ->orWhere('reference_number', 'LIKE', '%' . $input['keyword'] . '%')
                    ->orWhere('remarks', 'LIKE', '%' . $input['keyword'] . '%');
            });
        }

        if (!empty($input['start_date']) && !empty($input['end_date'])) {
            $data->whereBetween('created_at', [
                $input['start_date'] - 86400,
                $input['end_date'] + 86400
            ]);
        } else {
            if (!empty($input['start_date'])) {
                $data->where('created_at', '>', $input['start_date'] - 86400);
            }

            if (!empty($input['end_date'])) {
                $data->where('created_at', '<', $input['end_date'] + 86400);
            }
        }

        $orders = [
            'supplier', 'reference_number', 'remarks', 'created_at'
        ];

        if (in_array($input['order_by'], $orders)) {
            if ($input['order_direction'] !== 'asc' && $input['order_direction'] !== 'desc') {
                $data->orderBy($input['order_by']);
            } else {
                $data->orderBy($input['order_by'], $input['order_direction']);
            }
        } else {
            $data->orderBy('created_at', 'desc');
        }

        return $data->offset($input['offset'])
            ->limit(10)
            ->get();
    }

    public static function countData($input)
    {
        if (auth()->user()->id_cabang == 1) {
            $data = self::select('id');
        } else {
            $data = self::select(
                'id'
            )->where('id_cabang', '=', auth()->user()->id_cabang);
        }

        if (!empty($input['keyword'])) {
            $data->where(function ($query) use ($input) {
                $query->where('supplier', 'LIKE', '%' . $input['keyword'] . '%')
                    ->orWhere('reference_number', 'LIKE', '%' . $input['keyword'] . '%')
                    ->orWhere('remarks', 'LIKE', '%' . $input['keyword'] . '%');
            });
        }

        if (!empty($input['start_date']) && !empty($input['end_date'])) {
            $data->whereBetween('created_at', [
                $input['start_date'] - 86400,
                $input['end_date'] + 86400
            ]);
        } else {
            if (!empty($input['start_date'])) {
                $data->where('created_at', '>', $input['start_date'] - 86400);
            }

            if (!empty($input['end_date'])) {
                $data->where('created_at', '<', $input['end_date'] + 86400);
            }
        }

        return $data->count();
    }

    /**
     * Get the income transaction items for the income trasaction.
     */
    public function incomeTransactionItems()
    {
        return $this->hasMany(IncomeTransactionItem::class);
    }
}
