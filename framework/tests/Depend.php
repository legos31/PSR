<?php


namespace Framework\Tests;


class Depend
{
    public function __construct(private Telegram $telegram, private YouTube $youTube)
    {
    }

    /**
     * @return Telegram
     */
    public function getTelegram(): Telegram
    {
        return $this->telegram;
    }

    /**
     * @return YouTube
     */
    public function getYouTube(): YouTube
    {
        return $this->youTube;
    }
}