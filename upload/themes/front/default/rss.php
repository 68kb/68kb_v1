<?php 
header('Content-Type: text/xml; charset=utf-8');
header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
?>
<rss version="2.0"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
    xmlns:admin="http://webns.net/mvcb/"
    xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
    xmlns:content="http://purl.org/rss/1.0/modules/content/">

    <channel>
    
    <title><?php echo $feed_name; ?></title>

    <link><?php echo $feed_url; ?></link>
    <description><?php echo $page_description; ?></description>
    <dc:language>en-ca</dc:language>
    <dc:creator><?php echo $creator_email; ?></dc:creator>

    <dc:rights>Copyright <?php echo gmdate("Y", time()); ?></dc:rights>
    <admin:generatorAgent rdf:resource="http://www.codeigniter.com/" />

    <?php foreach($articles->result() as $row): ?>
    
        <item>

          <title><?php echo xml_convert($row->article_title); ?></title>
          <link><?php echo site_url("article/".$row->article_uri."/"); ?></link>
          <guid><?php echo site_url("article/".$row->article_uri."/"); ?></guid>

          <description><![CDATA[
      <?php echo $row->article_description; ?>
      ]]></description>
      <pubDate><?php echo date ('r', $row->article_date);?></pubDate>
        </item>

        
    <?php endforeach; ?>
    
    </channel></rss>