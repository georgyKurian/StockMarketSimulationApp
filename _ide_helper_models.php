<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Day
 *
 * @property int $id
 * @property int $day_index
 * @property int $start_at_intraday_id
 * @property string $buy_at_price
 * @property string $sell_at_price
 * @property int $end_at_intraday_id
 * @property string $exit_price
 * @property float $profit
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Day newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Day newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Day query()
 * @method static \Illuminate\Database\Eloquent\Builder|Day whereBuyAtPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Day whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Day whereDayIndex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Day whereEndAtIntradayId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Day whereExitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Day whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Day whereProfit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Day whereSellAtPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Day whereStartAtIntradayId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Day whereUpdatedAt($value)
 */
	class Day extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Intraday
 *
 * @property int $id
 * @property int $day_index
 * @property int $time
 * @property float $open
 * @property float $high
 * @property float $low
 * @property float $close
 * @property float $volume
 * @property float|null $vw_avg_price
 * @property float $recorded_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Domain\Strategy1Analysics\Collections\IntradayCollection|static[] all($columns = ['*'])
 * @method static \Domain\Strategy1Analysics\Collections\IntradayCollection|static[] get($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder|Intraday newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Intraday newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Intraday query()
 * @method static \Illuminate\Database\Eloquent\Builder|Intraday whereClose($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Intraday whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Intraday whereDayIndex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Intraday whereHigh($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Intraday whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Intraday whereLow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Intraday whereOpen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Intraday whereRecordedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Intraday whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Intraday whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Intraday whereVolume($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Intraday whereVwAvgPrice($value)
 */
	class Intraday extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Model
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Model newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model query()
 */
	class Model extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

