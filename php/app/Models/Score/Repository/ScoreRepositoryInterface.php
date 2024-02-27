<?php


namespace App\Models\Score\Repository;

use App\Models\Score\Score;

interface ScoreRepositoryInterface
{
    /**
     * @param int $id
     * @return Score|null
     */
    public function find(int $id);


    /**
     * @param Score $score
     * @return Score|false
     */
    public function save(Score $score);

    public function getModelAndSetParams(array $data): Score;

    /**
     * @param int $test_id
     * @return array
     */
    public function getStudentsByTestId(int $test_id): array;

    public function getAverageScores(int $student_id);

    public function getComprehensionScores(int $student_id);

    /**
     * @param $student_id
     * @param $test_id
     * @return Score|null
     */
    public function getRecordByStudentIdAndTestId($student_id, $test_id): ?Score;
}
