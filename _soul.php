<?php

// Constants

define('PASSWORD', 'asdfdfdf');

// Useful stuff

session_start();

function require_login(){
  $_SESSION["loggedin"] or die ('You are not logged in. Please log in.');
}

function log_in(){
  $_SESSION["loggedin"] = true;
}

function log_out(){
  session_start();
  session_destroy();
}

function prase_json_file($filename){
  //get the json from the file
  $fh = fopen($filename,'c+') or die('Failed to read revision data.');
  $jsonInput = fgets($fh);
  $decoded = json_decode($jsonInput,true);
  fclose($fh);
  return $decoded;
}

function get_revisions_data(){
  //get revisions stuff and fix it up a bit
  $revisions = prase_json_file('data/revisions.js');
  if(!$revisions) $revisions = array();
  if(!$revisions['revisions']) $revisions['revisions'] = array();
  if(!$revisions['current']) $revisions['current'] = 0;
  return $revisions;
}

function save_revisions_data($revisions){
  $fh = fopen('data/revisions.js', 'w') or die('Failed to save revision data.');
  fwrite($fh, json_encode($revisions));
  fclose($fh);
}

function die_on_evil_url($entries){
  //make sure our data doesn't contain any evil urls
  foreach ($entries as $entry){
    foreach ($entry as $field){
      if(!is_array($field)) continue;
      if(!$field['url']) continue;
      if(substr($field['url'], 0, 4) == 'http') continue;
      die('Bad url type detected.');
    }
  }
}

function save_new_revision($filename, $data){
  //save new revisions data
  $fh = fopen($filename, 'w') or die('Failed to save data.');
  fwrite($fh, json_encode($data));
  fclose($fh);
}