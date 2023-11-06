<?php

namespace webhook;

use dataBase\dataBase;

use events\notifications;

use DateTime;

class webhookDiscord
{
    protected array $hook;
    protected array $hooks;
    protected $payload;
    protected string $price;
    protected $links;
    private object $mysqli;
    private $notifications;

    private $title;
    private $dateTime;

    public $formattedDatetime;

    public $store;

    public function __construct($links, $priceEstm = "N/A", $title = "New product!", $store = "")
    {   
        $this->links = $links;
        $this->price = $priceEstm;
        $this->title = $title;
        $this->store = $store;
        if($this->title != "New product!") $this->title = "Found product that matches: " . $this->title;
        if($this->store == "") $this->store = "N/A";
        if($this->price == "") $this->price = "N/A";
        $this->price = trim($this->price);
        $this->mysqli = new dataBase;
        $this->hooks = $this->mysqli->grabResultsTable('kick_webhooks', "WHERE `type` = 'DISCORD' AND `status` = '1'");
        $this->notifications = new notifications;
        $this->dateTime = new DateTime('2023-11-05T20:15:00.000Z');
        $this->formattedDatetime = $this->dateTime->format('Y-m-d\TH:i:s.v\Z');
    }

    private function buildPayload($link)
    {
        $embed = array(
            "title" => $this->title,
            "description" => "**Price: " . $this->price . "**\n\nProduct link: ". $link,
            "url" => $link,
            "color" => 2990733,
            "author" => array(
                "name" => "Time For Kicks Monitor"
            ),
            "footer" => array(
                "text" => "Monitor developed by Time For Kicks"
            )
        );
        
        $this->payload = array(
            "content" => "New product was found on " . $this->store,
            "embeds" => array($embed), // Wrap the embed in an array
            "username" => "Time For Kicks",
            "attachments" => array()
        );

        return $this->payload;
    }

    public function sendHook()
    {  
        $this->hooks = $this->mysqli->grabResultsTable('kick_webhooks', "WHERE `site` = 'global' AND `status` = '1'");
        foreach($this->hooks as $this->hook) {


            if(!is_array($this->links)) {

                $this->payload = $this->buildPayload($this->links);
                $headers = ['Content-Type: application/json; charset=utf-8'];
                $ch = curl_init($this->hook['url']);
                curl_setopt($ch, CURLOPT_URL, $this->hook['url']);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->payload, JSON_PRETTY_PRINT));

                $response = curl_exec($ch);
                $this->notifications->sendNotification("New product!", $this->links, 1);

                return;
            }

            foreach ($this->links as $link) {
                $this->payload = $this->buildPayload($link);
                $headers = ['Content-Type: application/json; charset=utf-8'];
                $ch = curl_init($this->hook['url']);
                curl_setopt($ch, CURLOPT_URL, $this->hook['url']);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->payload, JSON_PRETTY_PRINT));
                $response = curl_exec($ch);
                $this->notifications->sendNotification("New product!", $link, 1);
            }
        }
    }

    public function sendHookSite($site)
    {  
        $this->hooks = $this->mysqli->grabResultsTable('kick_webhooks', "WHERE `site` = '$site' AND `status` = '1'");
        foreach($this->hooks as $this->hook) {

            
            if(!is_array($this->links)) {
                $this->payload = $this->buildPayload($this->links);
                $headers = ['Content-Type: application/json; charset=utf-8'];
                $ch = curl_init($this->hook['url']);
                curl_setopt($ch, CURLOPT_URL, $this->hook['url']);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->payload, JSON_PRETTY_PRINT));
                $response = curl_exec($ch);
                return;
            }

            foreach ($this->links as $link) {
                $this->payload = $this->buildPayload($link);
                $headers = ['Content-Type: application/json; charset=utf-8'];
                $ch = curl_init($this->hook['url']);
                curl_setopt($ch, CURLOPT_URL, $this->hook['url']);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->payload, JSON_PRETTY_PRINT));
                $response = curl_exec($ch);
                return;
            }
        }
    }
}
