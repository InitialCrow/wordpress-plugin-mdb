<?php
require_once ('../../../wp-config.php');
/*---------------------*/

/*-----FUNCTIONS----*/

/*---------------------*/



/**
 * [Function to update like on post]
 * @param  object with data to update
 * @return [object updated]
 */
function updateLike(object $object){
  global $wpdb;
  $wpdb->update(
    $wpdb->prefix."post_like",
    array(
      'ulike' => (int) $object->ulike,
      'udislike' => (int) $object->udislike
    ),
    array(
      'id'=>(int) $object->id
    ),
    array(
      '%d',
      '%d',
      '%d'
    )
  );
  return $object;
}
/**
 * [Function to insert like on post]
 * @param  object with data to insert
 * @return [object inserted]
 */
function insertLike(object $object){
  global $wpdb;
  $wpdb->insert(
    $wpdb->prefix."post_like",
    array(
      'ulike' => (int) $object->ulike,
      'udislike' =>(int) $object->udislike ,
      'post_id'=>(int)$object->post_id
    ),
    array(
      '%d',
      '%d',
      '%d'
    )
  );
  return $object;
}
/**
 * [Function to select like on post]
 * @param  id of post
 * @return [object selected]
 */
function selectLike($post_id){
  global $wpdb;
  $query = $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."post_like WHERE post_id = %d",$post_id);
  $res = $wpdb->get_row($query);
  return $res;
}
/**
 * [Function to choose if we update data or insert]
 * @return [object inserted or updated]
 */
function initLike(){
  $like = (object) $_POST['like'];
  $likeData = selectLike($like->data);
  if(!empty($likeData)){
    if($like->isLike == 1){
      $likeData->ulike+=1;
    }
    else{
      $likeData->udislike+=1;
    }
    if($_SESSION['can_like'][$likeData->post_id]!=true){

      return updateLike($likeData);
    }
    else{
      return $likeData;
    }
  }
  else{
    $insert = (object)[];
    $insert->ulike = 0;
    $insert->udislike = 0;
    $insert->post_id = $like->data;

    if($like->isLike == 1){
      $insert->ulike+=1;
    }
    else{
      $insert->udislike+=1;
    }
    if($_SESSION['can_like'][$insert->post_id]!=true){

      return insertLike($insert);
    }
    else{
      return $insert;
    }
  }
}
/**
 * [Function main scope exec]
 * @return [json new like data]
 */
function main(){
  $like = initLike();
  $_SESSION['can_like'][$like->post_id] = true;
  return json_encode($like);
}
/*---------------------*/

/*-----Main EXEC---*/

/*---------------------*/
echo main();