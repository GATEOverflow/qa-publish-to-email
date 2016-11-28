<?php

function follow_getfollowers($postid, $type)//$type = 'F' for Followers and 'U' for Unfollowers
{
	$query = "select userids from ^postfollowers where postid = # and followtype = $";
	$result = qa_db_query_sub($query, $postid, $type);
	$followers = qa_db_read_one_value($result, true);
	if(!$followers) return null;
	return explode(",", $followers);
}

function follow_updatefollowers($postid, $userids, $type)//$type = 'F' for Followers and 'U' for Unfollowers
{
	$query = "replace into ^postfollowers(postid, userids,followtype) values(#,$,$)";
	$result = qa_db_query_sub($query, $postid, implode(",",$userids), $type);
}

function qa_check_page_clicks()
{
	global $qa_page_error_html;
	global  $qa_request;

	if ( qa_is_http_post() ) {
		if(qa_opt('qa_follow_enabled') && qa_is_logged_in())
		{
			if(isset($_POST['follow-button'])  )
			{
				$postid = $_POST['follow-button'];	
				$followers = follow_getfollowers($postid, "F");
	//			$unfollowers = qa_follow_getfollowers($postid, "U");
				$userid = qa_get_logged_in_userid();
				if(!in_array($userid, $followers))
				{
					$followers[] = $userid;
				}
				follow_updatefollowers($postid, $followers, "F");
				qa_redirect( qa_request(), $_GET );
			}
			if(isset($_POST['unfollow-button'])  )
			{
				$postid = $_POST['unfollow-button'];	
				$followers = follow_getfollowers($postid, "F");
				$userid = qa_get_logged_in_userid();
				$key = array_search($userid,$followers);
				if($key !== false)
				{
    					array_splice($followers, $key, 1);
				}
				follow_updatefollowers($postid, $followers, "F");
				qa_redirect( qa_request(), $_GET );
			}
		}
	}

	qa_check_page_clicks_base();
}


?>
