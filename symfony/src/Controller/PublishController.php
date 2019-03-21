<?php

namespace App\Controller;

use DateTime;
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
        $date = new DateTime('now');
        $message = json_encode(['message' => 'Hello World! ' . $date->format('H:i:s')]);
        $targets = ['http://example.com/user/testUser'];

        $update = new Update(
            'http://example.com/test/demo',
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
     *     path="test/receive/{auth}",
     *     requirements={"auth"="0|1"},
     *     defaults={"auth"=0}
     * )
     *
     * @param int $auth
     *
     * @return Response
     */
    public function testReceive(int $auth): Response
    {
        $headers = '';
        if ($auth)
        {
            $headers = "headers: {'Authorization': 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJtZXJjdXJlIjp7InN1YnNjcmliZSI6WyJodHRwOlwvXC9leGFtcGxlLmNvbVwvdXNlclwvdGVzdFVzZXIiXX19.ITmbjNIoLgo4PhXN-XKSpSZkjLmhMQfde_2RpepxYiA'}";
        }

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
                
                var source = new EventSourcePolyfill(url, { $headers });
    
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
}
