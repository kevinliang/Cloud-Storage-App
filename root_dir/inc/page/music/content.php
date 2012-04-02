<h2>Music</h2>
<?php
$perms = getPermissions("view");
$perms = implode("','",$perms);

echo "<table><tr><td style='width:300px'>";
echo "<div class='box'><h3><img src='src/img/icons/music.png'> All Songs</h3>";
echo "<a class='button button-view' href='javascript:show_media(\"music\",\"all\",\"all\");'>View All</a> ";
echo "<a class='button button-update' href='javascript:show_media(\"music\",\"random\",40);'>Shuffle</a> ";
echo "<a class='button button-top' href='javascript:show_media(\"music\",\"popular\",40);'>Most Played</a> </div>";

$result = mysql_query("SELECT * FROM music_playlist WHERE user_id=".login_id);
$num_playlists = mysql_num_rows($result);
$height = min($num_playlists*14+15,200);
echo "<div class='box'><h3><img src='src/img/icons/text_list_numbers.png'> Playlists ($num_playlists)</h3><div style='height:$height"."px' class='media_side'><ul>";
while($row = mysql_fetch_array($result)){
    echo "<li id='music_playlist_num_$row[id]'><span class='fright'><a href='javascript:deletePlaylist($row[id]);'>X</a></span> ";

    echo "<a href='javascript:show_media(\"music\",\"playlist\",$row[id],\"$row[name]\");'>$row[name]</a></li>";
}
echo "</ul></div></div>";

$result=mysql_query("SELECT * FROM music_artists ORDER BY name");
$height = 500;
echo "<div class='box'><h3><img src='src/img/icons/ipod.png'> Artists</h3><div class='media_side' style='height:$height"."px'> ";
$current_letter=null;
while($row = mysql_fetch_array($result)){
	$count = mysql_num_rows(mysql_query("SELECT * FROM music_map WHERE root_id IN ('$perms') AND artist_id='$row[id]'"));
	if($count>0){
		if($current_letter!=substr($row['name'],0,1)){
			if($current_letter!=null){echo "</ul>";}
			$current_letter=substr($row['name'],0,1);
			echo "<h4>$current_letter</h4><ul>";
		}
		echo "<li><a href='javascript:show_media(\"music\",\"artist\",$row[id],\"$row[name]\");'>$row[name] ($count)</a></li>";;
	}
}
echo "</ul></div></div>";

$result=mysql_query("SELECT DISTINCT year FROM music_map");
$years=array();

while($row=mysql_fetch_array($result)){
	$count=mysql_num_rows(mysql_query("SELECT * FROM music_map WHERE root_id IN ('$perms') AND year='$row[year]' ORDER BY year DESC"));
	if($row['year']>1900&&$row['year']<date('Y')+2)
		$years[$row['year']]=$count;
}
krsort($years);
$num_years=count($years);
$height = min($num_years*14+15,200);
echo "<div class='box'><h3><img src='src/img/icons/calendar.png'> Year ($num_years)</h3><div style='height:$height"."px' class='media_side'><ul>";
foreach($years as $year => $count){
    if($count == 0)
	continue;
    echo "<li><a href='javascript:show_media(\"music\",\"year\",\"$year\",\"$year\");'>$year ($count)</a></li>";
}
echo "</ul></div></div>";

$result=mysql_query("SELECT DISTINCT genre FROM music_map");
$genres=array();

while($row=mysql_fetch_array($result)){
	$sql_genre=mysql_escape_string($row['genre']);
	$count=mysql_num_rows(mysql_query("SELECT * FROM music_map WHERE root_id IN ('$perms') AND genre='$sql_genre'"));
	if($count>1)
		$genres[$row['genre']]=$count;
}
arsort($genres);
$num_genres=count($genres);
$height = min($num_genres*14+15,200);
echo "<div class='box'><h3><img src='src/img/icons/television.png'> Genre ($num_genres)</h3><div style='height:$height"."px' class='media_side'><ul>";
foreach($genres as $genre => $count){
    if($count == 0)
	continue;
    $genre_name = $genre;
    if($genre_name == "")
	$genre_name = "Unknown";
    echo "<li><a href='javascript:show_media(\"music\",\"genre\",\"$genre\",\"$genre_name\");'>$genre_name ($count)</a></li>";
}
echo "</ul></div></div>";
echo "</td><td class='td_gap'></td><td><div class='box'><h3><img src='src/img/icons/music.png'> Songs <span class='title_media' id='title_music'></span></h3><div class='content_media' id='content_music'><p>Welcome to the music app, please select an option from the left to browse your music.</p></div></div></td></tr></table>";
