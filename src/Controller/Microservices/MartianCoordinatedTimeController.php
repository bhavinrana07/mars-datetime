<?php

namespace App\Controller\Microservices;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * MartianCoordinatedTime controller.
 * @Route("/api", name="api_")
 */
class MartianCoordinatedTimeController extends FOSRestController
{
    private const SECONDS_PER_SOL = 88775.244147;

    private const LEAP_SECONDS = 37;

    private const MSD_PRECISION = 5;

    private const MCT_FORMAT = 'H:i:s';

    public const HTTP_OK = 200;

    /**
     * get Mars Date and Time.
     * @Rest\Post("/getMarsTime")
     *
     * @return Response
     */
    public function postMarsTimeAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $timestamp = $data['timestamp'];
        return self::validateDateTime($timestamp);
    }

    /**
     * validateDateTime function
     *
     * @param string $timestamp
     * @return void
     */
    private function validateDateTime(string $timestamp)
    {
        if (self::isValidTimeStamp($timestamp)) {
            return self::sendMarsDateTime($timestamp);
        } else {
            $body = [
                'status' => 0,
                'message' => "not a valid date and time."
            ];
            $serializer = $this->get('jms_serializer');
            $response = $serializer->serialize($body, 'json');
            return new Response($response);
        }
    }

    /**
     * isValidTimeStamp function
     *
     * @param string $timestamp
     * @return boolean
     */
    public function isValidTimeStamp(string $timestamp)
    {
        return ((string) (int) $timestamp === $timestamp)
            && ($timestamp <= PHP_INT_MAX)
            && ($timestamp >= ~PHP_INT_MAX);
    }

    /**
     * sendMarsDateTime function
     *
     * @param string $timestamp
     * @return void
     */
    private function sendMarsDateTime(string $timestamp): Response
    {
        $body = self::getMarsDateTime($timestamp);
        $serializer = $this->get('jms_serializer');
        $response = $serializer->serialize($body, 'json');
        return new Response($response);
    }

    /**
     * getMarsDateTime function
     *
     * @param string $timestamp
     * @return void
     */
    public function getMarsDateTime(string $timestamp): array
    {
        $marsSolDate = ($timestamp + self::LEAP_SECONDS) / self::SECONDS_PER_SOL + 34127.2954262;
        $marsSolDate = round($marsSolDate, self::MSD_PRECISION, PHP_ROUND_HALF_UP);
        $martianCoordinatedTime = round(fmod($marsSolDate, 1) * 86400, 0, PHP_ROUND_HALF_UP);
        $martianCoordinatedTime = gmdate(self::MCT_FORMAT, (int) $martianCoordinatedTime);
        return  [
            'mars_sol_date' => $marsSolDate,
            'martian_coordinated_time' => $martianCoordinatedTime,
        ];
    }
}
