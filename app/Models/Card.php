<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use QCS\LaravelApi\Models\BaseModel;
use QCS\LaravelApi\Traits\ResultTrait;

class Card extends BaseModel
{
	protected $table = 'card';

    use HasFactory,SoftDeletes,ResultTrait;

	/**
	 * @param string $cardNumber
	 * @param int $point
	 * @return void
	 * @Another Edward Yu 2022/1/16下午7:32
	 */
	public static function validateCard(string $cardNumber, int $point): string
	{
		$card = self::query()
			->where('card_number', $cardNumber)
			->first();

		if (!$card) {
			return '卡密不存在！';
		}


		if (strtotime($card->expire_date) < time()){
			return  '卡密已过期！';
		}


		if (($card->points - $point) < 0 && $card->points !== -1){
			return  '卡密积分不足！';
		}

		return '';
	}


	/**
	 * @param string $cardNumber
	 * @param int $point
	 * @Another Edward Yu 2022/1/17上午12:45
	 */
	public static function reducePoint(string $cardNumber, int $point): void
	{
		//减除积分
		$card = self::query()
			->where('card_number', $cardNumber)
			->first();

		if (!$card) {
			return;
		}

		if ($card->points === -1) {
			return;
		}

		$card->points -= $point;
		$card->save();
	}
}
