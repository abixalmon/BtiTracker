<?php

class rss_reader
    {

    function rss_reader()
      {
     // constructor
    }


    // private
    // find the content in $text between <$tag> and </$tag>
    function get_tag_value($text, $tag)
      {
        $StartTag = "<$tag";
        $EndTag = "</$tag";
        
        $StartPosTemp = strpos($text, $StartTag);
        $StartPos = strpos($text, '>', $StartPosTemp);
        $StartPos = $StartPos + 1;
        
        $EndPos = strpos($text, $EndTag);
        
        if($EndPos > $StartPos) {
            $text   = substr ($text, $StartPos, ($EndPos - $StartPos));
        } else {
            $text = '';
        }
        
        $text = str_replace('<![CDATA[', '', $text);
        $text = str_replace(']]>', '', $text);
        
        return $text;
    }

    // input the full rss stream
    // output rss as array
    // array(channel => (title,link,description,item=>array(title,link,description,category,comments,pubDate,guid)))
    function rss_to_array($rss_flux)
      {
        $fullrss=explode("<channel>",$rss_flux);
        array_shift($fullrss);
        $rss=array();
        $i=0;
        foreach($fullrss as $r)
          {
          $rss[$i]["title"]=$this->get_tag_value($r,"title");
          $rss[$i]["link"]=$this->get_tag_value($r,"link");
          $rss[$i]["description"]=$this->get_tag_value($r,"description");
          $rss[$i]["copyright"]=$this->get_tag_value($r,"copyright");
          $rss[$i]["language"]=$this->get_tag_value($r,"language");
          $rss[$i]["lastBuildDate"]=$this->get_tag_value($r,"lastBuildDate");
          $items=explode("<item>",$r);
          array_shift($items);
          $j=0;
          foreach($items as $item)
            {
            $rss[$i]["item"][$j]["title"]=$this->get_tag_value($item,"title");
            $rss[$i]["item"][$j]["link"]=$this->get_tag_value($item,"link");
            $rss[$i]["item"][$j]["description"]=$this->get_tag_value($item,"description");
            $rss[$i]["item"][$j]["category"]=$this->get_tag_value($item,"category");
            $rss[$i]["item"][$j]["comments"]=$this->get_tag_value($item,"comments");
            $rss[$i]["item"][$j]["pubDate"]=$this->get_tag_value($item,"pubDate");
            $rss[$i]["item"][$j]["guid"]=$this->get_tag_value($item,"guid");
            $j++;
          }
          $i++;
        }
        unset($fullrss);
        unset($r);

        return $rss;

    }

}

?>