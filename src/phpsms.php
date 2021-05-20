<?php

namespace jonatasrt;


session_start();

/**
 *  Classe simples para envio de SMS
 *
 *
 *  @author Jonatas Teixeira
 */
class phpsms
{


    /**
     * Carrega IP, PORTA e mensagem padrão, telefone e chave
     */
    public function __construct($ip, $port, $sms = '', $fone = '', $key = '')
    {
        $this->ip = $ip;
        $this->port = $port;
        $this->sms = $sms;
        $this->fone = $fone;
        $this->key = $key;
        $this->codigo = rand(100000, 999999);
    }

    /**
     * Envia SMS com código e cria sessão com o código
     *
     * @param ip_porta
     *
     * @return
     */

    public function get_sms()
    {

        $posts = ['number' => $this->fone, 'message' => $this->codigo . ' ' . $this->sms, 'token' => $this->key];


        $config = array(



            CURLOPT_URL => $this->ip,
            CURLOPT_POSTFIELDS => $posts,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13',
            CURLOPT_RETURNTRANSFER => 1,  // return web page

            /**
            PORTAS PARA USAR OFFLINE
           */
            CURLOPT_PORT => $this->port, // 8766,
            CURLOPT_LOCALPORT => 1080,

            CURLOPT_HEADER         => false,        // don't return headers
            CURLOPT_FOLLOWLOCATION => false,         // follow redirects
            CURLOPT_ENCODING       => "",           // handle all encodings
            CURLOPT_AUTOREFERER    => true,         // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,          // timeout on connect
            CURLOPT_TIMEOUT        => 120,          // timeout on response
            CURLOPT_MAXREDIRS      => 10,           // stop after 10 redirects
            CURLOPT_SSL_VERIFYHOST => 0,            // don't verify ssl
            CURLOPT_SSL_VERIFYPEER => false,        //
            CURLOPT_VERBOSE        => 1                //
        );

        $ch = curl_init();
        curl_setopt_array($ch, $config);
        $content = curl_exec($ch);

        $info = curl_getinfo($ch);
        $erro = curl_error($ch);

        //   var_dump($info);
        //   var_dump($erro);

        curl_close($ch);

        if ($content) {
            $_SESSION['sms_fone'] = $this->fone;
            $_SESSION['sms_code'] = $this->codigo;
            return true;
        } else {
            $_SESSION['sms_fone'] = false;
            $_SESSION['sms_code'] = false;
            return false;
        }
    }

    public function view_sms($cod)
    {

        if (!empty($_SESSION['sms_code']) && $_SESSION['sms_code'] == $cod && !empty($_SESSION['sms_fone']) && $_SESSION['sms_fone'] == $this->fone) {
            return true;
        } else {
            return false;
        }
    }




}
