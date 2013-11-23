<?PHP

$sw = false;
$genreURL = '';	//各ジャンルのアーティスト表示ページ
$buffer = '';	//HTMLソースから取得した1行分のソース
$startpos = 0;	//文字列検索で取得するアーティスト名の始点(／の位置)
$slapos = 0;	//文字列検索で取得するアーティスト名の終端(／の位置)
$artist = '';	//文字列から取得したアーティスト名
$sw = false;	//抽出対象行かどうかの判定スイッチ

//ジャンルURLをまとめたtxtを読み込む
$contents = @file('janis_genre.txt');
//書き込み用のtextファイルを開く
$fpw = fopen("janis_artist.html", "w");

fwrite($fpw, '<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><title >JANIS在庫ｱーﾃｨｽﾄ</title></head><body>');


foreach($contents as $line)
{
	$genreURL = str_replace(array("\r\n","\r","\n"),'',$line);
//	echo $genreURL[$i]."<br />";
//	$i = $i + 1;
	//指定したURLのHTMLページをオープン、$fpにHTMLソースを格納
	//echo  '$genreURL[0]='.$genreURL[0];
	$fp = fopen($genreURL, 'r');
	if($fp){
		//$fpに格納したHTMLを1行ずつループ
	    while (!feof($fp))
	    {
	    	//fgets関数でHTMLを1行取得して在庫アーティスト記載部分まで探す
	        $buffer = fgets($fp);
	        if($sw == true)
	        {
	//			if(strpos($buffer,'-- アーティスト名 ここまで --'))
				if(strpos($buffer,'/h5'))
				{
					$sw = false;
				}
				else
				{
					$endpos = strrpos($buffer,'／');
					$startpos = 0;
					$slapos = 0;
					while($slapos !== FALSE)
					{
						//アーティスト名が'／'で区切られている場合はそれぞれを切り取って格納する
				    	//アーティスト名を区切るスラッシュの位置を検索
				    	$slapos = strpos($buffer, '／',$startpos);
				    	//スラッシュの位置がわかったら手前までに表示されているアーティスト名を格納
				    	if($slapos !== FALSE)
				    	{
				    		$artist = substr($buffer,$startpos,($slapos - $startpos));
				    	}
				    	else
				    	{
					    	$artist = substr($buffer,$startpos);
				    	}
				    	//取得したアーティスト名が空欄だった場合は出力しない
				    	if($artist != '')
				    	{
					    	fwrite($fpw, $artist."<br />");
				    	}
				    	$startpos = $slapos + 3;
			    	}
			    	echo "<br />";
		        }
	        }
	    	if($sw == false and strpos($buffer,'-- アーティスト名 ここから --'))
	    	{
		    	$sw = true;
	    	}
	    }
	    fclose($fp);
	}
}
fwrite($fpw, '</body>');
fclose($fpw);
?>