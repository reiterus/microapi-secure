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
    private string $channel = 'MICROAPI';
    private string $logPostfix = '';

    public function log(string $message, array $context = []): void
    {
        $channel = sprintf('%s: %s', $this->channel, $this->logPostfix);
        $message = sprintf('%s: %s ', $channel, $message);
        $message .= json_encode($context, 256);

        $stdout = fopen('php://stdout', 'w');
        fwrite($stdout, $message);
        fclose($stdout);
    }

    public function setLogPostfix(string $logPostfix): void
    {
        $this->logPostfix = $logPostfix;
    }
}
