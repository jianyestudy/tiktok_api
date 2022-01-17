<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use QCS\LaravelApi\Models\BaseModel;
use QCS\LaravelApi\Traits\ResultTrait;

class Card extends Basemodel
{
	protected $table = 'card';

    use HasFactory,SoftDeletes,ResultTrait;

	/**
	 * @param string $cardNumber
	 * @return void
	 * @Another Edward Yu 2022/1/16下午7:32
	 */
	public static function validateCard(string $cardNumber): string
	{
		$card = self::query()
			->where('card_number', $cardNumber)
			->first();

		if (!$card) {
			$error =  '卡密不存在！';
		}


		if (strtotime($card->expire_date) < time()){
			$error =  '卡密已过期！';
		}


		if ($card->points <= 0 && $card->points !== -1){
			$error =  '卡密积分不足！';
		}

		return $error ?? '';
	}


	/**
	 * @param string $cardNumber
	 * @Another Edward Yu 2022/1/17上午12:45
	 */
	public static function reducePoint(string $cardNumber): void
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

		$card->points -= env('TIKTOK_USER_POINT');
		$card->save();
	}
}
