<?php

namespace Comes\Logger;

use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Handler\Curl\Util;

/**
 * Sends notifications through the stride api to a stride room
 *
 * @author Jeremias Wolff <jeremiaswolff@gmail.com>
 * @see    https://developer.atlassian.com/cloud/stride/
 */
class StrideLogHandler extends AbstractProcessingHandler
{
    /**
     * @var string
     */
    private $token;

    /**
     * @var string
     */
    private $room;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $host;

    private $cloudid;

    public function __construct($token, $room, $cloudid, $name = 'Monolog', $level = Logger::DEBUG, $host = 'https://api.atlassian.com')
    {
        $this->token = $token;
        $this->cloudid = $cloudid;
        $this->name = $name;
        $this->room = $room;
        $this->host = $host;
    }

    /**
     * {@inheritdoc}
     */
    protected function write(array $record) : void
    {
        $data = [
            "body" => [
                "version" => 1,
                "type" => "doc",
                "content" => [
                    [
                        "content" => [
                            [
                                "marks" => [
                                    [
                                        "type" => "strong"
                                    ]
                                ],
                                "text" => "LOGGER APP",
                                "type" => "text"
                            ],
                            [
                                "text" => ": " . $this->name,
                                "type" => "text"
                            ]
                        ],
                        "type" => "paragraph"
                    ],

                    [
                        "type" => "codeBlock",
                        "content" => [
                            [
                                "type" => "text",
                                "text" => print_r($record['message'], true)
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $data_string = json_encode($data);

        $ch = curl_init("{$this->host}/site/{$this->cloudid}/conversation/{$this->room}/message");

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            "Authorization: Bearer {$this->token}",
            'Content-Length: ' . strlen($data_string)
        ));

        Util::execute($ch);
    }

}
