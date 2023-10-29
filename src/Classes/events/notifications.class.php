<?php

namespace events;

use dataBase\dataBase;

class notifications
{
    private $date;

    private $dataBase;

    public function __construct()
    {
        $this->dataBase = new dataBase;
    }

    public function sendNotification($title, $content, $type)
    {
        $this->dataBase->insertTable('kicks_notifications', "(`status`, `title`, `content`)", "('$type', '$title', '$content')");
    }

    public function getNotifications($status, $limit = 0)
    {

        if ($limit > 0) {
            $unread = $this->dataBase->grabResultsTable('kicks_notifications', "WHERE `status` = '$status'  ORDER BY `id` DESC LIMIT $limit");
        } else {
            $unread = $this->dataBase->grabResultsTable('kicks_notifications', "WHERE `status` = '$status' ORDER BY `id` DESC");
        }

        foreach ($unread as $notification) {
            $priority = $notification['status'];
            $title = $notification['title'];
            $content = $notification['content'];
            $date = $notification['date'];

            switch ($priority) {
                case '1':
                    $priority = 'normal';
                    break;

                case '2':
                    $priority = 'important';
                    break;
                default:
                    $priority = ' ';
                    break;
            }

            if ($priority == ' ') {
                echo '<div class="notification-item">
                <h4>' . $title . '</h4>
                <p>' . $content . '</p>
                <p>' . $date . '</p>
                </div>';
            } else {
                echo '<div class="notification-item ' . $priority . '" id="unread">
                <h4>' . $title . '</h4>
                <p>' . $content . '</p>
                <p>' . $date . '</p>
                </div>';
            }
        }
    }

    public function readNotifications()
    {
        $this->dataBase->updateTable('kicks_notifications', "`status` = '0' WHERE `status` != '0'");
    }
}
