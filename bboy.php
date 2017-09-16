
<?php
function sqlQuery($query) {
	$result = mysql_query($query);
	if (!$result)  {
		die('Could not perform query: \"' . $query . "\"" . mysql_error());
	} else {
		return $result;
	}
}
?>

<!DOCTYPE html>

<html>
	<head>
		<title>Cloud Player</title>
		<link rel="stylesheet" type="text/css" href="style/style.css" />
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/jplayer.js"></script>
		<script type="text/javascript" src="js/backstretch.js"></script>
		<script type="text/javascript" src="js/script.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				$("#header > ul > li").hover(function() {
						$(this).children("ul").addClass("hiddenmenu");
					}, function() {
						$(this).children("ul").removeClass("hiddenmenu");
					}
				);
				$("#playback-warp").click(function() {
					MusicPlayer._warpToggle();
				});
				$("#view-list").click(function() {
					MusicPlayer._changeState("list");
				});
				$("#view-grid").click(function() {
					MusicPlayer._changeState("grid");
				});
				$("#view-detail").click(function() {
					MusicPlayer._changeState("detail");
				});
				$("#sort-artist").click(function() {
					MusicPlayer._sort("artist");
				});
				$("#sort-title").click(function() {
					MusicPlayer._sort("title");
				});
				$("#sort-genre").click(function() {
					MusicPlayer._sort("genre");
				});
				
				$("#sort-album").click(function() {
					MusicPlayer._sort("album");
				});
				$("#filter").keyup(function() {
					MusicPlayer._filter($(this).val());
				});
				$("#button-next").click(function() {
					MusicPlayer._next();
				});
				
				$("#button-back").click(function() {
					MusicPlayer._back();
				});
				
				$("#playback-loop").click(function() {
					MusicPlayer._loopToggle();
				});
				
				
				// Move code below into the object
				// Begin Testing code
				MusicPlayer._constructor("all", [
					<?php
					mysql_connect("internal-db.s152388.gridserver.com", "db152388", "porunga1") or 

die(mysql_error());
					mysql_select_db("db152388_musiclibrary") or die("Could not connect to DB");
					$query = "
						SELECT * 
						FROM users u
						JOIN user_tracks ut ON ut.user_id = u.id
						JOIN tracks t ON t.id = ut.track_id
						LEFT OUTER JOIN albums a on a.id = t.album_id
						LEFT OUTER JOIN artists ar on ar.id = t.artist_id
						LEFT OUTER JOIN genres g on g.id = t.genre_id
						where u.id = 2
					";
					$result = sqlQuery($query);
					if (mysql_num_rows($result) > 0) {
						$row = mysql_fetch_array($result)
						?>
							{title: "<?= $row["track_title"] ?>", artist: "<?= $row

["artist_name"] ?>", album: "<?= $row["album_name"] ?>", genre: "<?= $row["genre_name"] ?>", imagesrc: "coverart/<?= 

$row["coverart"] ?>.jpg", musicurl: "musicupload/<?= $row["filename"] ?>.mp3"}
						<?php
						while($row = mysql_fetch_array($result)) {
							?>
								, {title: "<?= $row["track_title"] ?>", artist: "<?= 

$row["artist_name"] ?>", album: "<?= $row["album_name"] ?>", genre: "<?= $row["genre_name"] ?>", imagesrc: 

"coverart/<?= $row["coverart"] ?>.jpg", musicurl: "musicupload/<?= $row["filename"] ?>.mp3"}
							<?php
						}
					}
					?>
				], "#jplayer");
				
				//MusicPlayer._loadPlaylist();
				
				
				// New Shit
				$.backstretch("style/bg.jpg");
					
				$("#main-playlist li span").hover(function() {
						$(this).animate({paddingRight: "30px", color: "red"}, 300, function() 

{
							$(this).css({color: "red"});
						});
						
					},
					function() {
						$(this).animate({paddingRight: "20px", color: "red"}, 300, function() 

{
							$(this).css({color: "black"});
						});
					}
				);
				
				
				var sideVisible = true;
				$("#main-playlist-toggle").click(function() {
					var timeOut = 600;
					sideVisible = !sideVisible;
					if(sideVisible) {
						$("#main-playlist").animate({left: "-220px"}, timeOut);
						$("#main-content").animate({left: "10px"}, timeOut);
					} else {
						$("#main-playlist").animate({left: "10px"}, timeOut);
						$("#main-content").animate({left: "230px"}, timeOut);
					}
				});
			});
		</script>
		
	</head>
	<body>
		<div id="header" class="row">
			<input type="text" id="filter"/>
			<ul>
				<li>
					<span id="pause">Pause</span>
					<span id="play">Play</span>
				</li>
				<li>
					<span id="mute">Volume</span>
					<span id="unmute">Volume</span>
					<ul id="volume-dropdown">
						<li>
							<div id="volume-bar">
								<div id="volume-current"></div>
							</div>
						</li>
						<li id="max">Max</li>
					</ul>
				</li>
				<li>
					Playback
					<ul>
						<li id="playback-loop">Loop</li>
						<li id="playback-warp">Warp</li>
					</ul>
				</li>
				<li>Sort
					<ul>
						<li id="sort-title">Title</li>
						<li id="sort-artist">Artist</li>
						<li id="sort-album">Album</li>
						<li id="sort-genre">Genre</li>
					</ul>
				</li>
				<li>View
					<ul>
						<li id="view-list">List</li>
						<li id="view-grid">Grid</li>
						<li id="view-detail">Detail</li>
					</ul>
				</li>
				<li>Upload(Disabled)
					<!-- <ul>
						<li>Manage</li>
						<li>Upload</li>
						<li>Settings</li>
						<li>About</li>
						<li>Logout</li>
					</ul> -->
				</li></ul>
		</div>
		<div id="main" class="row">
			<div id="main-playlist-toggle" class="col">
			</div>
			<div id="main-playlist" class="col overflow-y">
				<!--
				<ul class="playlists">
					<li><span>All</span></li>
					<li><span>&hearts;</span></li>
					<li><span>Most</span></li>
					<li><span>Recent</span></li>
					<li><span>New</span></li>
				</ul>
				-->
				<h1>Playlists</h1>
				<ul id="custom-playlists" class="playlist">
				</ul>
			</div>
			<div id="main-content" class="col scroll-y">
			<!-- start -->
			
			<div id="jplayer"></div> <!-- hide this -->
		
			
			
			<!-- end -->
				<ol class="view-grid" id="song-list">
					
				</ol>
			</div>
		</div>
		<div id="infobar">
			<div id="current-playbar">
			</div>
			
			<div id="current-seekbar">
			</div>
			<div id="current-info">
				<span id="current-track"></span>
				<span id="current-artist"></span>
			</div>
			<div id="button-back">
				&lsaquo;
			</div>
			<div id="button-next">
				&rsaquo;
			</div>
			<div id="time-duration">
				<span id="current-time"></span>
				/ <span id="current-duration"></span>
			</div>
		</div>
	</body>
</html>