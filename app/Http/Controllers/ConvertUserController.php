<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConvertUserRequest;
use App\Models\Card;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use QCS\LaravelApi\Exceptions\ResultException;

class ConvertUserController extends Controller
{
	/**
	 * @param \App\Http\Requests\ConvertUserRequest $request
	 */
	public function __construct(ConvertUserRequest $request)
	{
		$this->request = $request;
	}


	/**
	 * @Another Edward Yu 2022/1/17上午12:39
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 * @throws \QCS\LaravelApi\Exceptions\ResultException
	 */
	public function getUid(): void
	{
		$requestData = $this->request->validate();
		$error = Card::validateCard($requestData['card_number']);
		$error && $this->error($error);

		$client = new Client();

		try {
			$response = $client->get(( string)env('TIKTOK_USER_API'), ['query' => ['uniqueId' => $requestData['username'], 'token' => env('TOKEN')]]);
			$results   = $response->getBody()->getContents();
			$result   = json_decode($results, false, 512, JSON_THROW_ON_ERROR);


			if($result->code === 1 || $result->msg === 'error' || $response->getStatusCode() === 502){
				$this->error('用户名信息错误！');
			}
			if ($result->code !== 0 || $result->msg !== 'success' || !$result->data) {
				$this->error('接口解析失败');
			}

		} catch (Exception $e) {
			Log::error($e->getTraceAsString());
			$this->error($e instanceof ResultException ? $e->getMessage(): '请求失败！请联系管理员');
		}

		//减除积分
		Card::reducePoint($requestData['card_number'], env('TIKTOK_USER_POINT'));


		//返回结果
		$this->success($result->data->uid);
	}
}
