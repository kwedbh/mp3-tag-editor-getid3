<?php 


// Start of http authenticate
$realm = 'Restricted area';

$users = array('admin' => 'mypass');


if (empty($_SERVER['PHP_AUTH_DIGEST'])) {
    header('HTTP/1.1 401 Unauthorized');
    header('WWW-Authenticate: Digest realm="'.$realm.
           '",qop="auth",nonce="'.uniqid().'",opaque="'.md5($realm).'"');

    die('Text to send if user hits Cancel button');
}


// analyze the PHP_AUTH_DIGEST variable
if (!($data = http_digest_parse($_SERVER['PHP_AUTH_DIGEST'])) ||
    !isset($users[$data['username']]))
    die('Wrong Credentials!');


// generate the valid response
$A1 = md5($data['username'] . ':' . $realm . ':' . $users[$data['username']]);
$A2 = md5($_SERVER['REQUEST_METHOD'].':'.$data['uri']);
$valid_response = md5($A1.':'.$data['nonce'].':'.$data['nc'].':'.$data['cnonce'].':'.$data['qop'].':'.$A2);

if ($data['response'] != $valid_response)
    die('Wrong Credentials!');

// ok, valid username & password
echo 'You are logged in as: ' . $data['username'];


// function to parse the http auth header
function http_digest_parse($txt)
{
    // protect against missing data
    $needed_parts = array('nonce'=>1, 'nc'=>1, 'cnonce'=>1, 'qop'=>1, 'username'=>1, 'uri'=>1, 'response'=>1);
    $data = array();
    $keys = implode('|', array_keys($needed_parts));

    preg_match_all('@(' . $keys . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $txt, $matches, PREG_SET_ORDER);

    foreach ($matches as $m) {
        $data[$m[1]] = $m[3] ? $m[3] : $m[4];
        unset($needed_parts[$m[1]]);
    }

    return $needed_parts ? false : $data;
}

// end of http authenticate

$site_name = 'kwed.ng';

$site_abb = "KWED";

$twitter_id = '@kwedbh';

$facebook_id = 'kwedbh';

$genre = "@kwedbh";

$date_only  = date("Y-m-d");


 if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   $args = $_POST['music'];
   
  // print_r($args);
   
  // die();
   

   $forum_url = $args['title'];
   
   $featured = $_POST['featured'] ?? '';


// $limg= trim($args['img']); 
$limg = TRUE;
$imgtemp = explode(".", "$limg"); 
$imgext = end($imgtemp);
$imgfolder="./music/images/"; 
$year = date("Y");
// $title= $args['title'];
$rstg = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
$newimage="[".$site_abb."]_".($args['artiste']."_".str_replace(' ', "_", $args['title']))."_".$rstg.".".$imgext."";
$rstimage=$imgfolder.$newimage;





$lmusic= $args['url']; 

// $args['img'] = $rimg;

$mp3_comment = "This Music Was Downloaded From ".htmlspecialchars($site_name).". Stay Updated By Liking/Following Us - Facebook: $facebook_id, Twitter: @$twitter_id."; 


$musictemp = explode(".", "$lmusic"); 
$m_text = end($musictemp);
$song_folder="./music/"; 
$year = date("Y");
// $title= $args['title'];
$rstg = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
$newimage="[".$site_abb."]_".($args['artiste']."_".str_replace(' ', "_", $args['title']))."_".$rstg.".".$m_text."";
$rstimage=$song_folder.$newimage;
if(copy($lmusic,$rstimage)){ 
    $rmusic="./music/$newimage"; 
    
    $song_location =  $song_folder."/".$newimage; 
    
    
}else{
    
    die("There was a problem copying the song");
    
}

    require_once('getid3/getid3.php');

$mp3_tagformat = 'UTF-8';

$mp3_handler = new getID3;

$mp3_handler->setOption(array('encoding'=>$mp3_tagformat));

require_once('getid3/write.php');

$mp3_writter = new getid3_writetags;

$mp3_writter->filename = $song_location; 

$mp3_writter->tagformats = array('id3v1', 'id3v2.3');  

$mp3_writter->overwrite_tags = true;          

$mp3_writter->tag_encoding   = $mp3_tagformat;  


$art = "./music/images/default.png";
$mp3_data['title'][]   = $args['title'] ." | | ". $site_name." ";
$mp3_data['artist'][]  = $args['artiste'];                
$mp3_data['album'][]   = $args['album'];
$mp3_data['year'][]    = $date_only;
$mp3_data['genre'][]   = $genre;
$mp3_data['comment'][] = $mp3_comment;

$mp3_data['attached_picture'][0]['data'] = file_get_contents($art,true);
$mp3_data['attached_picture'][0]['picturetypeid'] = 2;
$mp3_data['attached_picture'][0]['description'] = './music/images/default.png';
$mp3_data['attached_picture'][0]['mime'] = 'image/png'; 
                
$mp3_writter->tag_data = $mp3_data; 


if($mp3_writter->WriteTags()) {  

        
        print "<script>alert('Uploaded')</script>";
        print '<script>window.location.href = "./music/'.$newimage.'";</script>';
        die();
             

 }else{
     echo"<br />Failed to write tags!<br>".implode("<br /><br />",$mp3_writter->errors);
     
     die();
}
 }



 ?>


<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">

    <title><?php $site_abb ?>!</title>
  </head>
  <body class="container">

  <div class="text-center">


    <img src="music/images/logo.png" alt="" class="img-fluid">

  </div>


  <div class="card mt-5">
  <div class="card-body">
    <div class="container">

  <form action="<?php print htmlspecialchars($_SERVER['PHP_SELF']) ?>" class="forms-sample" method="POST" enctype="multipart/form-data">


  <?php require_once 'formFields.php'; ?>

  <button type="submit" class="btn btn-primary mr-2">Create</button>
</form>

  </div>
  </div>
</div>
    



    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js" integrity="sha384-VHvPCCyXqtD5DqJeNxl2dtTyhF78xXNXdkwX1CZeRusQfRKp+tA7hAShOK/B/fQ2" crossorigin="anonymous"></script>
    -->
  </body>
</html>

