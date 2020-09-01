<?php

namespace App\Tests\Service;

use App\Service\SlackBotApi;
use Illuminate\Support\Facades\Http;
use TestCase;

class SlackBotApiTest extends TestCase
{
    public function testSlackBotApiCanPostMessageIntoPrivateChannel()
    {
        // Arrange
        $accessToken = 'one-long-verification-token';
        $channel = 'D024BE91L';
        $message = '12345';
        $threadTs = null;
        $responseJson = '{
            "ok": true,
            "channel": "C1H9RESGL",
            "ts": "1503435956.000247",
            "message": {
                "text": "Here\'s a message for you",
                "username": "ecto1",
                "bot_id": "B19LU7CSY",
                "attachments": [
                    {
                        "text": "This is an attachment",
                        "id": 1,
                        "fallback": "This is an attachment\'s fallback"
                    }
                ],
                "type": "message",
                "subtype": "bot_message",
                "ts": "1503435956.000247"
            }
        }';
        Http::fake(
            [
                'https://slack.com/api/chat.postMessage' => Http::response(
                    $responseJson,
                    200,
                    ['Content-Type' => 'application/json']
                ),
            ]
        );
        $slack = new SlackBotApi($accessToken);

        // Act
        $response = $slack->sendMessage($channel, $message, $threadTs);

        // Assert
        $this->assertTrue($response);
    }

    public function testSlackBotApiGetAnErrorOnPostingMessageIntoPrivateChannel(
    )
    {
        // Arrange
        $accessToken = 'one-long-verification-token';
        $channel = 'D024BE91L';
        $message = '12345';
        $threadTs = null;
        $responseJson = '{
            "ok": false,
            "error": "too_many_attachments"
        }';
        Http::fake(
            [
                'https://slack.com/api/chat.postMessage' => Http::response(
                    $responseJson,
                    200,
                    ['Content-Type' => 'application/json']
                ),
            ]
        );
        $slack = new SlackBotApi($accessToken);

        // Act
        $response = $slack->sendMessage($channel, $message, $threadTs);

        // Assert
        $this->assertFalse($response);
    }
}
