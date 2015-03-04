<?
// CONFIG
$items = array('everyword');
$do_not_unfollow = array('181136229');  // IDs
$following=$items[array_rand($items)];
$myscreenname='everytwitpic';
$maxfollowing=360;
$followsaday=100;
$executed_every=60*30;
$connection_used=$tmhOAuth;


// FOLLOW
$user_followers_following=$items[array_rand($items)];
$follows_per_execution=($followsaday/(60*60*24/$executed_every))*2;
$follows_floored=floor($follows_per_execution);
$follows_skipped=$follows_per_execution-$follows_floored;
if(rand(0,1)<=$follows_skipped)$follows_floored++;
if($follows_floored>0) {



$connection_used->request('GET', 'https://api.twitter.com/1.1/users/show.json',
  array('screen_name'=>$myscreenname),
  true // use auth
);
$info=json_decode($connection_used->response['response']);
$fcount=$info->friends_count;

if($fcount>=$maxfollowing) {

$connection_used->request('GET', 'https://api.twitter.com/1.1/friends/ids.json',
  array('screen_name'=>$myscreenname, 'count'=>$follows_floored, 'cursor'=>-9999999999),
  true // use auth
);
$people_im_following=json_decode($connection_used->response['response']);

foreach($people_im_following->ids as $user) {
if(!in_array($user,$do_not_unfollow)) {
$connection_used->request('POST', 'https://api.twitter.com/1.1/friendships/destroy.json',
  array('user_id'=>$user),
  true // use auth
);
}
}

} elseif(true || rand(0,1)==1) { // else follow
$friends=$connection_used->request(
    'GET',    'https://api.twitter.com/1.1/followers/ids.json',
    array(
      'screen_name' => $following,
      'cursor' => '-1',
      'count' => $follows_floored
    )
  );
$friends = json_decode($connection_used->response['response'],true);
$friends = $friends['ids'];
if(isset($friends) && isset($friends[0])) {
foreach ($friends as $friend) {

$connection_used->request('POST','https://api.twitter.com/1.1/friendships/create.json',array(
    'user_id' => $friend,
    'follow' => 'true'));

}
}
}

}
?>