<?php

namespace Work;

/**
 * ---------------------- !!!NJTICE!!! -----------------------------------------------------------------------------------------------------------
 * connect($connect, логин, пароль, вход в прив. режим, пароль для прив. режима, хост, предельное время на работу с одним хостом в секундах)
 * commands($connect, хост/ы, пауза для выполнения основной команды в милисекундах)
 * write($connect)
 * reload($connect) reloadAfter($connect, время до ребута)
 * logout($connect)
 * closeConnect($connect)
 * endOfScript(Массив хостов, ключ перебора) - встраивается в foreach(), в конце
 */

class Functions
{
    /**
     * Сохранение конфига
     */
    function write($connect)
    {
        // Пауза
        usleep(500000);
        // Команда записи конфига
        fwrite($connect, "write\r\n");
        // Пауза для выполнения команды
        sleep(2);
        // Даём разрешение на сохранение конфига
        fwrite($connect, "y\r\n");
    }


    /**
     * Отложенный ребут
     */
    function reloadAfter($connect, $reloadDelay)
    {
        // Пауза
        usleep(500000);
        // Ставим отложенный ребут
        fwrite($connect, "reload after $reloadDelay\r\n");
        // Пауза
        usleep(500000);
        // Подтверждаем ребут
        fwrite($connect, "y\r\n");
    }


    /**
     * Ребут
     */
    function reload($connect)
    {
        // Пауза
        usleep(500000);
        // Ставим отложенный ребут
        fwrite($connect, "reload\r\n");
        // Пауза
        usleep(500000);
        // Подтверждаем ребут
        fwrite($connect, "y\r\n");
    }


    /**
     * Выход со свитча
     */
    function logout($connect)
    {
        // Пауза
        usleep(500000);
        // Выходим со свитча
        fwrite($connect, "exit\r\n");
    }


    /**
     * Закрываем соединение с хостом
     */
    function closeConnect($connect)
    {
        // Пауза
        usleep(500000);
        // Закрываем соединение с хостом
        fclose($connect);
    }

    /**
     * Подключение к хосту, авторизация
     */
    function connect($host, $endOfLiveConnect)
    {
        // Пробуем пинговать узлы перед их обработкой
        exec("ping -n 1 $host", $output, $result);

        // Если пинг прошёл, работаем. Иначе выдаём сообщение, что хост не пингуется
        if($result == 0)
        {
            // Отлавливаем ошибки подключений к хостам и придаём им цивилизованный вид
            try
            {
                $connect = pfsockopen($host, 23, $errno, $e, $endOfLiveConnect);

                // Если соединение успешно, то функция его возвращает как объект
                if(@$connect)
                {
                    return $connect;
                }

                if(!@$connect)
                {
                    throw new Exception("Не удалось подключиться к $host");
                }
            }
            catch(Exception $e)
            {
                echo $e->getMessage();
            }
        }
        else
        {
            return false;
        }
    }


    /**
     * Авторизация
     */
    function auth($connect, $login, $pass, $enable, $enablePass)
    {
        // Устанавливаем соединение с хостом
        if(@$connect)
        {
            // Даём некоторое время, чтобы свитч предложил ввести лог и пасс
            sleep(2);
        
            if(fwrite($connect, $login) and fwrite($connect, $pass))
            {
                // Пауза
                usleep(500000);
                // Вход в прив. режим
                fwrite($connect, $enable);
                // Пауза
                usleep(500000);
                // Пароль от прив. режима
                fwrite($connect, $enablePass);
            }
            else
            {
                return false;
            }
        }
    }


    /**
     * Список команд на выполнение, без чтения
     */
    function writeCommands($connect, $commands, $pause)
    {
        // Пауза
        usleep(500000);

        // Перебираем список нужных команд, если получили массив
        if(is_array($commands))
        {
            // Проверяем массив на пустоту
            if(!empty($commands))
            {
                foreach($commands as $command) 
                {
                    // Вводим каждую команду по очереди
                    fwrite($connect, "$command\r\n");
                    // Даём время на обработку команды
                    usleep($pause);
                }
            }
            else
            {
                return false;
            }
        }
        elseif((string)$commands)
        {
            // Вводим команду
            fwrite($connect, "$commands\r\n");
            // Даём время на обработку команды
            usleep($pause);
        }
        else
        {
            return "Что-то не так с основным набором команд! <br>";
        }
    }


    /**
     * Чтение результата команд
     */
    function readCommands($connect, $target, $lenght, $indent)
    {
        // Читаем поток из соединения со свитчём. $lenght - число читаемых байт, $indent - сдвиг курсора с начала строки на N байт
        $content = stream_get_contents($connect, $lenght, $indent);

        // Производим поиск в этом потоке некоего значения $target
        if(strpos($content, (string)$target))
        {
            return true;
        }
        else
        {
            return false;
        }
    }


    /**
     * Передача данных во вьюху
     */
    function view($object, $view, $array)
    {
        // Разбираем полученный массив на переменные и их значения
        if(is_array($array))
        {
            foreach($array as $k => $v)
            {
                echo $object->run($view,[
                    $k => $v,
                ]);
            }
    
            //Выводим на экран промежуточные результаты
            ob_flush();
            flush();
        }
    }


    /**
     * Результирование работы скрипта. Должно быть встроено в перебор массива хостов
     */
    function endOfScript($array, $key)
    {
        // Если список свитчей пройден, то сообщаем об этом, чтобы была понятно, что скрипт закончил
        if(count($array) == $key + 1)
        {
            return true; 
        }
    }
}