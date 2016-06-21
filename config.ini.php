<?php
session_start();

try {
    /**************************************
    * Create databases and                *
    * open connections                    *
    **************************************/
    // Create (connect to) SQLite database in file
    $file_db = new PDO('sqlite:database.sqlite3');
    // Set errormode to exceptions
    $file_db->setAttribute(PDO::ATTR_ERRMODE,
                            PDO::ERRMODE_EXCEPTION);
  }
  catch(PDOException $e) {
    // Print PDOException message
    echo "failed : ".$e->getMessage();
  }
  
  //test if the database is populated
  $query = $file_db->query("SELECT count(*) as yes FROM sqlite_master WHERE type='table' AND name='user'");
  $query->execute() or die("Could'nt exec user table check");
  $query = $query->fetch();
  if(!$query['yes']){
    //No `user` database, create one
        $file_db->exec("CREATE TABLE IF NOT EXISTS user (id_user INTEGER PRIMARY KEY, nom TEXT, email TEXT)");
        $file_db->exec("CREATE TABLE IF NOT EXISTS user_session (id_session INTEGER PRIMARY KEY, id_user INTEGER)");
        $file_db->exec("CREATE TABLE IF NOT EXISTS swagornot (id_son INTEGER PRIMARY KEY, owner INTEGER)");
        $file_db->exec("CREATE TABLE IF NOT EXISTS swagornot_picture (id_picture INTEGER PRIMARY KEY,id_son INTEGER, owner INTEGER, picture TEXT)");
        $file_db->exec("CREATE TABLE IF NOT EXISTS swagornot_vote (id_vote INTEGER PRIMARY KEY,id_winner INTEGER,id_loser INTEGER, owner INTEGER, picture TEXT)");
        $file_db->exec("INSERT INTO user (nom, email) VALUES ('Arthur','arthur@launchleap.com')");
        $file_db->exec("INSERT INTO user (nom, email) VALUES ('Julien','julien@launchleap.com')");
        $file_db->exec("INSERT INTO user (nom, email) VALUES ('Thomas','thomas@launchleap.com')");
        $file_db->exec("INSERT INTO user (nom, email) VALUES ('Charles','charles@launchleap.com')");
  }

//No session initiated
if(empty($_SESSION['id'])){
    //log in as a random user for test purposes
    $getUser = $file_db->query("SELECT * FROM user ORDER BY RANDOM() LIMIT 1;");
    $user = $getUser->fetch(PDO::FETCH_ASSOC);
    
    //create new session
    $newSession = $file_db->prepare("INSERT INTO user_session (id_user) VALUES (:user)");
    $newSession->bindParam(":user",$user['id_user']);
    $newSession->execute() or die('Unable to create new session');
    
    //populate session var
    $_SESSION['userID'] = $user['id_user'];
    $_SESSION['id'] = $file_db->lastInsertId();
}
?>
