<?php


namespace App\Providers;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;

/**
 * Class CustomAuthProvider
 * @package App\Providers
 */
abstract class AbstractAuthProvider extends EloquentUserProvider implements UserProvider
{

    /**
     * @var string
     */
    protected string $table_name;

    /**
     * 与えられた credentials からユーザーのインスタンスを探す
     *
     * @param array $credentials
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|void|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials) ||
            (count($credentials) === 1 &&
                array_key_exists('password', $credentials))) {
            return;
        }
        $query = $this->newModelQuery()
            ->select([$this->table_name . '.*']);
        if ($this->table_name !== 'admins') {
            // master管理以外
            if (!isset($credentials['company_code'])) {
                return null;
            }
            // 企業テーブルをjoin
            $query->join('companies', 'companies.id', $this->table_name . '.company_id');
        }
        foreach ($credentials as $key => $value) {
            if (Str::contains($key, ['password'])) {
                continue;
            }
            if (is_array($value) || $value instanceof Arrayable) {
                $query->whereIn($key, $value);
            } else {
                $query->where($key, $value);
            }
        }
        return $query->first();

    }

    /**
     * @param mixed $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function retrieveById($identifier)
    {
        return $this->createModel()->newQuery()
            ->select([$this->table_name . '.*'])
            ->where($this->table_name . '.id', '=', $identifier)
            ->first();
    }
}

