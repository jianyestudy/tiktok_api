<?php

namespace App\Http\Controllers;

use App\Http\Requests\CardRequest;
use App\Models\Card;
use Illuminate\Support\Str;

class CardController extends Controller
{
	/**
	 * @param \App\Http\Requests\CardRequest $request
	 * @param \App\Models\Card $model
	 */
    public function __construct(CardRequest $request, Card $model)
    {
		$this->request =  $request;
		$this->model = $model;
    }


	/**
	 * @Another Edward Yu 2022/1/16下午7:19
	 * @throws \QCS\LaravelApi\Exceptions\ResultException
	 */
	public function store(): void
	{
		//validate rules
		$requestData = $this->request->storeValidate();

		if($requestData['passwd'] !== 'njhbjhsdfdckxascdg'){
			$this->error('密令验证失败！');
		}

		$data = [];
		for ($i = 0; $i < $requestData['count']; $i++){
			$data[$i]['card_number'] = (string) Str::uuid();
			$data[$i]['points'] = $requestData['points'];
			$data[$i]['expire_date'] = $requestData['expire_date'];
		}

		$result = $this->model::query()
			->insert($data);

		!$result && $this->error('生成失败');

		$this->success($data, '生成成功');
	}

}
