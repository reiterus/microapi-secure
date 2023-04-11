<?php

declare(strict_types=1);

/*
 * This file is part of the MicroApi engine based on Symfony components.
 * (c) Pavel Vasin <reiterus@yandex.ru>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MicroApi\Util;

trait MicroLog
{
    private string $logSuffix = '';

    public function logMicro(string $message, array $context = []): void
    {
        if ('test' == $_ENV['APP_ENV']) {
            return;
        }

        $channel = sprintf('%s: %s', 'MICROAPI', $this->logSuffix);
        $message = sprintf('%s: %s ', $channel, $message);
        $message .= json_encode($context, 256);

        $stdout = fopen('php://stdout', 'w');

        if (!is_resource($stdout)) {
            throw new \UnexpectedValueException('STDOUT is false');
        }

        fwrite($stdout, $message);
        fclose($stdout);
    }

    public function setLogSuffix(string $logSuffix): void
    {
        $this->logSuffix = $logSuffix;
    }
}
