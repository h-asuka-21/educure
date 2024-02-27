<?php


namespace App\Models\Question\Repository;

use App\Models\AbstractRepository;
use App\Models\Question\Question;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QuestionRepository extends AbstractRepository implements QuestionRepositoryInterface
{
    /**
     * @param int $company_id
     * @return array
     */
    public function getList(?int $company_id): array
    {
        $query = request()->query();
        return Question::query()
            ->paginate($this->getPerPage($query))
            ->toArray();
    }

    /**
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function find(int $id)
    {
        return Question::query()->find($id);
    }


    public function save(Question $question): ?Question
    {
        return DB::transaction(function () use ($question) {
            try {
                return Question::query()->updateOrCreate(
                    ['id' => $question->id],
                    [
                        'name' => $question->name,
                        'test_id' => $question->test_id,
                        'content' => $question->content,
                        'image' => $question->image,
                        'choice1' => $question->choice1,
                        'choice2' => $question->choice2,
                        'choice3' => $question->choice3,
                        'choice4' => $question->choice4,
                        'answer' => $question->answer,
                        'order' => $question->order
                    ]
                );
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                return false;
            }
        });
    }

    public function getListByTestId(int $test_id): array
    {
        return Question::query()
            ->where('test_id', $test_id)
            ->orderBy('order')
            ->get()->toArray();
    }

    public function getModelAndSetParams(array $data, ?int $test_id, int $order): Question
    {
        if (isset($data['id'])) {
            $question = $this->find($data['id']);
        } else {
            $question = new Question();
        }
        $question->name = $data['name'];
        $question->test_id = $test_id;
        $question->content = $data['content'];
        $question->answer = $data['answer'];
        $question->image = $data['image'] ?? null;
        $question->choice1 = $data['choice1'] ?? null;
        $question->choice2 = $data['choice2'] ?? null;
        $question->choice3 = $data['choice3'] ?? null;
        $question->choice4 = $data['choice4'] ?? null;
        $question->order = $order;
        return $question;
    }

    public function delete(int $id): bool
    {
        try {
            $target = Question::query()->find($id);
            if ($target === null) {
                return false;
            }
            return $target->delete();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }
}
