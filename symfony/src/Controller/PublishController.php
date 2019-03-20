<?php

namespace App\Controller;

use DateTime;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\Publisher;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PublishController
 * @package App\Controller
 */
class PublishController
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
        $update = new Update(
            'test/1',
            json_encode([
                'message' => 'Hello World! ' . $date->format('c')
            ])
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
    public function testReceive()
    {
        return new Response(
            '<!doctype html><html lang="en"><head><meta charset="utf-8"><title>Mercure subscriber</title></head>

            <body><h1>Mercure subscriber</h1><div id="output" style="border:1px solid black; overflow:scroll; height: 250px;"></div>
            
            <script type="text/javascript">
            const es = new EventSource(\'http://symfonytests.lh:3000/hub?topic=\' + encodeURIComponent(\'test/1\'));
            es.onmessage = e => {
                // Will be called every time an update is published by the server
                let div = document.getElementById(\'output\');
                div.innerHTML += \'<p>\' + JSON.parse(e.data).message + \'</p>\';
            }
            </script>
            
            </body></html>'
        );
    }
}
