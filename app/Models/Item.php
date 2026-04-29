<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class Item extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'part_number',
        'description',
        'price',
        'stock',
        'image',
        'satuan_brg'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'description' => 'string'
    ];

    public static function getData($input)
    {
        $data = DB::table('items as a')
            ->select(
                'a.id',
                'a.description',
                'a.part_number',
                'a.price',
                'a.satuan_brg'
            );

        if (!empty($input['keyword'])) {
            $data->where('a.description', 'LIKE', '%' . $input['keyword'] . '%')
                ->orWhere('a.part_number', 'LIKE', '%' . $input['keyword'] . '%')
                ->orWhere('a.price', 'LIKE', '%' . $input['keyword'] . '%')
                ->orWhere('a.satuan_brg', 'LIKE', '%' . $input['keyword'] . '%');
        }

        $order = [
            'part_number' => 'a.part_number',
            'description' => 'a.description',
            'price' => 'a.price',
            'satuan_brg' => 'a.satuan_brg'
        ];

        if (array_key_exists($input['order_by'], $order)) {
            if ($input['order_direction'] !== 'asc' && $input['order_direction'] !== 'desc') {
                $data->orderBy($order[$input['order_by']]);
            } else {
                $data->orderBy($order[$input['order_by']], $input['order_direction']);
            }
        } else {
            $data->orderBy('a.part_number', 'asc');
        }

        return $data->offset($input['offset'])
            ->limit(10)
            ->get();
    }

    public static function countData($input)
    {
        $data = DB::table('items as a')
            ->select('a.id');


        if (!empty($input['keyword'])) {
            $data->where('a.description', 'LIKE', '%' . $input['keyword'] . '%')
                ->orWhere('a.part_number', 'LIKE', '%' . $input['keyword'] . '%');
        }

        return $data->count();
    }

    public static function getAvailableItem()
    {
        return DB::select(
            'SELECT
                a.*,
                (IFNULL(b_amount, 0) - IFNULL(c_amount, 0)) as total
            FROM
                items as a
            LEFT JOIN
                (
                    SELECT
                        b.item_id,
                        SUM(b.amount) as b_amount
                    FROM
                        income_transaction_items as b
                    GROUP BY
                        b.item_id
                ) as x
            ON
                a.id = x.item_id
            LEFT JOIN
                (
                    SELECT
                        c.item_id,
                        SUM(c.amount) as c_amount
                    FROM
                        expenditure_transaction_items as c
                    GROUP BY
                        c.item_id
                ) as y
            ON
                a.id = y.item_id
            HAVING
                total > 0
            ORDER BY
                a.description ASC'
        );
    }

    public static function getAvailableItemIncludeIds($ids)
    {
        $query = 'SELECT a.*, (IFNULL(b_amount, 0) - IFNULL(c_amount, 0)) as total
            FROM
                items as a
            LEFT JOIN
                (
                    SELECT
                        b.item_id,
                        SUM(b.amount) as b_amount
                    FROM
                        income_transaction_items as b
                    GROUP BY
                        b.item_id
                ) as x
            ON
                a.id = x.item_id
            LEFT JOIN
                (
                    SELECT
                        c.item_id,
                        SUM(c.amount) as c_amount
                    FROM
                        expenditure_transaction_items as c
                    GROUP BY
                        c.item_id
                ) as y
            ON
                a.id = y.item_id
        ';

        $values = [];

        $query .= 'HAVING total > 0';

        foreach ($ids as $key => $value) {
            array_push($values, $value);

            $query .= " OR a.id = :$key ";
        }

        $query .= 'ORDER BY a.description ASC';

        return DB::select($query, $values);
    }

    public static function getStockById($id)
    {
        $income_transaction_items = DB::table('income_transaction_items')
            ->select(DB::raw(
                'SUM(amount) as income_transaction_items_amount,' .
                    'item_id'
            ))
            ->groupBy('item_id');

        $expenditure_transaction_items = DB::table('expenditure_transaction_items')
            ->select(DB::raw(
                'SUM(amount) as expenditure_transaction_items_amount,' .
                    'item_id'
            ))
            ->groupBy('item_id');
        return DB::table('items as a')
            ->select(
                DB::raw(
                    'a.id, a.description, a.part_number,' .
                        '(IFNULL(income_transaction_items.income_transaction_items_amount, 0) -' .
                        'IFNULL(expenditure_transaction_items.expenditure_transaction_items_amount, 0)) as total'
                )
            )
            ->leftJoinSub($income_transaction_items, 'income_transaction_items', function ($join) {
                $join->on('a.id', '=', 'income_transaction_items.item_id');
            })
            ->leftJoinSub($expenditure_transaction_items, 'expenditure_transaction_items', function ($join) {
                $join->on('a.id', '=', 'expenditure_transaction_items.item_id');
            })
            ->where('id', '=', $id)
            ->groupBy('a.id')
            ->first();
    }

    public static function getWithCategoryBrandUOMStock($input)
    {
        $where = 0;

        $values = [];

        // $query = '
        //     select
        //         a.*, 
        //         x.income_transaction_items_amount,
        //         y.expenditure_transaction_items_amount,
        //         (IFNULL(x.income_transaction_items_amount, 0) -
        //         IFNULL(y.expenditure_transaction_items_amount, 0))
        //         as stock
        //     from
        //         items as a
        //     left join
        //         ( SELECT
        //         SUM(amount) AS income_transaction_items_amount,
        //         item_id
        //     FROM
        //         income_transaction_items
        //     INNER JOIN
        //         income_transactions
        //     ON
        //         income_transaction_items.income_transaction_id = income_transactions.id
        //     WHERE
        //         income_transactions.remarks = "1"
        //     GROUP BY
        //         item_id
        //         ) as x
        //     on
        //         a.id = x.item_id
        //     left join
        //         (
        //            SELECT
        //         SUM(amount) AS expenditure_transaction_items_amount,
        //         item_id
        //     FROM
        //         expenditure_transaction_items
        //     INNER JOIN
        //         expenditure_transactions
        //     ON
        //         expenditure_transaction_items.expenditure_transaction_id = expenditure_transactions.id
        //     WHERE
        //         expenditure_transactions.remarks = "1"
        //     GROUP BY
        //         item_id
        //         ) as y
        //     on
        //         a.id = y.item_id
        // ';
        
        $query = '
            select
                cabang.keterangan_cabang,
                a.*, 
                x.income_transaction_items_amount,
                y.expenditure_transaction_items_amount,
                (IFNULL(x.income_transaction_items_amount, 0) -
                IFNULL(y.expenditure_transaction_items_amount, 0))
                as stock
            from
                items as a 
            left join
                ( SELECT
                SUM(amount) AS income_transaction_items_amount,
                item_id, id_cabang
            FROM
                income_transaction_items
            INNER JOIN
                income_transactions
            ON
                income_transaction_items.income_transaction_id = income_transactions.id
            WHERE
                income_transactions.remarks = "1"
            GROUP BY
                item_id, id_cabang
                ) as x 
            on
                a.id = x.item_id
            left join
                ( SELECT
                SUM(amount) AS expenditure_transaction_items_amount,
                item_id, id_cabang
            FROM
                expenditure_transaction_items
            INNER JOIN
                expenditure_transactions
            ON
                expenditure_transaction_items.expenditure_transaction_id = expenditure_transactions.id
            WHERE
                expenditure_transactions.remarks = "1"
            GROUP BY
                item_id, id_cabang
                ) as y 
            on
                a.id = y.item_id AND x.id_cabang = y.id_cabang
            LEFT JOIN (
                SELECT id_cabang, keterangan_cabang
                FROM cabang
            ) AS cabang
            ON x.id_cabang = cabang.id_cabang
        ';

        if (!empty($input['keyword'])) {
            $query .= '
                where
                    (
                        a.part_number
                        like
                            :keyword1
                        or
                            a.description
                        like
                            :keyword2
                        or
                            a.price

                        like
                            :keyword6
                    )
            ';
        }

        // $query .= '
        //     GROUP BY a.id
        // ';

        if (is_numeric($input['start_stock'])) {
            $query .= '
                having stock > :start_stock
            ';

            $values['start_stock'] = $input['start_stock'] - 1;
        }

        if (is_numeric($input['end_stock'])) {
            if (is_numeric($input['start_stock'])) {
                $query .= '
                    and stock < :end_stock
                ';
            } else {
                $query .= '
                    having stock < :end_stock
                ';
            }

            $values['end_stock'] = $input['end_stock'] + 1;
        }

        $orderColumns = [
            'part_number' => 'a.part_number',
            'description' => 'a.description',
            'price' => 'a.price',
            'keterangan_cabang' => 'cabang.keterangan_cabang'
        ];

        if (array_key_exists($input['order_by'], $orderColumns)) {
            if ($input['order_direction'] === 'asc' || $input['order_direction'] === 'desc') {
                $query .= '
                    order by
                        ' . $orderColumns[$input['order_by']] . '
                    ' . $input['order_direction'] . '
                ';
            } else {
                $query .= 'order by a.part_number asc';
            }
        } else {
            $query .= 'order by a.part_number asc';
        }

        $query .= '
            limit 10 offset ' . $input['offset'] . '
        ';

        return DB::select($query, $values);
    }

    public static function countWithCategoryBrandUOMStock($input)
    {
        $where = 0;

        $values = [];

        $query = '
            select
                count(*) as total
            from
                (
                    select
                        (IFNULL(x.income_transaction_items_amount, 0) -
                        IFNULL(y.expenditure_transaction_items_amount, 0))
                        as stock
                    from
                        items as a
                    left join
                        (
                            select
                                SUM(amount) as income_transaction_items_amount,
                                item_id
                            from
                                income_transaction_items
                            group by
                                item_id
                        ) as x
                    on
                        a.id = x.item_id
                    left join
                        (
                            select
                                SUM(amount) as expenditure_transaction_items_amount,
                                item_id
                            from
                                expenditure_transaction_items
                            group by
                                item_id
                        ) as y
                    on
                        a.id = y.item_id
        ';

        if (!empty($input['keyword'])) {
            $query .= '
                where
                    (
                        a.part_number
                        like
                            :keyword1
                        or
                            a.description
                        like
                            :keyword2
                        or
                            a.price
                        like
                            :keyword3
                       
                    )
            ';
        }

        if (is_numeric($input['start_stock'])) {
            $query .= '
                having stock > :start_stock
            ';

            $values['start_stock'] = $input['start_stock'] - 1;
        }

        if (is_numeric($input['end_stock'])) {
            if (is_numeric($input['start_stock'])) {
                $query .= '
                    and stock < :end_stock
                ';
            } else {
                $query .= '
                    having stock < :end_stock
                ';
            }

            $values['end_stock'] = $input['end_stock'] + 1;
        }

        $query .= ') as x';

        // dd($query);

        return DB::select($query, $values)[0]->total;
    }


    /**
     * Get the category that owns the item.
     */


    /**
     * Get the brand that owns the item.
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Get the unit of measurement that owns the item.
     */
    public function unitOfMeasurement()
    {
        return $this->belongsTo(UnitOfMeasurement::class);
    }

    /**
     * Get the income transaction items for the item.
     */
    public function incomeTransactionItems()
    {
        return $this->hasMany(IncomeTransactionItem::class);
    }

    /**
     * Get the expenditure transaction items for the item.
     */
    public function expenditureTransactionItems()
    {
        return $this->hasMany(ExpenditureTransactionItem::class);
    }
}
