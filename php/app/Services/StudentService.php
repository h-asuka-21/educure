<?php


namespace App\Services;


use App\Models\Company\Company;
use App\Models\Company\Repository\CompanyRepositoryInterface;
use App\Models\Course\Course;
use App\Models\Course\Repository\CourseRepositoryInterface;
use App\Models\Curriculum\Curriculum;
use App\Models\Curriculum\Repository\CurriculumRepositoryInterface;
use App\Models\InterviewHistory\Repository\InterviewHistoryRepositoryInterface;
use App\Models\Progress\Repository\ProgressRepositoryInterface;
use App\Models\Report\Repository\ReportRepositoryInterface;
use App\Models\Reservation\Repository\ReservationRepositoryInterface;
use App\Models\Score\Repository\ScoreRepositoryInterface;
use App\Models\Student\Repository\StudentRepositoryInterface;
use App\Models\Student\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Mockery\Exception;
use Illuminate\Support\Carbon;

class StudentService extends AbstractService
{
    private StudentRepositoryInterface $student;

    private CourseRepositoryInterface $course;

    private CurriculumRepositoryInterface $curriculum;

    private ProgressRepositoryInterface $progress;

    private CurriculumService $curriculumService;

    private ScoreRepositoryInterface $scores;

    private ReservationRepositoryInterface $reservation;

    private InterviewHistoryRepositoryInterface $interview;

    private CompanyRepositoryInterface $company;

    private ReportRepositoryInterface $report;

    public function __construct(
        StudentRepositoryInterface $student,
        CourseRepositoryInterface $course,
        CurriculumRepositoryInterface $curriculum,
        ProgressRepositoryInterface $progress,
        CurriculumService $curriculumService,
        ScoreRepositoryInterface $scores,
        ReservationRepositoryInterface $reservation,
        InterviewHistoryRepositoryInterface $interview,
        CompanyRepositoryInterface $company,
        ReportRepositoryInterface $report
    )
    {
        $this->student = $student;
        $this->course = $course;
        $this->progress = $progress;
        $this->scores = $scores;
        $this->reservation = $reservation;
        $this->interview = $interview;
        $this->curriculumService = $curriculumService;
        $this->company = $company;
        $this->report = $report;
    }

    /**
     * @param int|null $company_id
     * @param bool $paginate
     * @param int|null $type
     * @param bool $end_this_month
     * @return array
     */
    public function getList(?int $company_id, bool $paginate = true, ?int $type = null, bool $end_this_month = false, bool $without_old_course_id = false)
    {
        return $this->student->getList($company_id, $paginate, $type, $end_this_month, $without_old_course_id);
    }

    /**
     * @param int $id
     * @return array|null
     */
    public function getById(int $id): ?array
    {
        $student = $this->student->find($id);
        if ($student === null) {
            throw new \Exception('生徒が見つかりませんでした');
        }
        $student->setAttribute('course_name', '未設定');
        if ($student->course_id) {
            /** @var Course $course */
            $course = $this->course->find($student->course_id);
            $student->setAttribute('course_name', $course->name);
        }
        /** @var Company $company */
        $company = $this->company->find($student->company_id);
        $student->setAttribute('company_name', $company->name);
        return $student->toArray();
    }

    /**
     * @param int $id
     * @return bool
     */
    public function deleteStudent(int $id)
    {
        $target_student = $this->student->find($id);
        if ($target_student === null) {
            return false;
        }
        return $this->student->delete($id);
    }

    public function save(Request $request, ?int $company_id, ?int $id = null)
    {
        $student = $this->getModelAndSetParams($request, $company_id, $id);
        if ($this->student->save($student)) {
            return true;
        }
        return false;
    }


    public function updateforScores(array $item)
    {
        $student = new Student();

        $student->id = $item['student_id'];
        $student->total_score = $item['total_score'];
        $student->teacher_score = $item['teacher_score'];
        $student->sales_score = $item['sales_score'];
        $student->comprehension_score = $item['comprehension_score'];
        $student->think_score = $item['think_score'];
        $student->attendance_score = $item['attendance_score'];
        $student->report_score = $item['report_score'];
        $student->progress_score = $item['progress_score'];
        $student->aggregate_date = date('Y-m-d');

        if ($this->student->updateforScores($student)) {
            return true;
        }
        return false;
    }

    /**
     * @param Request $request
     * @param int|null $company_id
     * @param int|null $id
     * @return Student
     * @throws \Exception
     */
    public function getModelAndSetParams(Request $request, ?int $company_id, int $id = null): Student
    {
        $student = new Student();
        if ($id !== null) {
            $student = $this->student->find($id);
        }
        if ($request->password_change) {
            // 現在のパスワードが間違っている場合ここで例外を返す
            $this->checkPassword($request->current_password, $student->password);
            $student->password = Hash::make($request->new_password);
        } elseif ($request->password !== null) {
            $student->password = Hash::make($request->password);
        }
        $student->company_id = $company_id ?? $request->company_id;
        $student->name = $request->name;
        $student->name_kana = $request->name_kana;
        $student->email = $request->email;
        $student->course_id = $request->course_id;
        $student->start_date = $request->start_date;
        $student->end_date = $request->end_date;
        $student->birthday = $request->birthday;
        $student->gender = $request->gender;
        $student->academic_type = $request->academic_type;
        $student->birthplace = $request->birthplace;
        $student->working_history = $request->working_history;
        $student->former_job_type = $request->former_job_type;
        $student->former_job_status = $request->former_job_status;
        $student->change_job_count = $request->change_job_count;
        $student->national_qualification_flg = $request->national_qualification_flg ?? 0;
        $student->qualification_flg = $request->qualification_flg ?? 0;
        $student->club_type = $request->club_type ?? 0;
        $student->after_graduation_flg = $request->after_graduation_flg;
        return $student;
    }


    public function getReservedStudents($schedule_id)
    {
        return $this->student->getReserveStudentList($schedule_id);
    }


    public function getEvaluation(int $student_id, int $company_id = null): array
    {

        $student = $this->student->find($student_id);
        if ($company_id) {
            if ($student === null || $student->company_id !== $company_id) {
                throw new \Exception('page not find', 404);
            }
        }

        $result = [
            'total_score' => $student->total_score,
            'sales_score' => $student->sales_score,
            'think_score' => $student->think_score,
            'comprehension_score' => $student->comprehension_score,
            'teacher_score' => $student->teacher_score,
            'attendance_score' => $student->attendance_score,
            'report_score' => $student->report_score,
            'progress_score' => $student->progress_score
        ];

        $result['sales_scores'] = $this->interview->getListByStudentId($student_id)->toArray();
        $result['teacher_scores'] = $this->getTeacherEvaluationData($student_id);

        [$result['total_score'], $result['rank']] = $this->calcEvaluationTotalScoreAndRank($result);

        return $result;
    }

    public function getEvaluationBatch(array $params, bool $progress_evaluation_flg, bool $sales_evaluation_flg, array $student): array
    {
        if ($progress_evaluation_flg) {
            // 講師評価
            $scores['teacher_score'] = $this->getTeacherEvaluationScore($params['student_id'], $params['date']);
            // 思考力、理解度
            [$scores['think_score'], $scores['comprehension_score']] = $this->getTestEvaluation($params['student_id']);
            // 出席率、報告率
            [$scores['attendance_score'], $scores['report_score']] = $this->getAttendanceEvaluation($params['student_id'], $params['date']);
            // 進捗率
            $scores['progress_score'] = $this->getProgressEvaluation($params['student_id']);
        }
        if ($sales_evaluation_flg) {
            // 営業評価
            $sales_score = $this->interview->getEvaluationAverageByStudentId($params['student_id']);
            $scores['sales_score'] = $sales_score ? round($sales_score) : null;
        }

        $result = [
            'student_id' => $student['id'],
            'sales_score' => isset($scores['sales_score']) ? $scores['sales_score'] : $student['sales_score'],
            'think_score' => isset($scores['think_score']) ? $scores['think_score'] : $student['think_score'],
            'comprehension_score' => isset($scores['comprehension_score']) ? $scores['comprehension_score'] : $student['comprehension_score'],
            'teacher_score' => isset($scores['teacher_score']) ? $scores['teacher_score'] : $student['teacher_score'],
            'attendance_score' => isset($scores['attendance_score']) ? $scores['attendance_score'] : $student['attendance_score'],
            'report_score' => isset($scores['report_score']) ? $scores['report_score'] : $student['report_score'],
            'progress_score' => isset($scores['progress_score']) ? $scores['progress_score'] : $student['progress_score'],
        ];

        [$result['total_score'], $result['rank']] = $this->calcEvaluationTotalScoreAndRank($result);

        return $result;
    }

    public function getEvaluationRanking(int $company_id = null): array
    {
        $students = $this->getList($company_id, false)->toArray();
        $results = [];
        $total = 0;
        foreach ($students as $key => $student) {
            if ($student === null || $student['company_id'] !== $company_id) {
                throw new \Exception('page not find', 404);
            }
            // トータルスコアがNULLの場合ランキングから除外
            if (empty($student['total_score'])) {
                continue;
            }

            $results['data'][$key] = [
                'id' => $student['id'],
                'name' => $student['name'],
                'all_ave_score' => $student['total_score'],
            ];
            $sort_keys[$key] = $results['data'][$key]['all_ave_score'];
            $total++;
        }
        $results['total'] = $total;
        if (isset($results['data'])) {
            // DataTable用に評価点で並び替え
            array_multisort($sort_keys, SORT_DESC, $results['data']);
            // 順位追加
            $order = 1;
            foreach ($results['data'] as $key => $value) {
                $results['data'][$key]['order'] = $order;
                $order++;
            }
        }
        return $results;
    }

    public function getTeacherEvaluationData(int $student_id): array
    {
        $evaluations = $this->reservation->getTeacherEvaluations($student_id);

        if ($evaluations->count() === 0) {
            return [];
        }

        return $evaluations->toArray();
    }

    public function getSelfRanking(int $student_id, int $company_id = null)
    {
        $results = $this->getEvaluationRanking($company_id);

        if (isset($results['data'])) {
            foreach ($results['data'] as $key => $value) {
                if ($value['id'] == $student_id) {
                    $self_rank = $value['order'];
                    return $self_rank;
                }
            }
        }
        return false;
    }

    private function getTestEvaluation(int $student_id): array
    {
        $scores = $this->scores->getAverageScores($student_id)->toArray()[0];
        $comprehension_scores = $this->scores->getComprehensionScores($student_id)->toArray();
        if ($comprehension_scores) {
            $comprehension_total_score = 0.0;
            foreach ($comprehension_scores as $key => $score) {
                $comprehension_total_score += $this->calcEvaluationScore($score['score'], $score['question_count']);
            }
            $comprehension_score = round($comprehension_total_score / count($comprehension_scores), 1);
        } else {
            $comprehension_score = null;
        }
        return [
            $this->calcEvaluationScore($scores['think_total'], $scores['think_count']),
            $comprehension_score
        ];
    }

    private function getAttendanceEvaluation(int $student_id, string $date): array
    {
        // 予約のみの状態になっている数
        // $reserve_count = $this->reservation->getTotalStudentReservedCountToToday($student_id);
        $reserve_count = $this->reservation->getTotalStudentReservedCountToDate($student_id, $date);
        // 出席数
        // $attendance_count = $this->reservation->getTotalStudentReserveCount($student_id);
        $attendance_count = $this->reservation->getTotalStudentReserveCountToDate($student_id, $date);
        // 報告数（出席で講師評価が入っている数）
        // $report_count = $this->reservation->getTotalStudentReserveCountWithTeacherEvaluation($student_id);
        $report_count = $this->reservation->getTotalStudentReserveCountWithTeacherEvaluationToDate($student_id, $date);
        return [
            $this->calcAttendanceEvaluationScore($attendance_count, ($reserve_count + $attendance_count)),
            $this->calcAttendanceEvaluationScore($report_count, $attendance_count),
        ];
    }

    private function calcEvaluationScore($total, $count): ?float
    {
        if ($total == 0 && $count == 0) {
            return null;
        }
        $percent = $total / $count;
        for ($score = 0; $score <= 4; $score++) {
            $target = 0.2 * $score;
            if ($percent <= $target) return $score;
        }
        return round($score, 1);
    }

    private function calcAttendanceEvaluationScore($total, $count): ?float
    {
        if ($total == 0 && $count == 0) {
            return null;
        }
        $percent = $total / $count;
        for ($score = 0; $score <= 4; $score++) {
            $target = 0.1 * $score;
            if ($percent <= $target) return $score;
        }
        return round($score, 1);
    }

    public function calcEvaluationTotalScoreAndRank(array $result)
    {

        if (!$result['sales_score'] ||
            !$result['think_score'] ||
            !$result['comprehension_score'] ||
            !$result['teacher_score'] ||
            !$result['attendance_score'] ||
            !$result['report_score'] ||
            !$result['progress_score']) {
            return [null, null];
        }

        $sales = $result['sales_score'] * 1.5;
        $think = $result['think_score'];
        $comprehension = $result['comprehension_score'] * 1.75;
        $teacher = $result['teacher_score'] * 1.75;
        $attendance = $result['attendance_score'];
        $report = $result['report_score'] * 1.5;
        $progress = $result['progress_score'] * 1.5;

        $total_score = round($sales + $think + $comprehension + $teacher + $attendance + $report + $progress, 1);
        if ($total_score > 45) {
            $rank = 'S';
        } elseif ($total_score > 40) {
            $rank = 'A';
        } elseif ($total_score > 30) {
            $rank = 'B';
        } elseif ($total_score > 20) {
            $rank = 'C';
        } elseif ($total_score > 10) {
            $rank = 'D';
        } else {
            $rank = 'E';
        }
        return [$total_score, $rank];
    }

    private function getTeacherEvaluation(int $student_id): array
    {
        $evaluations = $this->reservation->getTeacherEvaluations($student_id);
        $total_score = 0;
        if ($evaluations->count() === 0) {
            return [
                null,
                [],
            ];
        }
        foreach ($evaluations as $evaluation) {
            $total_score += $evaluation->evaluation;
        }
        return [
            round($total_score / $evaluations->count(), 1),
            $evaluations->toArray()
        ];
    }

    private function getTeacherEvaluationScore(int $student_id, string $date)
    {
        $evaluations = $this->reservation->getTeacherEvaluations($student_id, $date);

        $total_score = 0;
        if ($evaluations->count() === 0) {
            return null;
        }
        foreach ($evaluations as $evaluation) {
            $total_score += $evaluation->evaluation;
        }

        return round($total_score / $evaluations->count(), 1);
    }

    private function getProgressEvaluation(int $student_id): ?int
    {
        $student = $this->student->find($student_id);
        // クリア済みのカリキュラム数取得
        $cleared_curriculum_count = $this->progress->getCleared($student->course_id, $student_id)->count();
        $progress = $this->curriculumService->getProgress($student_id);

        $total_score = 0;
        if ($cleared_curriculum_count === 0) {
            return null;
        }
        for ($i = 0; $i < $cleared_curriculum_count; $i++) {
            if ($progress['status'][$i] <= $progress['target'][$i]) {
                $total_score += 4;
            } else {
                $total_score += 2;
            }
        }
        return round($total_score / $cleared_curriculum_count, 1);
    }

    public function getDelayStudents($company_id): array
    {
        $students = $this->student->getList($company_id, false, 1);
        $result = [];
        foreach ($students as $student) {
            /** @var $student Student */
            $item = $student->toArray();
            $curriculum = $this->curriculumService->getCurrentCurriculumAndStepByStudentId($student->id);
            if (!empty($curriculum) && $curriculum['step']['target_days'] < $curriculum['step']['date_count']) {
                $item['curriculum'] = 'No.' . ($curriculum['curriculum']['order'] + 1) . ' ' . $curriculum['curriculum']['name'];
                $item['step'] = 'No.' . ($curriculum['step']['order'] + 1) . ' ' . $curriculum['step']['name'];
                $item['date_count'] = $curriculum['step']['date_count'];
                $item['target_days'] = $curriculum['step']['target_days'];
                $item['deadline_days'] = $curriculum['step']['deadline_days'];
                $item['delay'] = $curriculum['step']['date_count'] - $curriculum['step']['target_days'];
                $result[] = $item;
            }
        }
        return $result;
    }

    public function getLowEvaluationStudents($company_id): array
    {
        $students = $this->student->getList($company_id, false, 1);
        $result = [];
        foreach ($students as $student) {
            /** @var $student Student */
            $item = $student->toArray();
            $evaluation = $this->report->getAvgEvaluationAndAttendanceCountForOneMonthByStudentId($student->id)->toArray();
            // 自己評価が1ヶ月平均2.5以下の場合に表示対象にする
            if (isset($evaluation['avg_evaluation']) && $evaluation['avg_evaluation'] <= 2.5) {
                $reports = $this->report->getLowEvaluationListByStudentId($student->id);
                $item['avg_evaluation'] = round($evaluation['avg_evaluation'], 1);
                $item['count'] = $evaluation['count'];
                $item['last_date'] = $reports[0]['date'];
                $item['last_personal_evaluation'] = $reports[0]['personal_evaluation'];
                $result[] = $item;
            }
        }
        return $result;
    }

    public function getNotAttendedStudents($company_id): array
    {
        $students = $this->student->getList($company_id, false, 1);
        $result = [];
        $subMonth = Carbon::today()->subMonth();
        foreach ($students as $student) {
            /** @var $student Student */
            $item = $student->toArray();
            $attended = $this->reservation->getLastAttendedDayByStudentId($student->id);
            if (!$attended) {
                continue;
            }
            $last_date = new Carbon($attended->date);
            // 1ヶ月以上出席がない生徒を表示対象にする
            if ($subMonth->gt($last_date)) {
                $item['last_date'] = $attended->date;
                $sort_keys[] = $attended->date;
                $result[] = $item;
            }
        }
        // 最終出席日で並び替え
        if ($result) {
            array_multisort($sort_keys, SORT_DESC, $result);
        }
        return $result;
    }

    public function getStudentsAchievementPercentage(?int $company_id = null)
    {
        $graduated_students = $this->getList($company_id, false, 2, false, false)->count();
        $retired_students = $this->getList($company_id, false, 3, false, false)->count();
        $parameter = $graduated_students + $retired_students;
        $percentage = $parameter != 0 ? ($graduated_students / $parameter) * 100 : 0;
        return round($percentage, 1);
    }

    public function getStudentStatistics(?int $company_id = null): array
    {
        $graduated_students = $this->getList($company_id, false, 2);
        $retired_students = $this->getList($company_id, false, 3);

        // 出席日数別達成数・リタイア数
        $by_attendance_achievement = $this->getStudentsCountByTotalDays($graduated_students);
        $by_attendance_retire = $this->getStudentsCountByTotalDays($retired_students);

        // 月別達成数・リタイア数
        [$months, $by_month_achievement, $by_month_retire] = $this->getStudentsCountByMonth($company_id);

        // カリキュラム毎のリタイア数
        [$by_curriculum_retire, $by_curriculum_labels] = $this->getStudentsCountByCurriculum($company_id, $retired_students);

        // 年齢
        $start_age_achievement = $this->getStudentsCountByStartAge($graduated_students);
        $start_age_retire = $this->getStudentsCountByStartAge($retired_students);

        // 性別
        foreach (config('const.students.GENDER_LIST') as $key => $value) {
            $gender_achievement[] = $this->student->getStudentsCountWithCompanyIdAndAttribute($company_id, 2, 'gender', $value);
        }
        foreach (config('const.students.GENDER_LIST') as $key => $value) {
            $gender_retire[] = $this->student->getStudentsCountWithCompanyIdAndAttribute($company_id, 3, 'gender', $value);
        }

        //最終学歴
        foreach (config('const.students.ACADEMIC_TYPE_LIST') as $key => $value) {
            $academic_achievement[] = $this->student->getStudentsCountWithCompanyIdAndAttribute($company_id, 2, 'academic_type', $value);
        }
        foreach (config('const.students.ACADEMIC_TYPE_LIST') as $key => $value) {
            $academic_retire[] = $this->student->getStudentsCountWithCompanyIdAndAttribute($company_id, 3, 'academic_type', $value);
        }

        // 出身地
        $birthplace_achievement = $this->getStudentsCountByBirthplace($graduated_students);
        $birthplace_retire = $this->getStudentsCountByBirthplace($retired_students);

        //社会人歴
        $working_history_achievement = $this->getStudentsCountByWorkingHistory($graduated_students);
        $working_history_retire = $this->getStudentsCountByWorkingHistory($retired_students);


        // 前職（業種）
        foreach (config('const.students.INDUSTRY_LIST') as $key => $value) {
            $former_job_type_achievement[] = $this->student->getStudentsCountWithCompanyIdAndAttribute($company_id, 2, 'former_job_type', $value);
        }
        foreach (config('const.students.INDUSTRY_LIST') as $key => $value) {
            $former_job_type_retire[] = $this->student->getStudentsCountWithCompanyIdAndAttribute($company_id, 3, 'former_job_type', $value);
        }

        // 前職（雇用形態）
        foreach (config('const.students.JOB_STATUS_LIST') as $key => $value) {
            $former_job_status_achievement[] = $this->student->getStudentsCountWithCompanyIdAndAttribute($company_id, 2, 'former_job_status', $value);
        }
        foreach (config('const.students.JOB_STATUS_LIST') as $key => $value) {
            $former_job_status_retire[] = $this->student->getStudentsCountWithCompanyIdAndAttribute($company_id, 3, 'former_job_status', $value);
        }

        // 転職回数
        $change_job_count_achievement = $this->getStudentsCountByChangeJobCount($graduated_students);
        $change_job_count_retire = $this->getStudentsCountByChangeJobCount($retired_students);

        // 国家資格
        foreach (config('const.students.HAVE_LIST') as $key => $value) {
            $national_qualification_flg_achievement[] = $this->student->getStudentsCountWithCompanyIdAndAttribute($company_id, 2, 'national_qualification_flg', $value);
        }
        foreach (config('const.students.HAVE_LIST') as $key => $value) {
            $national_qualification_flg_retire[] = $this->student->getStudentsCountWithCompanyIdAndAttribute($company_id, 3, 'national_qualification_flg', $value);
        }

        // 資格
        foreach (config('const.students.HAVE_LIST') as $key => $value) {
            $qualification_flg_achievement[] = $this->student->getStudentsCountWithCompanyIdAndAttribute($company_id, 2, 'qualification_flg', $value);
        }
        foreach (config('const.students.HAVE_LIST') as $key => $value) {
            $qualification_flg_retire[] = $this->student->getStudentsCountWithCompanyIdAndAttribute($company_id, 3, 'qualification_flg', $value);
        }

        // 部活動
        foreach (config('const.students.CLUB_TYPE_LIST') as $key => $value) {
            $club_type_achievement[] = $this->student->getStudentsCountWithCompanyIdAndAttribute($company_id, 2, 'club_type', $value);
        }
        foreach (config('const.students.CLUB_TYPE_LIST') as $key => $value) {
            $club_type_retire[] = $this->student->getStudentsCountWithCompanyIdAndAttribute($company_id, 3, 'club_type', $value);
        }


        return [
            'by_attendance' => ['achievement_data' => $by_attendance_achievement,
                'retire_data' => $by_attendance_retire],
            'by_month' => ['achievement_data' => $by_month_achievement,
                'retire_data' => $by_month_retire,
                'months' => $months],
            'by_curriculum' => ['retire_data' => $by_curriculum_retire,
                'labels' => $by_curriculum_labels],
            'start_age' => ['achievement_data' => $start_age_achievement,
                'retire_data' => $start_age_retire],
            'gender' => ['achievement_data' => $gender_achievement,
                'retire_data' => $gender_retire],
            'academic_type' => ['achievement_data' => $academic_achievement,
                'retire_data' => $academic_retire],
            'birthplace' => ['achievement_data' => $birthplace_achievement,
                'retire_data' => $birthplace_retire],
            'working_history' => ['achievement_data' => $working_history_achievement,
                'retire_data' => $working_history_retire],
            'former_job_type' => ['achievement_data' => $former_job_type_achievement,
                'retire_data' => $former_job_type_retire],
            'former_job_status' => ['achievement_data' => $former_job_status_achievement,
                'retire_data' => $former_job_status_retire],
            'change_job_count' => ['achievement_data' => $change_job_count_achievement,
                'retire_data' => $change_job_count_retire],
            'national_qualification_flg' => ['achievement_data' => $national_qualification_flg_achievement,
                'retire_data' => $national_qualification_flg_retire],
            'qualification_flg' => ['achievement_data' => $qualification_flg_achievement,
                'retire_data' => $qualification_flg_retire],
            'club_type' => ['achievement_data' => $club_type_achievement,
                'retire_data' => $club_type_retire],
        ];
    }

    public function getCompanyStatistics(): array
    {
        $company_graduated_students = $this->student->getListWithCompanyAttribute(2);
        $company_retired_students = $this->student->getListWithCompanyAttribute(3);
//        $company_graduated_students = $this->student->getListWithCompanyAttribute(2, true);
//        $company_retired_students = $this->student->getListWithCompanyAttribute(3, true);

        // 業種
        foreach (config('const.students.INDUSTRY_LIST') as $key => $value) {
            $company_industry_achievement[] = $this->student->getStudentsCountWithCompanyAttribute(2, 'industry', $value);
        }
        foreach (config('const.students.INDUSTRY_LIST') as $key => $value) {
            $company_industry_retire[] = $this->student->getStudentsCountWithCompanyAttribute(3, 'industry', $value);
        }

        // 従業員数
        $company_number_of_employees_achievement = $this->getStudentsCountByCompanyNumberOfEmployees($company_graduated_students);
        $company_number_of_employees_retire = $this->getStudentsCountByCompanyNumberOfEmployees($company_retired_students);

        // 設立年数
        $company_year_of_establishment_achievement = $this->getStudentsCountByCompanyYearOfEstablishment($company_graduated_students);
        $company_year_of_establishment_retire = $this->getStudentsCountByCompanyYearOfEstablishment($company_retired_students);

        // 平均年齢
        $company_average_age_achievement = $this->getStudentsCountByCompanyAverageAge($company_graduated_students);
        $company_average_age_retire = $this->getStudentsCountByCompanyAverageAge($company_retired_students);

        return [
            'company_industry' => ['achievement_data' => $company_industry_achievement,
                'retire_data' => $company_industry_retire],
            'company_number_of_employees' => ['achievement_data' => $company_number_of_employees_achievement,
                'retire_data' => $company_number_of_employees_retire],
            'company_year_of_establishment' => ['achievement_data' => $company_year_of_establishment_achievement,
                'retire_data' => $company_year_of_establishment_retire],
            'company_average_age' => ['achievement_data' => $company_average_age_achievement,
                'retire_data' => $company_average_age_retire],
        ];
    }

    public function getCompanyRankingByAchievementPercentage($order)
    {
        $companies = $this->company->getAutocompleteItem();
        $results = [];
        foreach ($companies as $key => $company) {
            $graduated_students = $this->getList($company['value'], false, 2)->count();
            $retired_students = $this->getList($company['value'], false, 3)->count();
//            $graduated_students = $this->getList($company['value'], false, 2, false, true)->count();
//            $retired_students = $this->getList($company['value'], false, 3, false, true)->count();
            if ($graduated_students == 0 && $retired_students == 0) {
                continue;
            }
            $percentage = $this->getStudentsAchievementPercentage($company['value']);
            $results['data'][$key] = [
                'company_id' => $company['value'],
                'company_name' => $company['text'],
                'percentage' => $percentage,
            ];
            $sort_keys[$key] = $results['data'][$key]['percentage'];
        }
        if (isset($results['data'])) {
            // DataTable用に評価点で並び替え
            if ($order == 'best') {
                array_multisort($sort_keys, SORT_DESC, $results['data']);
            }
            if ($order == 'worst') {
                array_multisort($sort_keys, SORT_ASC, $results['data']);
            }
            // 順位追加
            $order = 1;
            foreach ($results['data'] as $key => $value) {
                $results['data'][$key]['order'] = $order;
                $order++;
            }
        }
        return $results;
    }

    private function getStudentsCountByTotalDays($students)
    {
        list($day5, $day10, $day15, $day20, $day25, $day30, $day35, $day40, $day45, $day50, $day55, $day60, $day65, $day70, $over) = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        foreach ($students as $key => $student) {
            $attendance_count = $this->reservation->getTotalStudentReserveCount($student->id);
            if ($attendance_count <= 5) {
                $day5++;
                continue;
            }
            if ($attendance_count <= 10) {
                $day10++;
                continue;
            }
            if ($attendance_count <= 15) {
                $day15++;
                continue;
            }
            if ($attendance_count <= 20) {
                $day20++;
                continue;
            }
            if ($attendance_count <= 25) {
                $day25++;
                continue;
            }
            if ($attendance_count <= 30) {
                $day30++;
                continue;
            }
            if ($attendance_count <= 35) {
                $day35++;
                continue;
            }
            if ($attendance_count <= 40) {
                $day40++;
                continue;
            }
            if ($attendance_count <= 45) {
                $day45++;
                continue;
            }
            if ($attendance_count <= 50) {
                $day50++;
                continue;
            }
            if ($attendance_count <= 55) {
                $day55++;
                continue;
            }
            if ($attendance_count <= 60) {
                $day60++;
                continue;
            }
            if ($attendance_count <= 65) {
                $day65++;
                continue;
            }
            if ($attendance_count <= 70) {
                $day70++;
                continue;
            } else {
                $over++;
            }
        }
        return [$day5, $day10, $day15, $day20, $day25, $day30, $day35, $day40, $day45, $day50, $day55, $day60, $day65, $day70, $over];
    }

    private function getStudentsCountByMonth(?int $company_id = null)
    {
        // データ取得開始日はデータ移行後の2020年10月以降
        $start = '2020-10-01';
        $end = Carbon::now()->format('Y-m-t');
        for ($i = $start; $i <= $end; $i = date('Y-m-d', strtotime($i . '+1 month'))) {
            $months[] = date('Y年m月', strtotime($i));
            $by_month_achievement[] = $this->student->getListByEndDate($company_id, 2, $i)->count();
            $by_month_retire[] = $this->student->getListByEndDate($company_id, 3, $i)->count();
        }
        return [$months, $by_month_achievement, $by_month_retire];
    }

    private function getStudentsCountByCurriculum(?int $company_id = null, $retired_students)
    {
        $results = [];
        $curriculums = $this->curriculumService->getAutocompleteWithCompanyId($company_id);
        foreach ($curriculums as $curriculum) {
            $results[$curriculum['value']][] = $curriculum['text'];
        }
        foreach ($retired_students as $student) {
            $current = $this->curriculumService->getCurrentCurriculumAndStepByStudentId($student->id);
            foreach ($curriculums as $curriculum) {
                if ($curriculum['value'] == $current['curriculum']['id']) {
                    $results[$curriculum['value']][] = $current['curriculum']['name'];
                    $sort_keys[] = $curriculum['value'];
                }
            }
        }
        $by_curriculum_retire = [];
        $by_curriculum_labels = [];
        if ($results) {
            ksort($results);
            foreach ($results as $result) {
                $by_curriculum_retire[] = count($result) - 1;
                $by_curriculum_labels[] = $result[0];
            }
        }
        return [$by_curriculum_retire, $by_curriculum_labels];
    }

    private function getStudentsCountByStartAge($students)
    {
        list($twenty, $twentytwo, $twentyfour, $twentysix, $twentyeight, $thirty, $over, $none) = [0, 0, 0, 0, 0, 0, 0, 0];
        foreach ($students as $key => $student) {
            if ($student->birthday === null) {
                $none++;
                continue;
            }
            if ($student->start_date && $student->birthday) {
                $start_date = new Carbon($student->start_date);
                $birthday = new Carbon($student->birthday);
                $start_age = $start_date->diffInYears($birthday);
                if ($start_age === null) {
                    continue;
                }
                if ($start_age <= 20) {
                    $twenty++;
                    continue;
                }
                if ($start_age <= 22) {
                    $twentytwo++;
                    continue;
                }
                if ($start_age <= 24) {
                    $twentyfour++;
                    continue;
                }
                if ($start_age <= 26) {
                    $twentysix++;
                    continue;
                }
                if ($start_age <= 28) {
                    $twentyeight++;
                    continue;
                }
                if ($start_age <= 30) {
                    $thirty++;
                    continue;
                } else {
                    $over++;
                }
            }
        }
        return [$twenty, $twentytwo, $twentyfour, $twentysix, $twentyeight, $thirty, $over, $none];
    }

    private function getStudentsCountByBirthplace($students)
    {
        list($hokkaido, $tohoku, $kanto, $chubu, $kinki, $chugoku, $shikoku, $kyushu, $none) = [0, 0, 0, 0, 0, 0, 0, 0, 0];
        foreach ($students as $key => $student) {
            if ($student->birthplace === null) {
                $none++;
                continue;
            }
            if ($student->birthplace == 1) {
                $hokkaido++;
                continue;
            }
            if ($student->birthplace <= 7) {
                $tohoku++;
                continue;
            }
            if ($student->birthplace <= 14) {
                $kanto++;
                continue;
            }
            if ($student->birthplace <= 23) {
                $chubu++;
                continue;
            }
            if ($student->birthplace <= 28) {
                $kinki++;
                continue;
            }
            if ($student->birthplace <= 35) {
                $chugoku++;
                continue;
            }
            if ($student->birthplace <= 39) {
                $shikoku++;
                continue;
            } else {
                $kyushu++;
            }
        }
        return [$hokkaido, $tohoku, $kanto, $chubu, $kinki, $chugoku, $shikoku, $kyushu, $none];
    }

    private function getStudentsCountByWorkingHistory($students)
    {
        list($zero, $two, $four, $six, $eight, $ten, $over, $none) = [0, 0, 0, 0, 0, 0, 0, 0];
        foreach ($students as $key => $student) {
            if ($student->working_history === null) {
                $none++;
                continue;
            }
            if ($student->working_history == 0) {
                $zero++;
                continue;
            }
            if ($student->working_history <= 2) {
                $two++;
                continue;
            }
            if ($student->working_history <= 4) {
                $four++;
                continue;
            }
            if ($student->working_history <= 6) {
                $six++;
                continue;
            }
            if ($student->working_history <= 8) {
                $eight++;
                continue;
            }
            if ($student->working_history <= 10) {
                $ten++;
                continue;
            } else {
                $over++;
            }
        }
        return [$zero, $two, $four, $six, $eight, $ten, $over, $none];
    }

    private function getStudentsCountByChangeJobCount($students)
    {
        list($zero, $one, $two, $three, $four, $over, $none) = [0, 0, 0, 0, 0, 0, 0];
        foreach ($students as $key => $student) {
            if ($student->change_job_count === null) {
                $none++;
                continue;
            }
            if ($student->change_job_count == 0) {
                $zero++;
                continue;
            }
            if ($student->change_job_count == 1) {
                $one++;
                continue;
            }
            if ($student->change_job_count == 2) {
                $two++;
                continue;
            }
            if ($student->change_job_count == 3) {
                $three++;
                continue;
            }
            if ($student->change_job_count == 4) {
                $four++;
                continue;
            } else {
                $over++;
            }
        }
        return [$zero, $one, $two, $three, $four, $over, $none];
    }

    private function getStudentsCountByCompanyAverageAge($students)
    {
        list($twentyfive, $thirty, $thirtyfive, $fourty, $fourtyfive, $over, $none) = [0, 0, 0, 0, 0, 0, 0];
        foreach ($students as $key => $student) {
            if ($student->average_age === null) {
                $none++;
                continue;
            }
            if ($student->average_age <= 25) {
                $twentyfive++;
                continue;
            }
            if ($student->average_age <= 30) {
                $thirty++;
                continue;
            }
            if ($student->average_age <= 35) {
                $thirtyfive++;
                continue;
            }
            if ($student->average_age <= 40) {
                $fourty++;
                continue;
            }
            if ($student->average_age <= 45) {
                $fourtyfive++;
                continue;
            } else {
                $over++;
            }
        }
        return [$twentyfive, $thirty, $thirtyfive, $fourty, $fourtyfive, $over, $none];
    }

    private function getStudentsCountByCompanyNumberOfEmployees($students)
    {
        list($ten, $thirty, $fifty, $hundred, $threehundred, $fivehundred, $thousand, $over, $none) = [0, 0, 0, 0, 0, 0, 0, 0, 0];
        foreach ($students as $key => $student) {
            if ($student->number_of_employees === null) {
                $none++;
                continue;
            }
            if ($student->number_of_employees <= 10) {
                $ten++;
                continue;
            }
            if ($student->number_of_employees <= 30) {
                $thirty++;
                continue;
            }
            if ($student->number_of_employees <= 50) {
                $fifty++;
                continue;
            }
            if ($student->number_of_employees <= 100) {
                $hundred++;
                continue;
            }
            if ($student->number_of_employees <= 300) {
                $threehundred++;
                continue;
            }
            if ($student->number_of_employees <= 500) {
                $fivehundred++;
                continue;
            }
            if ($student->number_of_employees <= 1000) {
                $thousand++;
                continue;
            } else {
                $over++;
            }
        }
        return [$ten, $thirty, $fifty, $hundred, $threehundred, $fivehundred, $thousand, $over, $none];
    }

    private function getStudentsCountByCompanyYearOfEstablishment($students)
    {
        list($three, $five, $ten, $twenty, $thirty, $fifty, $over, $none) = [0, 0, 0, 0, 0, 0, 0, 0];
        foreach ($students as $key => $student) {
            if ($student->number_of_employees === null) {
                $none++;
                continue;
            }
            if ($student->number_of_employees <= 3) {
                $three++;
                continue;
            }
            if ($student->number_of_employees <= 5) {
                $five++;
                continue;
            }
            if ($student->number_of_employees <= 10) {
                $ten++;
                continue;
            }
            if ($student->number_of_employees <= 20) {
                $twenty++;
                continue;
            }
            if ($student->number_of_employees <= 30) {
                $thirty++;
                continue;
            }
            if ($student->number_of_employees <= 50) {
                $fifty++;
                continue;
            } else {
                $over++;
            }
        }
        return [$three, $five, $ten, $twenty, $thirty, $fifty, $over, $none];
    }

}
