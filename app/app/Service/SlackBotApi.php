<?php

namespace App\Service;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class SlackBotApi
{
    public const CHAT_POST_MESSAGE_URL = 'https://slack.com/api/chat.postMessage';

    /**
     * @var string
     */
    protected string $accessToken;

    /**
     * SlackBotApi constructor.
     *
     * @param string $accessToken
     */
    public function __construct(string $accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * Sends message to the private channel using Slack Web API.
     *
     * @param string      $channel
     * @param string      $message
     * @param string|null $threadTs
     *
     * @return bool
     */
    public function sendMessage(
        string $channel,
        string $message,
        ?string $threadTs = null
    ): bool {

        /** @var Response $response */
        $response = Http::withToken($this->accessToken)
            ->contentType('application/json')
            ->post(self::CHAT_POST_MESSAGE_URL, [
                'channel' => $channel,
                'text' => $message,
                'thread_ts' => $threadTs,
            ]);

        return $response->json()['ok'];
    }
}
