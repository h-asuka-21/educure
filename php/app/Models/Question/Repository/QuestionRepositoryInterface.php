<?php


namespace App\Models\Question\Repository;

use App\Models\Question\Question;

interface QuestionRepositoryInterface
{
    public function getList(?int $company_id): array;

    /**
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function find(int $id);


    /**
     * @param Question $test
     * @return Question
     */
    public function save(Question $test): ?Question;


    public function getListByTestId(int $test_id): array;

    public function getModelAndSetParams(array $data, ?int $test_id, int $order): Question;

    public function delete(int $id): bool;
}
