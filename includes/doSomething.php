<?php

    require_once("../classes/timelogClass.php");

    session_start();

    $timelog = new timelogClass();


    if (isset($_POST['action'])){
        $action = $_POST['action'];

        switch ($action){
            case 'login':
                $timelog->login($_POST['username'], $_POST['password']);
                break;
            case 'logout':
                $timelog->logout();
                break;
            case 'creatuser':

                break;
            case 'toggletimer':
                $timelog->toggleTimer();
                break;
            case 'inittimer':
                $timelog->getActiveTimer();
                break;
            case 'getuserstats':
                $timelog->getUserStats();
                break;
            case 'getlogs':
                $timelog->getTimelogs();
                break;
            case 'setnotedata':
                $timelog->setNoteData($_POST['note'], $_POST['logid']);
                break;
            default:
                break;
        }
    }else{

    }
    echo json_encode($timelog->response);

