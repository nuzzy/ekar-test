<?php

namespace App\Tests\Http\Controllers;

use Illuminate\Support\Facades\Http;
use TestCase;

class SlackBotApiControllerTest extends TestCase
{
    /**
     * @return void
     */
    public function testNewMessageActionCanVerifySlackEventsCallbackUrl(): void
    {
        // Arrange
        $slackRequestBody = '{
    "token": "Jhj5dZrVaK7ZwHHjRyZWjbDl",
    "challenge": "3eZbrw1aBm2rZgRNFdxV2595E9CY3gmdALWMmHkvFXO7tYXAYM8P",
    "type": "url_verification"
}';
        $data = json_decode($slackRequestBody, true);

        // Act
        $httpClient = $this->json(
            'POST',
            '/slack-bot/euler-problem-1',
            $data,
            ['Content-Type' => 'application/json']
        );

        // Assert
        $this->assertEquals(200, $httpClient->response->getStatusCode());
        $this->assertTrue(
            $httpClient->response->headers->contains(
                'Content-Type',
                'application/json'
            )
        );
        $this->assertJsonStringEqualsJsonString(
            '{"challenge": "3eZbrw1aBm2rZgRNFdxV2595E9CY3gmdALWMmHkvFXO7tYXAYM8P"}',
            $httpClient->response->getContent()
        );
    }

    /**
     * @return void
     */
    public function testNewMessageActionCanReturnResolvedAnswer(): void
    {
        // Arrange
        $slackRequestBody = '{
    "token": "one-long-verification-token",
    "team_id": "T061EG9R6",
    "api_app_id": "A0PNCHHK2",
    "event": {
        "type": "message",
        "channel": "D024BE91L",
        "user": "U2147483697",
        "text": "10",
        "ts": "1355517523.000005",
        "event_ts": "1355517523.000005",
        "channel_type": "im"
    },
    "type": "event_callback",
    "authed_teams": [
        "T061EG9R6"
    ],
    "event_id": "Ev0PV52K21",
    "event_time": 1355517523
}';
        $requestData = json_decode($slackRequestBody, true);

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

        // Act
        $httpClient = $this->json(
            'POST',
            '/slack-bot/euler-problem-1',
            $requestData,
            ['Content-Type' => 'application/json']
        );

        // Assert
        $this->assertEquals(200, $httpClient->response->getStatusCode());
        $this->assertTrue(
            $httpClient->response->headers->contains(
                'Content-Type',
                'application/json'
            )
        );
        $this->assertJsonStringEqualsJsonString(
            '{"result": true}',
            $httpClient->response->getContent()
        );
    }
}
