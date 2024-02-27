<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

abstract class AbstractRepository
{

    private const DEFAULT_PER_PAGE = 15;

    /**
     * @var string テーブル名（setOrderで使用）
     */
    protected string $tbl_name = '';

    /**
     * ソート可能なカラムを指定
     * 例)
     * 'フロント側で指定されるキー' => '実際にソートされるテーブル名.カラム名'
     * @var array|string
     */
    protected array $sortable = [];
    /**
     * 一覧検索可能なカラムを指定する
     * 例)
     * 検索テーブル名.カラム名で検索対象を指定
     * 通常
     * 'tbl.col' => '=',
     * 部分一致
     * 'tbl.col1' => 'like',
     * 前方一致
     * 'tbl.col1' => 'like_before',
     * 広報一致
     * 'tbl.col1' => 'like_after',
     * 日付比較
     * 'tbl.col2' => 'date'
     * 0,1(flg)検索
     * 'tbl.col2' => 'boolean'
     * 0,1(flg)でtrueのみ
     * 'tbl.col2' => 'true'
     * 0,1(flg)でfalseのみ
     * 'tbl.col2' => 'false'
     *
     * ＜追記＞
     * 異なるテーブルの同一名カラムが検索に含まれる場合以下の記述で記入すると、検索できます。
     * tbl__col1
     * @var array|string[]
     */
    protected array $searchable = [];

    protected function getPerPage(?array $query = null): int
    {
        if ($query === null) {
            $query = request()->query();
        }
        if (isset($query['per_page']) && !empty($query['per_page'])) {
            return (int)$query['per_page'];
        }
        return self::DEFAULT_PER_PAGE;
    }

    /**
     * 検索
     * @param Builder $builder クエリビルダー
     * @param array $query 検索パラメータ
     * @param bool $or オア検索フラグ
     */
    protected function search(Builder $builder, array $query, $or = false)
    {
        foreach ($this->searchable as $key => $type) {
            $col = $key;
            if (strpos($key, '.') !== false) {
                [$tbl, $col] = explode('.', $key);
            }
            if (!isset($query[$col]) || empty($query[$col]) && $query[$col] != 0) {
                // 検索パラメータが空の場合
                continue;
            }
            if (strpos($col, '__') !== false) {
                // joinしたほかテーブルに同名カラムが存在したときに
                //検索を行うため__(アンダーバー２個)でつないだキーで検索がかかった場合、.に置換して検索を実行する。
                $key = str_replace('__', '.', $col);
            }

            $this->where($builder, $key, $type, $query[$col], $or);
        }
    }

    /**
     * where を条件を設定
     * @param Builder $builder
     * @param $key
     * @param $type
     * @param $param
     * @param $or
     */
    private function where(Builder $builder, $key, $type, $param, $or)
    {
        $boolean = $this->getBoolean($or);
        switch ($type) {
            case '=':
            case '<>':
            case '>':
            case '<':
            case '>=':
            case '<=':
                $builder->where($key, $type, $param, $boolean);
                break;
            case 'like':
                // 部分一致
                $builder->where($key, 'like', "%{$param}%", $boolean);
                break;
            case 'like_before':
                // 前方一致
                $builder->where($key, 'like', "%{$param}", $boolean);
                break;
            case 'like_after':
                // 後方一致
                $builder->where($key, 'like', "{$param}%", $boolean);
                break;
            case 'date':
                $builder->whereDate($key, '=', $param, $boolean);
                break;
            case 'null':
                if ($param === 'false') {
                    $param = false;
                }
                if ($param) {
                    $builder->whereNull($key, $boolean);
                }
                break;
            case 'not_null':
                if ($param === 'false') {
                    $param = false;
                }
                if ($param) {
                    $builder->whereNotNull($key, $boolean);
                }
                break;
            case 'boolean':
                // flg系のパラメーター
                if ($param === 'true') {
                    $builder->where($key, 1);
                } else {
                    $builder->where($key, 0);
                }
                break;
            case 'true':
                // flg系のパラメーターでtrueのときのみ
                if ($param === 'true') {
                    $builder->where($key, 1);
                }
                break;
            case 'false':
                // flg系のパラメーターでfalseのときのみ
                if ($param === 'false') {
                    $builder->where($key, 0);
                }
                break;
        }
    }

    /**
     * where条件のOR,ANDを取得
     * @param bool $or
     * @return string
     */
    private function getBoolean($or): string
    {
        if ($or) {
            return 'or';
        }
        return 'and';
    }

    protected function setOrder(): string
    {
        $query = request()->query();
        $order = '';
        if (!empty($query['sort_by']) && !empty($query['sort_order'])) {
            $order = "{$this->sortable[$query['sort_by']]} {$query['sort_order']}, ";
        }
        return "{$order}{$this->tbl_name}.id desc";

    }

}
