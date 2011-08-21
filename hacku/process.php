<html>
<head>
<meta http-equiv="Content-Type"
 content="text/html; charset=iso-8859-1">
<title>Your connections</title>
<link href="style.css" rel="stylesheet" type="text/css">
</head>
<body background="images.jpg">
<?php
require("OAuth.php");

if(!empty($_POST['name']))
{

print "Connections available with <b>$_POST[name]</b><p>\n\n";
print "your choices were:<p>\n\n";

//var_dump($_POST);


if(!empty($_POST['social']))
	{
		print "<ul>\n\n";
		foreach($_POST['social'] as $val)
			{	
				
				//setup variables
				$cc_key  = "dj0yJmk9YWF3ODdGNWZPYjg2JmQ9WVdrOWVsWlZNRk5KTldFbWNHbzlNVEEyTURFNU1qWXkmcz1jb25zdW1lcnNlY3JldCZ4PTUz";
				$cc_secret = "a3d93853ba3bad8a99a175e8ffa90a702cd08cfa";
				//$url = "http://yboss.yahooapis.com/ysearch/news,web,images"; // uncomment this line for doing web, news and images in one single query
				$url = "http://yboss.yahooapis.com/ysearch/web";
				$args = array();
				//$args["q"] = isset($_GET["q"]) ? $_GET["q"] : "$_POST['name'] $val ";	//pass parmeter as ?q=<param> in the URL
				$args["q"] = $_POST["name"] . " " . $val;
				$args["format"] = "json";   //or xml

				//OAuth stuff
				$consumer = new OAuthConsumer($cc_key, $cc_secret);
				$request = OAuthRequest::from_consumer_and_token($consumer, NULL,"GET", $url, $args);
				$request->sign_request(new OAuthSignatureMethod_HMAC_SHA1(), $consumer, NULL);
				$url = sprintf("%s?%s", $url, OAuthUtil::build_http_query($args));

				//echo "URL is :" . $url;
				
				//cURL stuff
				$ch = curl_init();
				$headers = array($request->to_header());
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
				$srp = curl_exec($ch);

				//results		
				$results = json_decode($srp);

				if(($results->bossresponse->web->count))
				{
				// Parse results and extract data to display
					foreach($results->bossresponse->web->results as $result)
					{
					echo "<br>";
					$title = $result->title;
					echo ($title);
					$res ="";
					$res .= "<div><h2>" . $title . "</h2><p>";
					$res .= html_entity_decode(wordwrap($result->abstract, 80, "<br/>"));
					$res .="<p><a href=$result->url>link</a></p></div>";
					echo "<br>";
					print_r($res);
					}
				}
			}
		print"</ul>";
	}
}
else {
	print"please enter name for search\n";	
}
?>
</body>
</html>