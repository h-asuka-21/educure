<?php


namespace App\Models\SlackChannel\Repository;

use App\Models\AbstractRepository;
use App\Models\SlackChannel\SlackChannel;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;


class SlackChannelRepository extends AbstractRepository implements SlackChannelRepositoryInterface
{
    /**
     * @param int $company_id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function getSlackChannel(int $company_id)
    {
        return SlackChannel::query()
            ->where('company_id', $company_id)
            ->first();
    }


    public function getSlackChannelbyToken(array $company_ids)
    {
        return SlackChannel::query()
            ->select([
                'channel_id'
            ])
            ->whereIn('company_id', $company_ids)
            ->first();
    }

    /**
     * @param SlackChannel $slack_channel
     * @return bool
     */
    public function save(SlackChannel $slack_channel): bool
    {
        return DB::transaction(function () use ($slack_channel) {
            SlackChannel::updateOrCreate(
                ['id' => $slack_channel->id],
                [
                    'company_id' => $slack_channel->company_id,
                    'channel_id' => $slack_channel->channel_id,
                ]
            );
            return true;
        });
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete()
    {
        return DB::table('slack_channels')->truncate();
    }
}
