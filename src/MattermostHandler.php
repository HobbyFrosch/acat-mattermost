<?php

namespace ACAT\Mattermost;

use Psr\Log\LogLevel;
use Monolog\LogRecord;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Monolog\Handler\AbstractProcessingHandler;

/**
 *
 */
class MattermostHandler extends AbstractProcessingHandler
{
    /**
     * @var string
     */
    private string $webHookUrl;

    private Client $client;

    /**
     * @param   string  $webHookUrl
     * @param   string  $level
     * @param   bool    $bubble
     */
    public function __construct(string $webHookUrl, $level = LogLevel::CRITICAL, bool $bubble = true)
    {
        parent::__construct($level, $bubble);
        $this->webHookUrl = $webHookUrl;
        $this->client = new Client();
    }

    /**
     * @throws GuzzleException
     * @return void
     *
     * @param   LogRecord  $record
     */
    public function write(LogRecord $record): void
    {
        $this->client->request('POST', $this->webHookUrl, [
            'form_params' => [
                'payload' => json_encode(['text' => $record['formatted']])
            ]
        ]);
    }
}