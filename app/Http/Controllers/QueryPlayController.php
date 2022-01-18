<?php

namespace App\Http\Controllers;

use App\Http\Requests\QueryPlayRequest;
use App\Models\Card;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use QCS\LaravelApi\Exceptions\ResultException;
use Sovit\TikTok\Api;

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
		$error = Card::validateCard($requestData['card_number'], env('TIKTOK_VIDEO_POINT'));
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

		//减除积分g
		Card::reducePoint($requestData['card_number'], env('TIKTOK_VIDEO_POINT'));

		//返回结果
		$this->success($result['data']);
	}


	 public function test()
	 {
		 $config = [
			 "user-agent"		=> 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.106 Safari/537.36', // Valid desktop browser HTTP User Agent
			 "proxy-host"		=> false,
			 "proxy-port"		=> false,
			 "proxy-username"	=> false,
			 "proxy-password"	=> false,
			 "cache-timeout"		=> 3600, // 1 hours cache timeout
			 "cookie_file"		=> sys_get_temp_dir() . 'tiktok.txt', // cookie file path
			 "nwm_endpoint"		=> "https://my-api.example.com", // private api endpoint
			 "api_key"		=> "API_KEY", // see below on how to get API key
		 ];
		 $api=new Api(array($config));
		 
		 dd($api->getUser('zoe980304'));
	 }
}
