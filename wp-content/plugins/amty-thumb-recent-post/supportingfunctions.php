<?php 

function amty_close_tags($text) {
    $patt_open    = "%((?<!</)(?<=<)[\s]*[^/!>\s]+(?=>|[\s]+[^>]*[^/]>)(?!/>))%";
    $patt_close    = "%((?<=</)([^>]+)(?=>))%";
    if (preg_match_all($patt_open,$text,$matches))
    {
        $m_open = $matches[1];
        if(!empty($m_open))
        {
            preg_match_all($patt_close,$text,$matches2);
            $m_close = $matches2[1];
            if (count($m_open) > count($m_close))
            {
                $m_open = array_reverse($m_open);
                foreach ($m_close as $tag) $c_tags[$tag]++;
                foreach ($m_open as $k => $tag)    if ($c_tags[$tag]--<=0) $text.='</'.$tag.'>';
            }
        }
    }
    return $text;
}

function getStrippedContent($originalContent, $num) {
	$theContent = $originalContent;
	$theContent = strip_shortcodes( $theContent );
	$theContent = preg_replace('/<img[^>]+./','', $theContent);
	$theContent = strip_tags($theContent);
	$theContent = substr($theContent,0,$num+1 );
	$theContent =  amty_close_tags(strip_shortcodes($theContent));
	return trim($theContent);
}

?>