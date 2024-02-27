<?php

namespace App\Models\SlackChannel;

use App\Models\AbstractModel;


/**
 * Class SlackChannel
 * @package App\Models\SlackChannel
 * @property int $company_id
 * @property int $channel_id
 */
class SlackChannel extends AbstractModel
{
    protected $fillable = [
        'company_id',
        'channel_id',
    ];
}
