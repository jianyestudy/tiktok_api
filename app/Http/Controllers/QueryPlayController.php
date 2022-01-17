<?php

namespace App\Http\Controllers;

use App\Http\Requests\QueryPlayRequest;
use App\Models\Card;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use QCS\LaravelApi\Exceptions\ResultException;

class QueryPlayController extends Controller
{
	/**
	 * @param \App\Http\Requests\QueryPlayRequest $request
	 */
    public function __construct(QueryPlayRequest $request)
    {
		$this->request = $request;
    }

	/**
	 * @Another Edward Yu 2022/1/17下午2:38
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 * @throws \QCS\LaravelApi\Exceptions\ResultException
	 */
	public function QueryPlay(): void
	{
		$requestData = $this->request->queryPlayValidate();

		//验证卡密
		$error = Card::validateCard($requestData['card_number']);
		$error && $this->error($error);

		$client = new Client();

		try {
			$response = $client->get(( string)env('TIKTOK_VIDEO_PLAY'), ['query' => ['uid' => $requestData['uid'], 'token' => env('TOKEN')]]);
			$results   = $response->getBody()->getContents();
			$result   = json_decode($results, true, 512, JSON_THROW_ON_ERROR);

			if($result['code'] === 1 || $result['msg'] === 'error' || $response->getStatusCode() === 502){
				$this->error('uid错误！');
			}

			if ($result['code'] !== 0 || $result['msg'] !== 'success' || !$result['data']) {
				$this->error('接口解析失败');
			}

		} catch (Exception $e) {
			Log::error($e->getTraceAsString());
			$this->error($e instanceof ResultException ? $e->getMessage(): '请求失败！请联系管理员');
		}

		//减除积分
		Card::reducePoint($requestData['card_number']);

		//返回结果
		$this->success($result['data']);
	}
}
