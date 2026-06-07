<?php

namespace core;
class ApiClient
{
    public static function get($url)
    {
        // pregatim setarile pentru cererea HTTP
        $options = [
            'http' => [
                // permitem citirea raspunsului chiar daca API-ul da erori HTTP
                // putem citi mesajul de eroare JSON din API in loc ca PHP ul sa crape.
                'ignore_errors' => true,
                // luam id-ul sesiunii curente (PHPSESSID) din browser si il trimitem ca un cookie catre API, ca sa stie cine suntem
                'header' => "Cookie: PHPSESSID=" . session_id() . "\r\n"
            ]
        ];

        // impachetez setarile de mai sus intr-un format pe care file_get_contents il intelege
        $context = stream_context_create($options);

        //inchid write sesiune deorece cererea mea va trece prin index
        //care are un session start si va fi blocat
        // TOATE DATELE DE SESIUNE $_SESSION trebuie modificate in api
        session_write_close();

        //apel propriu zis catre api
        $jsonResponse = file_get_contents($url, false, $context);


        if (!$jsonResponse) {
            return false;
        }

        // json il transform in obiect php
        return json_decode($jsonResponse);
    }
}