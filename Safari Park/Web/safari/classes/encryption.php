<?php
/**
 * Created by PhpStorm.
 * User: ahstanin
 * Date: 8/7/17
 * Time: 6:07 PM
 */
(strpos($_SERVER["REQUEST_URI"], "classes") !== false) ? exit('Direct access not allowed') : '';

class encryption
{
    public function encrypt($value)
    {
        $_split = str_split($value);
        $_dis = '';

        foreach ($_split as $_value):
            $_dis .= $_value . "|" . rand("1", "9");
        endforeach;

        $decision = base64_encode($_dis);
        $decision = $this->encrypt_decrypt('encrypt', $decision);
        return $decision;
    }

    private function encrypt_decrypt($action, $string)
    {
        $output = false;

        @$crt_file_open = fopen(DIR_CLS . ".crt", "r");
        $crt_file = fread($crt_file_open, 8192);
        fclose($crt_file_open);


        @$key_file_open = fopen(DIR_CLS . ".key", "r");
        $key_file = fread($key_file_open, 8192);
        fclose($key_file_open);

        if ($action == 'encrypt') {
            openssl_get_publickey($crt_file);
            openssl_public_encrypt($string, $crypttext, $crt_file);
            $output = base64_encode($crypttext);
        } elseif ($action == 'decrypt') {
            $key = openssl_get_privatekey($key_file, 'AIFAPP007');
            $decode = base64_decode($string);
            openssl_private_decrypt($decode, $crypttext, $key);
            $output = $crypttext;
        }

        return $output;
    }

    public function enc_token($value, $duration)
    {
        $_timeout = $duration * 60;

        $_start = time();
        $_end = time() + $_timeout;

        $_time_hash = base64_encode($_start . "/" . $_end);

        $_split = str_split($value);
        $_dis = '';

        foreach ($_split as $_value):
            $_dis .= $_value . "|" . rand();
        endforeach;
        $_dis .= "@" . $_time_hash;

        file_put_contents("/mnt/www/content/test.txt", $_end . "/" . $value);

        $decision = base64_encode($_dis);
        $decision = $this->encrypt_decrypt('encrypt', $decision);
        return $decision;
    }

    public function dec_token($value, $key)
    {
        if (strpos($_SERVER['REMOTE_ADDR'], '192.168') !== false) {
            $ip = file_get_contents("http://ipecho.net/plain");
        } elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }


        $_dis = $this->encrypt_decrypt('decrypt', $value);
        $_dis = base64_decode($_dis);
        $_split = explode("|", $_dis);
        $_split_time = explode("@", $_dis);
        $_time_val = base64_decode($_split_time["1"]);
        $_time = explode("/", $_time_val);
        $_end = $_time["1"];
        $_now = time();


        $decode = '';

        foreach ($_split as $_value):
            $decode .= substr($_value, -1);
        endforeach;

        $decision = substr($decode, 0, -1);

        if ($decision == $key && intval($_end) && $_end > $_now && $decision == $ip) {
            $final = 1;
        } else {
            $final = 0;
        }

        return $final;
    }

    public function random_key()
    {
        $seed = str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789");
        $rand = substr($seed, 0, 15);
        return $rand;
    }

    public function unique_id()
    {
        $seed = str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ");
        $rand = substr($seed, 0, 6);
        return $rand;
    }

    public function random_token()
    {
        $seed = str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789");
        $rand = substr($seed, 0, 45);
        return $rand;
    }

    public function random_image()
    {
        $seed = str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789");
        $rand = substr($seed, 0, 30);
        return $rand;
    }

    public function random_value()
    {
        $seed = str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ");
        $rand = substr($seed, 0, 4);
        return $rand;
    }

    public function random_number()
    {
        $seed = str_shuffle("0123456789");
        $rand = substr($seed, 0, 8);
        return $rand;
    }


    public function random_invoice()
    {
        $seed_one = str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ");
        $rand_one = substr($seed_one, 0, 4);
        $seed_two = str_shuffle("0123456789");
        $rand_two = substr($seed_two, 0, 8);
        $invoice = $rand_one . "-" . $rand_two;
        return $invoice;
    }

    public function decrypt($value)
    {
        $_dis = $this->encrypt_decrypt('decrypt', $value);
        $_dis = base64_decode($_dis);
        $_split = explode("|", $_dis);
        $decoded = '';

        foreach ($_split as $_value):
            $decoded .= substr($_value, -1);
        endforeach;

        $decision = substr($decoded, 0, -1);
        return $decision;
    }


}