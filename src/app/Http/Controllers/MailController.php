<?php

namespace App\Http\Controllers;

use App\Utility\SendGrid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MailController extends Controller
{

    /**
     * send
     */
    public function send(Request $request)
    {
        try {
            $to = $request->to;
            if (empty($to)) {
                throw new \Exception('必須パラメータがありません。');
            }
            $result = SendGrid::send(
                $to,
                'SendGrid送信テスト',
                'テスト本文'
            );
            return $result;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }


    /**
     * receive
     */
    public function receive(Request $request)
    {
        try {

            Log::info($request->envelope);
            $envelope = json_decode($request->envelope, true);
            if (in_array('noreply@mail.oootaiji.com', $envelope['to'])) {
                // 転送s
                SendGrid::send(
                    config('mail.to.contact.address'),
                    "[転送]". $request->subject,
                    $request->html,
                );
                // 自動返信 (自分のドメインは無限ループになるために除外)
                if (strpos($envelope['from'], '@mail.oootaiji.com') !== false) {
                    SendGrid::send(
                        $envelope['from'],
                        "[自動返信]連絡",
                        "こちらへメールは返信できません。",
                    );
                }
            } else {
                // 転送
                SendGrid::send(
                    config('mail.to.contact.address'),
                    "[転送]". $request->subject,
                    $request->html,
                );
                // 自動返信 (自分のドメインは無限ループになるために除外)
                if (strpos($envelope['from'], '@mail.oootaiji.com') !== false) {
                    SendGrid::send(
                        $envelope['from'],
                        "[自動返信]連絡",
                        "こちらのメールは自動返信です。担当者からの返信は、しばらくお待ち下さい。",
                    );
                }
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
    }

}
