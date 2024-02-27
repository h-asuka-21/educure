<?php


namespace App\Models\SlackChannel\Repository;

use App\Models\SlackChannel\SlackChannel;

interface SlackChannelRepositoryInterface
{
    /**
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function getSlackChannel(int $company_id);

    public function getSlackChannelbyToken(array $company_ids);

    /**
     * @param SlackChannel $schedule
     * @return bool
     */
    public function save(SlackChannel $schedule): bool;


    public function delete();
}
