<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');

    $host = 'localhost';    
    $dbuser = 'root';
    $dbpass = '';
    $dbname = 'test';
    $conn = new mysqli($host, $dbuser, $dbpass, $dbname);
    if($conn -> connect_error) {
        echo json_encode(array('status' => 'failed','Error: ' => $conn -> connect_error));
    }
    else if(isset($_POST['test'])) {
        $sql = 'select * from user_info';
        $rlt = $conn -> query($sql);
        if($rlt) {
            echo 'total: '.$rlt -> num_rows.'<br>';
            $ret = array();
            for($i = 0; $i < $rlt -> num_rows; $i ++) {
                $data = $rlt -> fetch_assoc();
                $ret[] = $data;
                echo 'name: '.$data['name'].'<br>';
                echo 'gander: '.$data['gander'].'<br>';
                echo 'phone: '.$data['phone'].'<br>';
                echo 'email: '.$data['email'].'<br><br>';
            }
            echo json_encode($ret);
        }
        $conn -> close();//關閉資料庫
    }
    else {
        $body = file_get_contents('php://input');
        $param = json_decode($body, true);
        if($param['func'] == 'query') {
            $sql = 'select * from user_info';
            $rlt = $conn -> query($sql);
            if($rlt) {
                $ret = array();
                for($i = 0; $i < $rlt -> num_rows; $i ++) {
                    $data = $rlt -> fetch_assoc();
                    $ret[] = $data;
                }
            echo json_encode(array('status' => 'success', 'data' => $ret));
            }
        }
        else if($param['func'] == 'insert') {
            //{'func':'insert', 'data':{'name':'xxxx',''gander':'F', 'phone':'12345','email':'xxx'}}
            //$sql.=$str; ===> $sql=$sql.$str;
            $sql='insert into user_info (name,gander,phone,email) value(';
            $sql.="'".$param['data']['name']."',";
            $sql.="'".$param['data']['gander']."',";
            $sql.="'".$param['data']['phone']."',";
            $sql.="'".$param['data']['email']."')";
            $rlt = $conn->query($sql);
            if($rlt) {
                $sql='select last_insert_id()';
                $rlt2=$conn->query($sql);
                if($rlt2) {
                    $row = $rlt2->fetch_row();
                    echo json_encode(array('status'=>'success', 'id'=>$row[0]));
                }
                else
                    echo json_encode(array('status'=>'fail', 'error'=>'no id'));
            }
            else
                echo json_encode(array('status'=>'fail', 'error'=>mysqli_error($conn)));
        }
        else if($param['func'] == 'update') {
            $sql='update user_info set ';
            $sql.="name='".$param['data']['name']."',";
            $sql.="gander='".$param['data']['gander']."',";
            $sql.="phone='".$param['data']['phone']."',";
            $sql.="email='".$param['data']['email']."'";
            $sql.=" where id=".$param['data']['id'];
            $rlt = $conn->query($sql);
            if($rlt) {
                echo json_encode(array('status'=>'success'));
            }
            else
                echo json_encode(array('status'=>'fail', 'error'=>mysqli_error($conn)));
        }
        else if($param['func'] == 'delete') {
            $sql='delete from user_info where id='.$param['id'];
            $rlt = $conn->query($sql);
            if($rlt) {
                echo json_encode(array('status'=>'success'));
            }
            else
                echo json_encode(array('status'=>'fail', 'error'=>mysqli_error($conn)));
        }
        else{
            echo json_encode(array('status' => 'failed', 'Error' => 'invalid param'));
        }
        $conn -> close();
    }
