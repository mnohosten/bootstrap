<?php
declare(strict_types=1);

namespace RemoteService\Telfa;

/**
 * Class Telfa
 * @package RemoteService\Telfa
 * @todo Dodelat overovani vracenych dat
 * @todo Refaktorovat odesilani pres curl
 */
final class Telfa
{

    /** @var string */
    private $username;
    /** @var string */
    private $password;
    /** @var int */
    private $numberId;

    public function __construct(string $username, string $password, int $numberId)
    {
        $this->username = $username;
        $this->password = $password;
        $this->numberId = $numberId;
    }

    public function callTo(string $number, string $text)
    {
        $args = [
            'number' => $number,
            'text' => $text
        ];
        $url = "https://www4.telfa.cz/calls/announce.xml?" . http_build_query($args);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERPWD, "{$this->username}:{$this->password}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "<xml><nil></nil></xml>");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
    }

    public function sendSmsTo(string $number, string $message)
    {
        $data = <<<SMS
<sms>
    <number_id>{$this->numberId}</number_id>
    <recipient>{$number}</recipient>
    <message>{$message}</message>
</sms>
SMS;
        $url = "https://www4.telfa.cz/sms.xml";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERPWD, "{$this->username}:{$this->password}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $xml = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

    }

    public function listNumbersAndPlans()
    {
        $repeat = 1;
        $page = 1;
        $json = "";

        while ($repeat == 1) {
            $ch = curl_init("https://www4.telfa.cz/recipes.json?page=" . $page);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_USERPWD, "{$this->username}:{$this->password}");
            $res = curl_exec($ch);
            $resdec = json_decode($res);
            $res = trim($res);
            if (count($resdec) >= 30) {
                $repeat = 1;
                if ($page == 1) {
                    $json = substr($res, 0, -1);
                } else {
                    $json .= "," . substr($res, 1, -1);
                }
            } else {
                $repeat = 0;
                if ($page == 1) {
                    $json = $res;
                } else {
                    if (count($resdec) != 0) {
                        $json .= "," . substr($res, 1);
                    } else {
                        $json .= "]";
                    }
                }
            }
            curl_close($ch);
            $page++;
        }
        return json_decode($json, true);
    }

}
