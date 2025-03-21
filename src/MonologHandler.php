<?php

namespace ACAT\Mattermost;

use Psr\Log\LogLevel;
use Monolog\LogRecord;
use Monolog\Handler\AbstractProcessingHandler;

/**
 *
 */
class MonologHandler extends AbstractProcessingHandler
{
    /**
     * @var string
     */
    private string $webHookUrl;

    /**
     * @param   string  $webHookUrl
     * @param   string  $level
     * @param   bool    $bubble
     */
    public function __construct(string $webHookUrl, string $level = LogLevel::DEBUG, bool $bubble = true)
    {
        parent::__construct($level, $bubble);
        $this->webHookUrl = $webHookUrl;
    }

    /**
     * @param LogRecord $record
     * @return void
     */
    public function write(LogRecord $record): void
    {
        $payload = json_encode(['text' => $record['formatted']]);

        $ch = curl_init($this->webHookUrl);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ['payload' => $payload]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);

        curl_exec($ch);
        curl_close($ch);
    }
}