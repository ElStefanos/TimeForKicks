<?php

namespace admin;
use dataBase\dataBase;
class loadUsers
{
    private $dataBase;
    public array $users;

    public function __construct()
    {
        $this->dataBase = new dataBase;
        $this->users = $this->dataBase->grabResultsTable('kicks_admin');
    }

    public function formatUsers()
    {
        foreach ($this->users as $key => $value) {
            if($value['active'] == 1) {
                echo "<span class='user active'>";
            } else {
                echo "<span class='user disabled'>";
            }

            echo "<p>".$value['username']."</p>";
            echo "<p>".$value['email']."</p>";
            echo "<p class='actions'>";
            echo "<a href='".__URL__."/dashboard/jobs/modifyUser.php?del&id=".$value['id']."'><i class='fa-regular fa-trash-can'></i></a>";
            if($value['active'] == 1) {
                echo "<a href='".__URL__."/dashboard/jobs/modifyUser.php?suspend&id=".$value['id']."'><i class='fa-solid fa-pause'></i></a></p></span>";
            } else {
                echo "<a href='".__URL__."/dashboard/jobs/modifyUser.php?activate&id=".$value['id']."'><i class='fa-solid fa-play'></i></a></p></span>";
            }

        }
    }
}