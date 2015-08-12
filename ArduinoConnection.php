<?php
/**
 * User: Noah Parker (nparker)
 * Date: 7/16/15
 * Time: 10:27 AM
 * email: nparker@dandb.com
 * Interning: 6/1/15 - 8/15/15
 * Created by PhpStorm.
 */

class ArduinoConnection {

    public $sock; //the socket that connects to the server IP
    private $SERVERIP = "172.16.25.9"; //server IP
    private $PORT = 1025; //The port that the server is on
    public $oldMessage = "";


    public function __construct() //a constructor method
    {
        $this->connectToServer();
    }

    public function connectToServer(){//connects to the server
        var_dump($this->sock);
        $this->sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);// creates a socket
        socket_connect($this->sock, $this->SERVERIP, $this->PORT);//creates a connection to the server
        $this->oldMessage = " ";
        var_dump($this->sock);
    }

    public function sendMessage($message){//sends a message to the server //MAIN METHOD USED//
        if(!$this->sock){//tries to reconnect to server
            echo "Try again";
            $this->connectToServer();
        }
        echo "send message";
        socket_write($this->sock,$message); //should send a message to the server
    }

    public function constructMessage($array){//takes the info and makes it into a message that can be sent to the server
        $message = "";
        $message .= $array['group'] . "," . $array['name']. "," . $array['culprit']. "," . $array['status'];
        return $message;
    }
}
?>