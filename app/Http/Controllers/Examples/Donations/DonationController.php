<?php

namespace App\Http\Controllers\Donations;

use Exception;
use Throwable;
use App\Models\Target;
use App\Models\Donation;
use App\Http\Controllers\Controller;
use App\Enums\Payments\PaymentStatusEnum;
use App\Models\Audio;
use App\Models\Gif;
use App\Models\TopDonation;
use App\Models\TopDonator;
use App\Models\TopGreeting;
use App\Models\TopTimer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DonationController extends Controller
{
	/**
	 * Change decimal to int amount
	 *
	 * @param float $decimal
	 * @return integer
	 */
	function toCents(float $decimal): int
	{
		return number_format($decimal * 100, 0, '.', '');
	}

	/**
	 * Change int amount to decimal
	 *
	 * @param int $amount
	 * @return float
	 */
	public function toDecimal($amount)
	{
		return number_format(($amount / 100), 2, '.', '');
	}

	/**
	 * OBS API current target
	 */
	public function targetToday()
	{
		try {
			$i = Target::latest('id')->first();

			return $this->toDecimal($i->amount);
			// return response($this->toDecimal($i->amount ?? 0))->header('Content-Type', 'text/plain')->header('Access-Control-Allow-Origin', '*');
		} catch (Throwable $e) {
			report($e);
			return 0;
			// return response($this->toDecimal(0))->header('Content-Type', 'text/plain');
		}
	}

	/**
	 * OBS API today profit
	 */
	public function profitToday()
	{
		try {
			$amount = Donation::query()
				->where('status', PaymentStatusEnum::COMPLETED->value)
				->whereDate('created_at', '=', now()->format('Y-m-d'))
				->sum('amount');

			return $this->toDecimal($amount) ?? 0;
			// return response($this->toDecimal($amount))->header('Content-Type', 'text/plain')->header('Access-Control-Allow-Origin', '*');
		} catch (Throwable $e) {
			report($e);
			return 0;
			// return response($this->toDecimal(0))->header('Content-Type', 'text/plain');
		}
	}

	/**
	 * OBS API 7 days profit
	 */
	public function profitWeek()
	{
		try {
			$amount = Donation::query()
				->where('status', PaymentStatusEnum::COMPLETED->value)
				->where('created_at', '>=', now()->subDays(7))
				->sum('amount');

			return $this->toDecimal($amount) ?? 0;
			// return response($this->toDecimal($amount))->header('Content-Type', 'text/plain')->header('Access-Control-Allow-Origin', '*');
		} catch (Throwable $e) {
			report($e);
			return 0;
			// return response($this->toDecimal(0))->header('Content-Type', 'text/plain');
		}
	}

	/**
	 * OBS API month profit
	 */
	public function profitMonth()
	{
		try {
			$i = Donation::query()
				->selectRaw('sum(amount) as donate_amount')
				->where('status', PaymentStatusEnum::COMPLETED->value)
				->whereYear('created_at', now()->year)
				->whereMonth('created_at', now()->month) // Only current month
				// ->where('created_at', '>=', now()->subDays(30)) // 30 days
				->first();

			return $this->toDecimal($i->donate_amount);
		} catch (Throwable $e) {
			report($e);
			return $this->toDecimal(0);
		}
	}

	/**
	 * OBS API all profit
	 */
	public function profitAll()
	{
		try {
			$i = Donation::query()
				->selectRaw('sum(amount) as donate_amount')
				->where('status', PaymentStatusEnum::COMPLETED->value)
				->first();

			return $this->toDecimal($i->donate_amount);
		} catch (Throwable $e) {
			report($e);
			return $this->toDecimal(0);
		}
	}

	/**
	 * OBS API count unseen donations
	 */
	public function getGreetingUnseen()
	{
		try {
			$i = Donation::query()
				->where('status', PaymentStatusEnum::COMPLETED->value)
				->where('is_seen', 0)
				->count();

			return $i ?? 0;
		} catch (Throwable $e) {
			report($e);
			return 0;
		}
	}

	public function bestsDonationsLine()
	{
		$limit = 5;
		$str = '';

		try {
			// Email => amount pairs
			$all = Donation::query()
				->selectRaw('sum(amount) as donate_amount,email')
				->where('status', PaymentStatusEnum::COMPLETED->value)
				->whereDate('created_at', '=', now()->format('Y-m-d'))
				->orderBy('donate_amount', 'DESC')
				->groupBy('email')
				->take($limit)
				->pluck('donate_amount', 'email');

			// Email => name pairs
			$all_names = DB::table('donations')
				->select('name', 'email', 'currency')
				->whereDate('created_at', '=', now()->format('Y-m-d'))
				->whereIn('email', $all->keys())
				->orderBy('id', 'ASC')
				->pluck('name', 'email');

			if ($all->isEmpty()) {
				return response('')->header('Content-Type', 'text/plain');
			}

			$nr = 1;
			foreach ($all as $k => $amount) {
				$name = $all_names[$k] ?? fake()->name();
				$str .=  $nr . '. ' . $name . ': ' . $this->toDecimal($amount) .  ' ' . strtoupper(env('PAYU_CURRENCY')) . " ";
				$nr++;
			}

			return $str;
			// return response($str)->header('Content-Type', 'text/plain');
		} catch (Throwable $e) {
			report($e);
			return '';
			// return response('')->header('Content-Type', 'text/plain');
		}
	}

	public function bestsDonations()
	{
		$limit = 5;
		$str = '';

		try {
			// Email => amount pairs
			$all = Donation::query()
				->selectRaw('sum(amount) as donate_amount,email')
				->where('status', PaymentStatusEnum::COMPLETED->value)
				->whereDate('created_at', '=', now()->format('Y-m-d'))
				->orderBy('donate_amount', 'DESC')
				->groupBy('email')
				->take($limit)
				->pluck('donate_amount', 'email');

			// Email => name pairs
			$all_names = DB::table('donations')
				->select('name', 'email', 'currency')
				->whereDate('created_at', '=', now()->format('Y-m-d'))
				->whereIn('email', $all->keys())
				->orderBy('id', 'ASC')
				->pluck('name', 'email');

			if ($all->isEmpty()) {
				return response('💰😍💰')->header('Content-Type', 'text/plain');
			}

			$nr = 1;
			foreach ($all as $k => $amount) {
				$name = $all_names[$k] ?? fake()->name();
				$str .=  $nr . '. ' . $name . ': ' . $this->toDecimal($amount) .  ' ' . strtoupper(env('PAYU_CURRENCY')) . "\r\n";
				$nr++;
			}

			return response($str)->header('Content-Type', 'text/plain');
		} catch (Throwable $e) {
			report($e);
			return response('')->header('Content-Type', 'text/plain');
		}
	}

	public function bestsDonationsJson()
	{
		$limit = 5;
		$users = [];

		try {
			// List email => amount
			$all = Donation::query()
				->selectRaw('sum(amount) as donate_amount,email')
				->where('status', PaymentStatusEnum::COMPLETED->value)
				->whereDate('created_at', '=', now()->format('Y-m-d'))
				->orderBy('donate_amount', 'DESC')
				->groupBy('email')
				->take($limit)
				->pluck('donate_amount', 'email');

			// List email => name
			$all_names = DB::table('donations')
				->select('name', 'email', 'currency')
				->whereDate('created_at', '=', now()->format('Y-m-d'))
				->whereIn('email', $all->keys())
				->orderBy('id', 'ASC')
				->pluck('name', 'email');

			foreach ($all as $k => $amount) {
				$name = $all_names[$k] ?? fake()->name();

				$users[] = [
					'name' => $name,
					'donate_amount' => $this->toDecimal($amount),
					'currency' => strtoupper(env('PAYU_CURRENCY')) ?? 'PLN'
				];
			}

			return response()->json([
				'users' => $users
			]);
		} catch (Throwable $e) {
			report($e);
			return response('')->json([
				'users' => []
			]);
		}
	}

	public function bestDonation()
	{
		try {
			$i = Donation::query()
				->selectRaw('amount as donate_amount, name, currency, created_at')
				->where('status', PaymentStatusEnum::COMPLETED->value)
				->whereDate('created_at', '=', now()->format('Y-m-d'))
				->latest('amount')->first();

			if ($i) {
				$str = $i->name . ' ' . $this->toDecimal($i->donate_amount) . ' ' . $i->currency;

				return response($str)->header('Content-Type', 'text/plain');
			}

			return response('')->header('Content-Type', 'text/plain');
		} catch (Throwable $e) {
			report($e);
			return response('')->header('Content-Type', 'text/plain');
		}
	}

	public function bestDonationJson()
	{
		try {
			$i = Donation::query()
				->selectRaw('amount as donate_amount, name, currency, created_at')
				->where('status', PaymentStatusEnum::COMPLETED->value)
				->whereDate('created_at', '=', now()->format('Y-m-d'))
				->latest('amount')->first();

			if ($i) {
				return response()->json([
					'name' => $i->name,
					'donate_amount' => $this->toDecimal($i->donate_amount),
					'currency' => $i->currency,
					'created_at' => $i->created_at->format('Y-m-d H:i:s'),
				]);
			}

			return response()->json([
				'error' => 'Empty',
			], 422);
		} catch (Throwable $e) {
			report($e);
			return response()->json([
				'error' => 'Empty',
			], 422);
		}
	}

	public function lastDonation()
	{
		try {
			$i = Donation::query()
				->selectRaw('amount as donate_amount, name, currency, created_at')
				->where('status', PaymentStatusEnum::COMPLETED->value)
				->whereDate('created_at', '=', now()->format('Y-m-d'))
				->latest('id')->first();

			if ($i) {
				$str = $i->name . ' ' . $this->toDecimal($i->donate_amount) . ' ' . $i->currency;

				return response($str)->header('Content-Type', 'text/plain');
			}

			return response('')->header('Content-Type', 'text/plain');
		} catch (Throwable $e) {
			report($e);
			return response('')->header('Content-Type', 'text/plain');
		}
	}

	public function lastDonationJson()
	{
		try {
			$i = Donation::query()
				->selectRaw('amount as donate_amount, name, currency, created_at')
				->where('status', PaymentStatusEnum::COMPLETED->value)
				->whereDate('created_at', '=', now()->format('Y-m-d'))
				->latest('id')->first();

			if ($i) {
				return response()->json([
					'name' => $i->name,
					'donate_amount' => $this->toDecimal($i->donate_amount),
					'currency' => $i->currency,
					'created_at' => $i->created_at->format('Y-m-d H:i:s'),
				]);
			}

			return response()->json([
				'error' => 'Empty',
			], 422);
		} catch (Throwable $e) {
			report($e);
			return response()->json([
				'error' => 'Empty',
			], 422);
		}
	}

	public function greetingsJson()
	{
		$limit = 3;
		$users = [];

		try {
			$arr = Donation::query()
				->selectRaw('amount as donate_amount, name, message, currency, is_seen, gif, created_at')
				->whereDate('created_at', '>=' . now()->subDays(1)->format('Y-m-d'))
				->orWhereDate('created_at', '<=', now()->format('Y-m-d'))
				->where('status', PaymentStatusEnum::COMPLETED->value)
				->where('is_seen', 0)
				->oldest('id')
				->take($limit)
				->get();

			foreach ($arr as $i) {
				$users[] = [
					'name' => $i->name,
					'message' => $i->message,
					'donate_amount' => $this->toDecimal($i->donate_amount),
					'currency' => $i->currency,
					'is_seen' => $i->is_seen,
					'created_at' => $i->created_at->format('Y-m-d H:i:s'),
				];
			}

			return response()->json([
				'users' => $users
			]);
		} catch (Throwable $e) {
			report($e);
			return response()->json([
				'users' => [],
			], 422);
		}
	}

	public function getGreeting()
	{
		try {
			$i = Donation::query()
				->selectRaw('amount as donate_amount, payment_id, gif, name, message, currency, is_seen, created_at')
				->whereDate('created_at', '>=' . now()->subDays(1)->format('Y-m-d'))
				->orWhereDate('created_at', '<=', now()->format('Y-m-d'))
				->where('status', PaymentStatusEnum::COMPLETED->value)
				->where('is_seen', '0')
				->oldest('id')
				->first();

			if ($i) {
				return response()->json([
					'seen_id' => $i->payment_id,
					'name' => $i->name,
					'message' => $i->message,
					'donate_amount' => $this->toDecimal($i->donate_amount),
					'currency' => $i->currency,
					'is_seen' => $i->is_seen,
					'gif' => $i->gif,
					'created_at' => $i->created_at->format('Y-m-d H:i:s'),
				]);
			}

			return response()->json([
				'error' => 'Empty',
			], 422);
		} catch (Throwable $e) {
			report($e);
			return response()->json([
				'error' => 'Empty',
			], 422);
		}
	}

	public function setGreetingSeen(Request $request)
	{
		if (Str::isUuid($request->uuid, 7)) {
			return response()->json([
				'error' => 'Invalid id',
			], 422);
		}

		try {
			$i = Donation::where('payment_id', $request->uuid)->first();

			if ($i) {
				$i->is_seen = 1;
				$i->save();

				return response()->json([
					'message' => 'Updated',
				]);
			}

			return response()->json([
				'error' => 'Empty',
			], 422);
		} catch (Throwable $e) {
			report($e);
			return response()->json([
				'error' => 'Empty',
			], 422);
		}
	}

	public function latestDonationsJson()
	{
		try {
			$i = Donation::query()
				->selectRaw('amount as donate_amount, id, payment_id, name, message, status, currency, gateway, created_at')
				->whereDate('created_at', '=', now()->format('Y-m-d'))
				->latest('id')->take(25)->get();

			return $i;
		} catch (Throwable $e) {
			report($e);
			return response()->json([
				'error' => 'Empty',
			], 422);
		}
	}

	/**
	 * Audio list
	 */
	public function audioList()
	{
		return Audio::oldest('id')->get();
	}

	/**
	 * Gifs list
	 */
	public function gifList()
	{
		return Gif::oldest('id')->get();
	}

	/**
	 * Youtube live viewers
	 *
	 * $url = 'https://youtube.googleapis.com/youtube/v3/videos?part=statistics%2Cstatus%2CliveStreamingDetails&id={VIDEO_ID}&key={KEY}';
	 */
	public function youtubeLiveCurrentViewers()
	{
		try {
			$count = Cache::store('file')
				->remember(
					'youtubeConcurrentViewers',
					config('access.youtube.current_refresh', 60),
					function () {
						$key = env('YOUTUBE_API_KEY', '');
						$videoId = Storage::disk('local')->get(config('access.youtube.current', 'youtube-current.txt'));
						if (!empty($videoId) && !empty($key)) {
							$res = Http::get('https://youtube.googleapis.com/youtube/v3/videos?part=liveStreamingDetails&id=' . $videoId . '&key=' . $key)->json();
							$cnt = $res['items'][0]['liveStreamingDetails']['concurrentViewers'] ?? 0;
						}
						return  $cnt ?? 0;
					}
				);
		} catch (Throwable $e) {
			report($e);
			return 0;
		}

		return $count;
	}

	/**
	 * Get target details
	 */
	public function htmlTarget(Target $target)
	{
		$period = $target->period ?? 'day';

		if ($period == 'hour6') {
			$amount = Donation::query()
				->where('status', PaymentStatusEnum::COMPLETED->value)
				->where('created_at', '>=', now()->subHours(6))
				->sum('amount');
		}

		if ($period == 'hour12') {
			$amount = Donation::query()
				->where('status', PaymentStatusEnum::COMPLETED->value)
				->where('created_at', '>=', now()->subHours(12))
				->sum('amount');
		}

		if ($period == 'hour24') {
			$amount = Donation::query()
				->where('status', PaymentStatusEnum::COMPLETED->value)
				->where('created_at', '>=', now()->subHours(24))
				->sum('amount');
		}

		if ($period == 'day') {
			$amount = Donation::query()
				->where('status', PaymentStatusEnum::COMPLETED->value)
				->whereDate('created_at', '=', now()->format('Y-m-d'))
				->sum('amount');
		}

		if ($period == 'week') {
			$amount = Donation::query()
				->where('status', PaymentStatusEnum::COMPLETED->value)
				->where('created_at', '>=', now()->subHours(24 * 7))
				->sum('amount');
		}

		if ($period == 'month') {
			$amount = Donation::query()
				->where('status', PaymentStatusEnum::COMPLETED->value)
				->where('created_at', '>=', now()->subDays(30))
				->sum('amount');
		}

		if ($period == 'year') {
			$amount = Donation::query()
				->where('status', PaymentStatusEnum::COMPLETED->value)
				->where('created_at', '>=', now()->subDays(365))
				->sum('amount');
		}

		$font = explode(':', $target->text_font);

		return response()->json([
			'target' => $target ?? null,
			'total' => $target->amount ?? 0,
			'profit' => $amount ?? 0,
			// Google font name and weight from font Orbitron:wght@400..900
			'font_name' => $font[0] ?? 'Poppins',
			'font_weight' => !empty($font[1]) ? ':' . $font[1] : '',
		]);
	}

	/**
	 * Get top donators details
	 */
	public function htmlDonators($id)
	{
		$model = TopDonator::find($id);

		$period = $model->period ?? 'day';
		$limit = $model->show ?? 5;
		$donators = [];
		$nr = 1;

		$all = Donation::query()->selectRaw('sum(amount) as donate_amount,email')->where('status', PaymentStatusEnum::COMPLETED->value);

		if ($period == 'day') {
			$all->whereDate('created_at', '=', now()->format('Y-m-d'));
		}

		if ($period == 'hour6') {
			$all->where('created_at', '>=', now()->subHours(6));
		}

		if ($period == 'hour12') {
			$all->where('created_at', '>=', now()->subHours(12));
		}

		if ($period == 'hour24') {
			$all->where('created_at', '>=', now()->subHours(24));
		}

		if ($period == 'week') {
			$all->where('created_at', '>=', now()->subDays(7));
		}

		if ($period == 'month') {
			$all->where('created_at', '>=', now()->subDays(30));
		}

		if ($period == 'year') {
			$all->where('created_at', '>=', now()->subDays(365));
		}

		$list = $all->orderBy('donate_amount', 'DESC')->groupBy('email')->take($limit)->pluck('donate_amount', 'email');

		// Next: Email => Name pairs
		$all_names = DB::table('donations')->select('name', 'email', 'currency')->whereIn('email', $list->keys());

		if ($period == 'day') {
			$all_names->whereDate('created_at', '=', now()->format('Y-m-d'));
		}

		if ($period == 'hour6') {
			$all_names->where('created_at', '>=', now()->subHours(6));
		}

		if ($period == 'hour12') {
			$all_names->where('created_at', '>=', now()->subHours(12));
		}

		if ($period == 'hour24') {
			$all_names->where('created_at', '>=', now()->subHours(24));
		}

		if ($period == 'week') {
			$all_names->where('created_at', '>=', now()->subDays(7));
		}

		if ($period == 'month') {
			$all_names->where('created_at', '>=', now()->subDays(30));
		}

		if ($period == 'year') {
			$all_names->where('created_at', '>=', now()->subDays(365));
		}

		$names = $all_names->orderBy('id', 'ASC')->pluck('name', 'email');

		// Concat text
		foreach ($list as $k => $amount) {
			$name = $names[$k] ?? fake()->name();
			$donators[] =  [
				'nr' => $nr,
				'name' => $name,
				'amount' => $this->toDecimal($amount) .  ' ' . strtoupper(env('PAYU_CURRENCY'))
			];
			$nr++;
		}

		$font = explode(':', $model->text_font);

		return response()->json([
			'settings' => $model,
			'donators' => $donators,
			// Google font name and weight from font Orbitron:wght@400..900
			'font_name' => $font[0] ?? 'Poppins',
			'font_weight' => !empty($font[1]) ? ':' . $font[1] : '',
		]);
	}

	/**
	 * Get top donators details
	 */
	public function htmlDonations($id)
	{
		try {
			// $model = TopDonator::find($id);
			$model = TopDonation::find($id);

			$period = $model->period ?? 'day';
			$limit = $model->show ?? 5;
			$donators = [];
			$nr = 1;

			$all = Donation::query()->selectRaw('amount,email,name')->where('status', PaymentStatusEnum::COMPLETED->value);

			if ($period == 'day') {
				$all->whereDate('created_at', '=', now()->format('Y-m-d'));
			}

			if ($period == 'hour6') {
				$all->where('created_at', '>=', now()->subHours(6));
			}

			if ($period == 'hour12') {
				$all->where('created_at', '>=', now()->subHours(12));
			}

			if ($period == 'hour24') {
				$all->where('created_at', '>=', now()->subHours(24));
			}

			if ($period == 'week') {
				$all->where('created_at', '>=', now()->subDays(7));
			}

			if ($period == 'month') {
				$all->where('created_at', '>=', now()->subDays(30));
			}

			if ($period == 'year') {
				$all->where('created_at', '>=', now()->subDays(365));
			}

			$list = $all->orderBy('amount', 'DESC')->take($limit)->get();

			// Concat text
			foreach ($list as $i) {
				$donators[] =  [
					'nr' => $nr,
					'name' => $i->name,
					'amount' => $this->toDecimal($i->amount) .  ' ' . strtoupper(env('PAYU_CURRENCY'))
				];
				$nr++;
			}

			$font = explode(':', $model->text_font);

			return response()->json([
				'settings' => $model,
				'donators' => $donators,
				// Google font name and weight from font Orbitron:wght@400..900
				'font_name' => $font[0] ?? 'Poppins',
				'font_weight' => !empty($font[1]) ? ':' . $font[1] : '',
			]);
		} catch (Throwable $e) {
			report($e);
			return response()->json([
				'error' => 'Empty',
			], 422);
		}
	}

	/**
	 * Get top donators details
	 */
	public function htmlTimers($id)
	{
		try {
			$model = TopTimer::find($id);

			$font = explode(':', $model->text_font);

			return response()->json([
				'settings' => $model,
				// Google font name and weight from font Orbitron:wght@400..900
				'font_name' => $font[0] ?? 'Poppins',
				'font_weight' => !empty($font[1]) ? ':' . $font[1] : '',
			]);
		} catch (Throwable $e) {
			report($e);
			return response()->json([
				'error' => 'Empty',
			], 422);
		}
	}

	/**
	 * Get greeting details
	 */
	public function htmlGreetings($id)
	{
		try {
			$model = TopGreeting::find($id);

			$font1 = explode(':', $model->font1);
			$font2 = explode(':', $model->font2);
			$font3 = explode(':', $model->font3);

			return response()->json([
				'settings' => $model,
				// Google font name and weight from font Orbitron:wght@400..900
				'font1_name' => $font1[0] ?? 'Poppins',
				'font1_weight' => !empty($font1[1]) ? ':' . $font1[1] : '',
				'font2_name' => $font2[0] ?? 'Poppins',
				'font2_weight' => !empty($font2[1]) ? ':' . $font2[1] : '',
				'font3_name' => $font3[0] ?? 'Poppins',
				'font3_weight' => !empty($font3[1]) ? ':' . $font3[1] : '',
			]);
		} catch (Throwable $e) {
			report($e);
			return response()->json([
				'error' => 'Empty',
			], 422);
		}
	}
}
