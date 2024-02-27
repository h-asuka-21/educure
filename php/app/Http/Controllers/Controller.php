<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * エラーの固定接尾語
     */
    private const ERROR_MESSAGE_SUFFIX = 'に失敗しました。しばらくしてからもう一度お試しください';
    /**
     * 成功時の固定接尾語
     */
    private const SUCCESS_MESSAGE_SUFFIX = 'が完了しました。';
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * エラー結果jsonレスポンスを作成する
     * @param string $action_string 実行内容（＊＊の登録、＊＊の更新など）
     * @param string $message メッセージ（メッセージの直接指定がある場合）
     * @param array|null $result 結果をそのままレスポンスする場合
     * @param int $status エラーステータスコード
     * @return JsonResponse
     */
    protected function errorResponse($action_string = '', $message = '', $result = null, $status = 500): JsonResponse
    {
        if ($status === 0) {
            $status = 500;
        }
        if ($result !== null && is_array($result)) {
            // エラー結果を配列で返すパターンの場合
            return response()->json($result, $status);
        }
        if ($message === '' || !preg_match(config('japanese_check', '/^[一-龠　-ー]+$/u'), $message)) {
            // 指定されたメッセージがない場合
            return response()->json(['message' => $action_string . self::ERROR_MESSAGE_SUFFIX], $status);
        }
        // メッセージ指定がある場合
        return response()->json(['message' => $message], $status);
    }

    /**
     * @param string $action_string 実行内容（＊＊を登録、＊＊を更新など）
     * @param string $message メッセージ（メッセージの直接指定がある場合）
     * @return JsonResponse
     */
    protected function successResponse($action_string = '', $message = ''): JsonResponse
    {
        if ($message === '') {
            return response()->json(['message' => $action_string . self::SUCCESS_MESSAGE_SUFFIX]);
        }
        return response()->json(['message' => $message]);
    }

    protected function authFailedResponse()
    {
        return $this->errorResponse('', '認証に失敗しました。', null, 401);
    }

}
