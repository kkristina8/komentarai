<?php

//fetch_comment.php

$DATABASE_HOST = 'sql212.epizy.com';
$DATABASE_USER = 'epiz_29739673';
$DATABASE_PASS = 'obRMG8ZqmhRbNWX';
$DATABASE_NAME = 'epiz_29739673_komentarai';

try{
    $connect = new PDO('mysql:host=' .$DATABASE_HOST . ';dbname=' .$DATABASE_NAME . ';charset=utf8', $DATABASE_USER, $DATABASE_PASS);
}
catch(PDOException $exception){
    exit('Failed to connect to database');
}

$query = "
SELECT * FROM kduomenys 
WHERE ats_id = '0' 
ORDER BY id DESC
";

$statement = $connect->prepare($query);

$statement->execute();

$result = $statement->fetchAll();
$output = '';

function time_elapsed_string($datetime, $full = false)
{
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);
    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;
    $string = array('y' => 'year', 'm' => 'month', 'w' => 'week', 'd' => 'day', 'h' => 'hour', 'i' => 'minute', 's' => 'second');
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }
    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

foreach($result as $row)
{
    $output .= '
    <div class="container">
        <div class="panel" style="background: rgba(255, 255, 255, 0.7);">
            <div class="panel-heading text-center" style="height: 50px;">
                <h4 class="panel-title pull-left" style="padding-top: 7.5px;"><b>'.$row["vardas"].'</b> <i>'.time_elapsed_string($row["data"]).'</i></h4>
                <div class="pull-right">
                    <button type="button" class="btn btn-link reply" id="'.$row["id"].'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/>
                    <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/>
                    </svg>
                    Reply</button>
                </div>
            </div>
            <div class="panel-body">'.$row["komentaras"].'</div>
        </div>
    </div>
    ';
    $output .= get_reply_comment($connect, $row["id"]);
}



echo $output;

function get_reply_comment($connect, $parent_id = 0, $marginleft = 0)
{
    $query = "
    SELECT * FROM kduomenys WHERE ats_id = '".$parent_id."'
    ";
    $output = '';
    $statement = $connect->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    $count = $statement->rowCount();
    if($parent_id == 0)
    {
        $marginleft = 0;
    }
    else
    {
        $marginleft = $marginleft + 48;
    }
    if($count > 0)
    {
        foreach($result as $row)
        {
            $output .= '
            <div class="container">
                <div class="panel" style="background: rgba(255, 255, 255, 0.7); margin-left:'.$marginleft.'px">
                    <div class="panel-heading text-center" style="height: 50px;">
                        <h4 class="panel-title pull-left" style="padding-top: 7.5px;"><b>'.$row["vardas"].'</b> <i>'.time_elapsed_string($row["data"]).'</i></h4>
                    </div>
                    <div class="panel-body">'.$row["komentaras"].'</div>
                </div>
            </div>
            ';
            $output .= get_reply_comment($connect, $row["id"], $marginleft);
        }
    }
    return $output;
}

?>
