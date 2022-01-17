<?php

namespace App\Http\Requests;

use QCS\LaravelApi\Validates\BaseValidate;

class CardRequest extends BaseValidate
{

	/**
	 * @Another Edward Yu 2022/1/16下午6:40
	 * @return \string[][]
	 */
	public function rules(): array
	{
		return [
			'count'  => ['bail', 'required', 'numeric', 'min:1'],
			'points' => ['bail', 'required', 'numeric'],
			'expire_date' => ['bail', 'required', 'date'],
		];
	}

	/**
	 * @Another Edward Yu 2022/1/16下午6:40
	 * @return string[]
	 */
	public function attributes(): array
	{
		return [
			'count'  => '数量',
			'points' => '积分',
			'expire_date' => '到期时间',
		];
	}

	public function storeValidate():array
	{
		return $this->scene($this->take(['count', 'points', 'expire_date']));
	}
}