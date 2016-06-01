<?php

namespace ScayTrase\Api\Cruds\Listener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ResponseFormatterListener
{
    const FORMAT_MAP = [
        'json' => 'application/json',
        'xml'  => 'application/xml',
    ];

    use CrudsRequestCheckerTrait;

    /** @var  SerializerInterface */
    private $serializer;
    /** @var RouterInterface */
    private $router;

    /**
     * ResponseNormalizerListener constructor.
     *
     * @param SerializerInterface $serializer
     * @param RouterInterface     $router
     */
    public function __construct(SerializerInterface $serializer, RouterInterface $router)
    {
        $this->router     = $router;
        $this->serializer = $serializer;
    }

    public function filterResponse(GetResponseForControllerResultEvent $event)
    {
        if (!$this->checkRequest($event)) {
            return;
        }

        $request  = $event->getRequest();
        $response = $event->getControllerResult();

        $format = $request->get('_format', 'json');

        $content = $this->serializer->serialize($response, $format);
        $format  = array_key_exists($format, self::FORMAT_MAP) ? self::FORMAT_MAP[$format] : 'text/plain';
        $event->setResponse(
            new Response(
                $content,
                Response::HTTP_OK,
                [
                    'Content-Type' => $format,
                ]
            )
        );
    }

    /** {@inheritdoc} */
    protected function getRouter()
    {
        return $this->router;
    }
}
