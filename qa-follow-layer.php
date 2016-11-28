<?php

class qa_html_theme_layer extends qa_html_theme_base {




	public function q_view_buttons($q_view)
	{
		if (($this->template == 'question') && (!empty($q_view['form']))) {
			$user_level = qa_get_logged_in_level();
			if($user_level > 0)
			{
				$postid=$q_view['raw']['postid'];
				if(qa_opt("qa_follow_enabled")){
					$query = "select userids from ^postfollowers where postid = # and followtype = $";
					$result = qa_db_query_sub($query, $postid, 'F');
					$userid = qa_get_logged_in_userid();
					$followers = qa_db_read_one_value($result, true);
					$followers = explode(",", $followers);
					$follow = in_array($userid, $followers);	
					if(!$follow)
					{
						$q_view['form']['buttons'][] = array("tags" => "name='follow-button' value='$postid' title='".qa_lang_html('follow_lang/follow_pop')."'", "label" => qa_lang_html('follow_lang/follow')); 
					}
					else{
						$q_view['form']['buttons'][] = array("tags" => "name='unfollow-button' value='$postid' title='".qa_lang_html('follow_lang/unfollow_pop')."'", "label" => qa_lang_html('follow_lang/unfollow')); 
					}
				}
			}

		}
		qa_html_theme_base::q_view_buttons($q_view);
	}



}

