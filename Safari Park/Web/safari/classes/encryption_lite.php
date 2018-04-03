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
    private function encrypt_decrypt($action, $string)
    {
        $output = false;

        $crt_file = '-----BEGIN CERTIFICATE-----
MIIIejCCB2KgAwIBAgIJAMUUbUchjTMJMA0GCSqGSIb3DQEBCwUAMIHGMQswCQYD
VQQGEwJVUzEQMA4GA1UECBMHQXJpem9uYTETMBEGA1UEBxMKU2NvdHRzZGFsZTEl
MCMGA1UEChMcU3RhcmZpZWxkIFRlY2hub2xvZ2llcywgSW5jLjEzMDEGA1UECxMq
aHR0cDovL2NlcnRzLnN0YXJmaWVsZHRlY2guY29tL3JlcG9zaXRvcnkvMTQwMgYD
VQQDEytTdGFyZmllbGQgU2VjdXJlIENlcnRpZmljYXRlIEF1dGhvcml0eSAtIEcy
MB4XDTE2MDQwNTA4NTYzOFoXDTE4MDQwNTA4NTYzOFowgdsxEzARBgsrBgEEAYI3
PAIBAxMCVVMxGDAWBgsrBgEEAYI3PAIBAhMHRmxvcmlkYTEdMBsGA1UEDxMUUHJp
dmF0ZSBPcmdhbml6YXRpb24xFTATBgNVBAUTDEwxNTAwMDAxNjgxNjELMAkGA1UE
BhMCVVMxEDAOBgNVBAgTB0Zsb3JpZGExEDAOBgNVBAcTB09ybGFuZG8xLDAqBgNV
BAoTI1JldGFpbCBFbWFpbCAoRWxpdGUgQ29uc3VtZXJzLCBMTEMpMRUwEwYDVQQD
EwxyZXRhaWwuZW1haWwwggIiMA0GCSqGSIb3DQEBAQUAA4ICDwAwggIKAoICAQDA
mc0ETS3BUCE2w/sEjJwuWiOcv2BrSTQdbx3TkiyaK6X95MorOYFQ4cQyozhdy2cA
gVjvwh0jJ7AnpY6J+tWU/DoaTg68EGgSqEwfkv1/VLflLcs05qBM07ESSpRaHx5S
HyVutmkEfY5PLBJIAjDg8tFkRIMRtDozLfwMKjpJx1HJKZru36goRboZmvK89sgb
/rRuZpGUhyOPEykWMAjGvNTLuQGoi4Thz16H4VudHpR5BpdZpzRyR3s6bZSWqM4c
W41w/TDSyMCfwIGdVkCu8C08AI6V0tL1sKstFlnAUQcWjOqaXveMTniybKXJUOnS
C52brpqIXQ9n1pFKFjiwfDTExheQSOfxbb3SmUWsQHS/eNpyq/zubh6Qaagxwbtq
Dti7WqQu68FOk1utkd+jgbKjrBa6Lbm0bQlmPU0kdioiCdg4pYS8NZzJ/EgZF2qR
4JlnWdRQoRlgtlerpuPsgrKlkm4jOFfTygl72d5UX6Aj28g5cFTXWyec728z1LA7
Pi4tlk7RzWrWbtFA1EitQHscHuenfxPLQ2XS6qk75L8aLthNagFJ3caM/Zq7sH/Y
RTvk7urEwPUsE9EOzMf7CYVQpAmX9LHeA25i5BWnVE36GxGad/JPyGds2kBd56IB
7y48OvqxyBJnQjbsO8bMSNPVXR+O/z39PvQs/9viLwIDAQABo4IDUjCCA04wDAYD
VR0TAQH/BAIwADAdBgNVHSUEFjAUBggrBgEFBQcDAQYIKwYBBQUHAwIwDgYDVR0P
AQH/BAQDAgWgMDsGA1UdHwQ0MDIwMKAuoCyGKmh0dHA6Ly9jcmwuc3RhcmZpZWxk
dGVjaC5jb20vc2ZpZzJzMy0wLmNybDBiBgNVHSAEWzBZME4GC2CGSAGG/W4BBxcD
MD8wPQYIKwYBBQUHAgEWMWh0dHA6Ly9jZXJ0aWZpY2F0ZXMuc3RhcmZpZWxkdGVj
aC5jb20vcmVwb3NpdG9yeS8wBwYFZ4EMAQEwgYIGCCsGAQUFBwEBBHYwdDAqBggr
BgEFBQcwAYYeaHR0cDovL29jc3Auc3RhcmZpZWxkdGVjaC5jb20vMEYGCCsGAQUF
BzAChjpodHRwOi8vY2VydGlmaWNhdGVzLnN0YXJmaWVsZHRlY2guY29tL3JlcG9z
aXRvcnkvc2ZpZzIuY3J0MB8GA1UdIwQYMBaAFCVFgWhQJjg9Oy0svs1q2bY9s2Zj
MCkGA1UdEQQiMCCCDHJldGFpbC5lbWFpbIIQd3d3LnJldGFpbC5lbWFpbDAdBgNV
HQ4EFgQU4VbBpr1/YGOerekuujIZukdFh8wwggF8BgorBgEEAdZ5AgQCBIIBbASC
AWgBZgB2AFYUBpov18Ls0/XhvUSyPsdGdrm8mRFcwO+UmFXWidDdAAABU+Wk6R4A
AAQDAEcwRQIhAJXG8I5eErsN3NgLrLGlJQeP9lgRjf0bR5qsJ7Uke6g1AiBW138j
b/xu/MYIE8a4eH9Dnn3bW+NBYuZ7mr0qpTuKQQB1AGj2mPgfZIK+OozuuSgdTPxx
UV1nk9RE0QpnrLtPT/vEAAABU+Wk6rQAAAQDAEYwRAIgZ5bNMMFvRfWrLhKHDfCe
tO7nsfqK/2AtQ0R9YNbFAcYCICvcM/aTISqOLGBTO5DDYPO93FhiUhHM0FJl7xYy
w13LAHUApLkJkLQYWBSHuxOizGdwCjw1mAT5G9+443fNDsgN3BAAAAFT5aTtwAAA
BAMARjBEAiAJPn6eJz05z+ER48W5LX7s5VOzMhCuX6dKJa893usswQIgetUgT9wy
r446M66ckZoXJpOxC8uYWnI/x1K7ztsY0mQwDQYJKoZIhvcNAQELBQADggEBAIgm
l5twTTGzZUDEhZ4SqQyK6VLeL8pScy6kfx43F7/kaoR2craa8mQ3k0t5RhsxU+xf
rl7qLIF9aKbSGrxWkxg/qizwPoeQqeZIzMaRASjMkzBIT53oEZ16C3zMN5tBsI7M
q4k5ktnsvvfbDUldx5dZmQ0aR6xmC63jNRn88ON7kjbvGeFxvMskV9yD5cohxtpB
L0fzt/MzhUrl7cvCdV6WLDiXZvQj5+zqZsQN18vagaTO54si0QmCgcEVZBYJIDB+
XHH1Z78L5huds+Ui4Vv4m2QSP+cCShXwTmIrDdFnkV4STIxayqEu9MhdwyqQ4dPX
3fvRC1u23pzkedrGUlI=
-----END CERTIFICATE-----';


        $key_file = '-----BEGIN PRIVATE KEY-----
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
CGhhGP2A+p6vO+zPhBxzj2ziB7wJgA==
-----END PRIVATE KEY-----';

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

        $decision = base64_encode($_dis);
        $decision = $this->encrypt_decrypt('encrypt', $decision);
        return $decision;
    }
}