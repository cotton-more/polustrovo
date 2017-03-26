<?php

namespace App\Provider;

use App\Service\Telegram\TelegramService;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use TelegramBot\Api\BotApi;

class TelegramBotServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $pimple A container instance
     */
    public function register(Container $pimple)
    {
        $pimple['telegram_bot.chat_id'] = $pimple['config']['telegram.chat_id'];

        $pimple['telegram_bot.bot_api'] = function (Container $c) {
            $botApi = new BotApi($c['config']['telegram.token']);

            return $botApi;
        };

        $pimple['telegram'] = function (Container $c) {
            $service = new TelegramService(
                $c['telegram_bot.bot_api'],
                $c['telegram_bot.chat_id'],
                $c['screenshot.repository']
            );

            return $service;
        };
    }
}