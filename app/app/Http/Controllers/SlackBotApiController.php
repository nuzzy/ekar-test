<?php

namespace App\Http\Controllers;

use App\Service\EulerProblem1Resolver;
use App\Service\SlackBotApi;
use HttpHeaderException;
use HttpInvalidParamException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SlackBotApiController extends Controller
{
    const JSON_DECODE_DEPTH = 512;
    const REQUEST_TYPE_URL_VERIFICATION = 'url_verification';
    const EULER_PROBLEM_1_MAX_NUMBER = 10000;

    /**
     * @param Request $request
     *
     * @throws HttpHeaderException
     * @throws HttpInvalidParamException
     * @throws \App\Exceptions\EulerProblemException
     * @throws \JsonException
     *
     * @return JsonResponse
     */
    public function eulerProblem1(Request $request): JsonResponse
    {
        if (!$request->headers->contains('Content-Type', 'application/json')) {
            throw new HttpHeaderException(
                '"Content-Type" header should have "application/json" value'
            );
        }

        $data = json_decode(
            $request->getContent(),
            true,
            self::JSON_DECODE_DEPTH,
            JSON_THROW_ON_ERROR
        );

        if (isset($data['type'])
            && $data['type'] === self::REQUEST_TYPE_URL_VERIFICATION
        ) {
            return $this->handleUrlVerification($data);
        }

        return $this->handleEulerProblemResolving($data);
    }

    /**
     * Slack Events set up requires this validation to work on the same URL.
     *
     * @param array $data
     *
     * @throws HttpInvalidParamException
     *
     * @return JsonResponse
     */
    protected function handleUrlVerification(array $data): JsonResponse
    {
        if (!isset($data['challenge'])) {
            throw new HttpInvalidParamException(
                'Required parameter "challenge" is missing'
            );
        }

        return new JsonResponse(['challenge' => $data['challenge']]);
    }

    /**
     * @param array $data
     *
     * @throws HttpInvalidParamException
     * @throws \App\Exceptions\EulerProblemException
     *
     * @return JsonResponse
     */
    protected function handleEulerProblemResolving(array $data): JsonResponse
    {
        if (!isset($data['event']['text'])) {
            throw new HttpInvalidParamException(
                'Required parameter "text" is missing'
            );
        }
        if (!is_numeric($data['event']['text'])) {
            throw new HttpInvalidParamException(
                'Parameter "text" should contain a number'
            );
        }

        $isOk = $this->sendResponseToSlackChannel($data['event']);

        return new JsonResponse(['result' => $isOk]);
    }

    /**
     * @param array $eventData
     *
     * @throws \App\Exceptions\EulerProblemException
     *
     * @return bool
     */
    protected function sendResponseToSlackChannel(array $eventData): bool
    {
        if ($this->isRespondingToSelf($eventData)) {
            return false;
        }

        $resolver = new EulerProblem1Resolver(self::EULER_PROBLEM_1_MAX_NUMBER);
        $sum = $resolver->resolve($eventData['text']);

        $accessToken = env('SLACK_ACCESS_TOKEN');
        $slackBot = new SlackBotApi($accessToken);

        return $slackBot->sendMessage($eventData['channel'], $sum, $eventData['ts']);
    }

    /**
     * Check who is the sender to avoid sending reply message to self.
     *
     * @param array $eventData
     *
     * @return bool
     */
    protected function isRespondingToSelf(array $eventData): bool
    {
        $botUserId = env('SLACK_BOT_USER_ID');
        if ($botUserId === $eventData['user']) {
            return true;
        }

        return false;
    }
}
