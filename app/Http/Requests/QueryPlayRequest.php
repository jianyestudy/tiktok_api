<?php

namespace App\Http\Requests;

use QCS\LaravelApi\Validates\BaseValidate;

class QueryPlayRequest extends BaseValidate
{
	/**
	 * @Another Edward Yu 2022/1/16下午6:29
	 * @return \string[][]
	 */
	public function rules(): array
	{
		return [
			'card_number' => ['bail', 'required', 'uuid'],
			'uid'    => ['bail', 'required', 'string']
		];
	}

	/**
	 * @Another Edward Yu 2022/1/16下午6:29
	 * @return string[]
	 */
	public function attributes(): array
	{
		return [
			'card_number' => '卡密',
			'uid' => 'uid'
		];
	}

	public function messages(): array
	{
		return [
			'card_number.uuid' => '卡密格式不正确',
		];
	}

	/**
	 * @Another Edward Yu 2022/1/17下午2:34
	 * @return array
	 */
	public function queryPlayValidate(): array
	{
		return $this->scene($this->take(['card_number', 'uid']));
	}
}
