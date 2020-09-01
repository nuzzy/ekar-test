<?php

namespace App\Http\Controllers;

use App\Service\EulerProblem1Resolver;
use HttpHeaderException;
use HttpInvalidParamException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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

        $resolver = new EulerProblem1Resolver(self::EULER_PROBLEM_1_MAX_NUMBER);

        return new JsonResponse(['result' => $resolver->resolve($data['event']['text'])]);
    }

    /**
     * @TODO: Make a call to the Slack Web API endpoint "".
     * @param int $sum
     */
    protected function sendResponseToSlackChannel(int $sum)
    {

//POST https://slack.com/api/chat.postMessage
//Content-type: application/json
//Authorization: Bearer xoxb-your-token
//{
//  "channel": "YOUR_CHANNEL_ID",
//  "text": "Hello, world",
//  "as_user": true
//}
    }
}
