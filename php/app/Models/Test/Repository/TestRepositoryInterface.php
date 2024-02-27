<?php


namespace App\Models\Test\Repository;

use App\Models\Test\Test;
use Illuminate\Database\Eloquent\Builder;

interface TestRepositoryInterface
{
    public function getList(?int $company_id): array;

    /**
     * @param int $id
     * @return Test|null
     */
    public function find(int $id);


    /**
     * @param Test $test
     * @return Test|false
     */
    public function save(Test $test);

    public function getModelAndSetParams(array $data, ?int $id): Test;

    /**
     * 未回答のCABテストを取得
     * @param int $student_id
     * @param int $type
     * @param int|null $curriculum_id
     * @param int|null $course_id
     * @return Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getUnansweredTest(int $student_id, int $type = Test::TYPE['curriculum'], int $curriculum_id = null, int $course_id = null);

    public function getAutocomplete(?int $type): array;
}
