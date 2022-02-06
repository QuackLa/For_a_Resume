<?php

namespace Work;

class Config
{
    /**
     * Логин
     */
    public $login = "tech\r\n";

    /**
     * Пароль
     */
    public $pass = "techpass\r\n";

    /**
     * enable команда
     */
    public $enable = "enable\r\n";

    /**
     * Пароль от прив. режима
     */
    public $enablePass = "qq\r\n";

    /**
     * Время до отложенного ребута
     */
    public $reloadDelay = '10:40:00';

    /**
     * Предельное время на работу с одним хостом. В секундах
     */
    public $endOfLiveConnect = 110;

    /**
     * Пауза на выполнение основных команд. В миКросекундах. 1 000 000 МкрС == 1 сек
     */
    public $pause = 100000; // 0,1 sec

    /**
     * Сколько байт хотим прочитать из потока со свитча
     */
    public $lenght = 200;

    /**
     * На сколько байт сдвигаем курсор при чтении потока со свитча
     */
    public $indent = 78;

    /**
     * Некие значения, которые будем искать в выводе со свитча
     */
    public $target = 'S2965'; 

    public $timer;

    /**
     * Команды
     */
    public $commands = [
        //"show version",
        "conf t",
        //"snmp-server community swsecret ro",
        'no ntp server 172.31.0.1',
        'no ntp server 172.31.0.2',
        'no ntp server 172.31.66.1',
        'clock timezone MSK 3 0',
        "ntp server 172.31.1.3",
        'logging source-interface Vlan30',
        'logging 172.31.0.2',
        //"exit",
        //"copy ftp://pva:82faluk6@172.31.1.3/upload/SNR2965/SNR-S2965-48T(24T_8T(R1.0,R2.0))(RPS_UPS)_7.0.3.5(R0241.0465)_nos.img nos.img",
        //"y",
    ];

    /**
     * Создаём объект для работы с датой и временем
     */
    function __construct()
    {
        $this->timer = new \DateTime();
    }

    /**
     * Возвращаем команду установки времени с текущим временем по Мск
     */
    public function getTimer()
    {
        return 'clock set '.$this->timer->format('g i s Y n j');
    }
}