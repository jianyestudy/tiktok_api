<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use QCS\LaravelApi\Validates\BaseValidate;

class ConvertUserRequest extends BaseValidate
{
	/**
	 * @Another Edward Yu 2022/1/16下午6:29
	 * @return \string[][]
	 */
	public function rules(): array
	{
		return [
			'card_number' => ['bail', 'required', 'uuid'],
			'username'    => ['bail', 'required', 'string']
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
			'username' => '用户名'
		];
	}

	/**
	 * @Another Edward Yu 2022/1/16下午7:37
	 * @return string[]
	 */
	public function messages(): array
	{
		return [
			'card_number.uuid' => '卡密格式不正确',
		];
	}

	/**
	 * @Another Edward Yu 2022/1/16下午7:37
	 * @return array
	 */
	public function validate(): array
	{
		return $this->scene($this->take(['card_number', 'username']));
	}
}
