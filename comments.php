<?

require_once("config.php");

$connection = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, true);
mysql_select_db(MYSQL_DB, $connection);
mysql_query("SET NAMES 'UTF8'", $connection);




if(isset($_REQUEST['action_comment'])) {
	//request to add comment
	if($_REQUEST['url']!='' && $_REQUEST['user']!='' && $_REQUEST['comment']!='') {
		addComment();
	}
	if(!isset($_REQUEST['is_ajax'])) {
		header('Location: '.$_SERVER['SCRIPT_NAME']);
		exit;
	}
} 
if(isset($_REQUEST['is_ajax'])) {
	echo getComments($_REQUEST['url']);
	exit;
}

echo '<div id="comments">'.getComments($_SERVER['SCRIPT_NAME']).'</div>';
echo getPostCommentForm();
echo getJavascript();



function getComments($url) {
	global $connection;
	$commentContent = '';
	$sql = "select user, comment, date_format(date_left, '%D %b %Y, %T') as comment_date
	from comment
	where url='".mysql_real_escape_string($url)."'
	order by date_left";
	$res = mysql_query($sql, $connection);
	$comment_count = mysql_num_rows($res);
	if($comment_count > 0) {
		$commentContent .= "<h2>".$comment_count." Comment".($comment_count==1?'':'s')."</h2>";
		while(($r = mysql_fetch_assoc($res))) {
			$commentContent .= "<p><span class=\"commentdate\">".$r['comment_date']." by ".htmlentities($r['user'],ENT_QUOTES)."</span>
			<br/>".nl2br(htmlentities($r['comment'],ENT_QUOTES))."</p>";
		}
	} else {
		$commentContent .= "<h2>No Comments</h2>";
	}
	return $commentContent;
}

function getPostCommentForm() {
	return "<h2>Post a Comment</h2>
		<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">
		<input type=\"hidden\" name=\"url\" id=\"url\" value=\"".$_SERVER['SCRIPT_NAME']."\" />
		<input type=\"text\" name=\"user\" id=\"user\" /><br/>
		<textarea name=\"comment\" id=\"comment\" rows=\"5\" cols=\"55\"></textarea><br/>
		<input type=\"submit\" name=\"action_comment\" value=\"Post Comment\" id=\"post_comment\" />
		</form>
		<p>Found an offensive/inappropriate comment? (ok, this would need implementing.)</p>";
}

function addComment() {
	global $connection;
	$sql = "insert into comment (url, user, comment) values ('".mysql_escape_string($_REQUEST['url'])."','".mysql_escape_string($_REQUEST['user'])."','".mysql_escape_string($_REQUEST['comment'])."')";
	mysql_query($sql, $connection);
}

function getJavascript() {
?>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('#post_comment').click(function(e) {
			e.preventDefault();
			$.post('<?=SCRIPT_PATH?>comments.php', { url: $('#url').val(), user: $('#user').val(), comment: $('#comment').val(), is_ajax: true, action_comment: true}, function(data) {
				$('#comments').html(data);
				//clear the form
				$('#comment').val('');
				$('#user').val('');
			});
		});
	});
</script>
<?
}
?>
