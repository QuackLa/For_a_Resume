<?php

namespace Moovi;

class Config
{
    /**
     * Адрес API
     */
    const API_URL = 'https://iptv.ru';

    /**
     * Логин для API
     */
    const API_LOGIN = 'login';

    /**
     * Пароль для API
     */
    const API_PASS = 'password';

    //Возвращаем URL
    function getURL()
    {
        return self::API_URL;
    }

    //Возвращаем логин от API Moovi
    function getLOGIN()
    {
        return self::API_LOGIN;
    }

    //Возвращаем пароль от API Moovi
    function getPASS()
    {
        return self::API_PASS;
    }
}