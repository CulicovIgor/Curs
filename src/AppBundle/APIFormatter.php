<?php
namespace AppBundle;

use AppBundle\Exception\InvalidFormException;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class APIFormatter implements ContainerAwareInterface
{
    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var ContainerInterface
     */
    private $container;
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    public function getResponseForRetrivedData($data, array $opts = []){
        //$me = $this->container->get('security.token_storage')->getToken()->getUser();
        !isset($opts['flush']) ? $opts['flush'] = [] : [];
        !isset($opts['code']) ? $opts['code'] = \Symfony\Component\HttpFoundation\Response::HTTP_OK : [];
        return [
            //'me' => $me,
            'meta' => [
                'code'      => $opts['code'],
                'flush'     => $opts['flush']
            ],
            'response' => $data,
        ];
    }

    public function getResponseForException(Request $request, \Exception $exception, $responseData = []){
        $code = \Symfony\Component\HttpFoundation\Response::HTTP_FORBIDDEN;
        if ($exception instanceof HttpExceptionInterface) {
            $code = $exception->getStatusCode();
        }
        $message = $exception->getMessage();
        if ($exception instanceof InvalidFormException){
            $message = $exception->getForm()->getErrors(true)->current()->getMessage();
        }
        $type = $request->get('_format');
        $serializer = $this->container->get('jms_serializer');
        $data = [
            'meta' => [
                'code' => $code,
                'error_message' => $message
            ]
        ];
        if (!empty($responseData))
        {
            $data = array_merge($data, $responseData);
        }
        switch ($type){
            case 'json':
            case 'xml':
                $data = $serializer->serialize($data, $type);
                $response = new Response($data, $code, [
                    'Content-type' => 'application/' . $type
                ]);
                break;
            default:
                $response = new Response(
                    $message,
                    $code
                );
        }
        return $response;
    }
    public function getResponseForRedirect($route, array $parameters = [], $statusCode = Response::HTTP_FOUND, Request $lastRequest = null){
        $apikey = $this->container->get('security.token_storage')->getToken()->getUser()->getAccessToken();
        $parameters['apikey'] = $apikey;
        if (null != $lastRequest){
            $parameters['_format'] = $lastRequest->get('_format');
        }
        $response = new Response();
        $location =
            $this->container->get('router')->getGenerator()->generate($route, $parameters, UrlGeneratorInterface::ABSOLUTE_PATH);
        $response->setStatusCode($statusCode);
        $response->headers->set('location', $location);
        return $response;
        //$this->routeRedirectView('api_1_get_me', $routeOptions, Response::HTTP_NO_CONTENT);
        //return $response;
    }
}