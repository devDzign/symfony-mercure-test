<?php

namespace App\Controller;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\Publisher;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PublishController
 * @package App\Controller
 */
class PublishController extends AbstractController
{
    /**
     * @Route(
     *     name="test_publish",
     *     path="test/publish"
     * )
     *
     * @param Publisher $publisher
     *
     * @throws
     *
     * @return Response
     */
    public function testPush(Publisher $publisher): Response
    {
        $message = json_encode(['status' => 'OutOfStock']);
        $targets = ['http://example.com/user/testUser'];

        $update = new Update(
            'http://example.com/books/1',
            $message,
            $targets
        );

        // The Publisher service is an invokable object
        $publisher($update);

        return new Response('Message ' . $update->getData() . ' published!');
    }

    /**
     * @Route(
     *     name="test_receive",
     *     path="test/receive"
     * )
     *
     * @return Response
     */
    public function testReceive(): Response
    {
        $html = <<<HTML
            <!doctype html>
            <html lang="en">
            <head><meta charset="utf-8">
                <title>Mercure subscriber</title>
                <script src="http://symfonytests.lh/eventsource.min.js"></script>
            </head>

            <body>
                <h1>Mercure subscriber</h1>
                <div id="output" style="border:1px solid black; overflow:scroll; height: 250px;"></div>
            
                <script type="text/javascript">
                var url = 'http://symfonytests.lh:3000/hub?topic=' + encodeURIComponent('http://example.com/test/demo');
                
                var source = new EventSourcePolyfill(url, { withCredentials: true $headers });
    
                source.onmessage = e => {
                    // Will be called every time an update is published by the server
                    console.log(e.data);
                    
                    let div = document.getElementById("output");
                    div.innerHTML += '<p>' + JSON.parse(e.data).message + '</p>';
                }
                </script>
            
            </body></html>
HTML;

        return new Response($html);
    }

    /**
     * @Route(
     *     name="test_cookie",
     *     path="test/cookie"
     * )
     *
     * @return Response
     */
    public function testCookie()
    {
        $token = (new Builder())
            // set other appropriate JWT claims, such as an expiration date
            ->set('mercure', ['subscribe' => ['http://example.com/user/testUser']]) // could also include the security roles, or anything else
            ->sign(new Sha256(), '3C26410B38FE45AEA23D075142D02A881280E59B97D8B53D2BCA70D9ADF2189C') // don't forget to set this parameter! Test value: aVerySecretKey
            ->getToken();

        $response = $this->json(['@id' => '/books/1', 'availability' => 'https://schema.org/InStock']);

        $response->headers->set(
            'set-cookie',
            sprintf('mercureAuthorization=%s; path=/hub; secure; httponly; SameSite=strict', $token)
        );

        return $response;
    }
}
