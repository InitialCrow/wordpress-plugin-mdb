<?php
require_once ('../../../wp-config.php');
/*---------------------*/

/*-----FUNCTIONS----*/

/*---------------------*/

/**
 * [Function to insert hour ]
 * @param  object with data to insert
 * @return [object inserted]
 */
function insertHours(object $object){
  global $wpdb;
  $query = $wpdb->insert($wpdb->prefix . "hours_mdb", array(
    'openH' => (int) $object->openH,
    'closeH' => (int) $object->closeH,
    'openM' => (int) $object->openM,
    'closeM'=> (int) $object->closeM,
    'day'=>$object->day
  ), array( '%d','%d','%d','%d','%s'));

  $object->insertId = $wpdb->insert_id;
  return $object;
}
/**
 * [Function to construct new hour object]
 * @return [object hours]
 */
function initHours(){
  if(!isset($_POST['hours']))
    return false;
  $days = [
    "Lundi",
    "Mardi",
    "Mercredi",
    "Jeudi",
    "Vendredi",
    "Samedi",
    "Dimanche",
  ];

  $hours = $_POST['hours'];
  $hours['day'] = $days[$hours['index']];
  $hours = (object) $hours;
  return $hours;
}
/**
 * [Function to create delete hour order]
 * @return [id hour to delete]
 */
function initDelete(){
  if(!isset($_POST['delete']))
    return false;

  $id = (int) $_POST['delete'];

  return $id;
}
/**
 * [Function to delete hour]
 * @return [id hour to delete]
 */
function deleteHours($id){
  global $wpdb;
  $query = $wpdb->delete($wpdb->prefix . "hours_mdb", array(
    'id' => (int) $id
  ), array( '%d'));

}
/**
 * [Function main scope exec]
 * @return [json new hours data]
 */
function main(){
  $hours = initHours();
  $delete = initDelete();
  if($hours != false){
    $hours = insertHours($hours);
  }
  if($delete !=false){
    $query = deleteHours($delete);
  }
  return json_encode($hours);
}

/*---------------------*/

/*-----Main EXEC---*/

/*---------------------*/
echo main();

