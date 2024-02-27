<?php


namespace App\Services;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

abstract class AbstractService
{
    /**
     * 一覧取得（ページネーション用）
     * @param int $company_id
     * @return array
     */
    abstract public function getList(?int $company_id);

    /**
     * Idから当該のカラムを取得
     * @param int $id
     * @return array|null
     */
    abstract public function getById(int $id): ?array;

    /**
     * @param Request $request
     * @param $password
     * @throws \Exception
     */
    protected function checkPassword(string $current_password, $password): void
    {
        if (!Hash::check($current_password, $password)) {
            throw new \Exception('現在のパスワードが違います');
        }
    }
}
