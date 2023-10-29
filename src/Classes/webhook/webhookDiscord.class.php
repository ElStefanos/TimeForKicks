<?php

namespace webhook;

use dataBase\dataBase;

use events\notifications;

class webhookDiscord
{
    protected array $hook;
    protected array $hooks;
    protected $payload;
    protected $links;
    private object $mysqli;

    private $notifications;

    public function __construct($links)
    {
        $this->links = $links;
        $this->mysqli = new dataBase;
        $this->hooks = $this->mysqli->grabResultsTable('kick_webhooks', "WHERE `type` = 'DISCORD' AND `status` = '1'");
        $this->notifications = new notifications;
    }

    private function buildPayload($link)
    {
        $this->payload = array(
            "content" => "Here si a new relevant product! $link", "username" => "Time For Kicks!"
        );
        return $this->payload;
    }

    public function sendHook()
    {  
        $this->hooks = $this->mysqli->grabResultsTable('kick_webhooks', "WHERE `site` = 'global' AND `status` = '1'");
        foreach($this->hooks as $this->hook) {
            foreach ($this->links as $link) {
                $this->payload = $this->buildPayload($link);
                $headers = ['Content-Type: application/json; charset=utf-8'];
                $ch = curl_init($this->hook['url']);
                curl_setopt($ch, CURLOPT_URL, $this->hook['url']);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->payload, JSON_FORCE_OBJECT));
                $response = curl_exec($ch);
                $this->notifications->sendNotification("New product!", $link, 1);
            }
        }
    }

    public function sendHookSite($site)
    {  
        $this->hooks = $this->mysqli->grabResultsTable('kick_webhooks', "WHERE `site` = '$site' AND `status` = '1'");
        foreach($this->hooks as $this->hook) {
            foreach ($this->links as $link) {
                $this->payload = $this->buildPayload($link);
                $headers = ['Content-Type: application/json; charset=utf-8'];
                $ch = curl_init($this->hook['url']);
                curl_setopt($ch, CURLOPT_URL, $this->hook['url']);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->payload, JSON_FORCE_OBJECT));
                $response = curl_exec($ch);
            }
        }
    }
}
