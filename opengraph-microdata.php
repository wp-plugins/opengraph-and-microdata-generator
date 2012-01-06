<?php
/*
Plugin Name: Opengraph and Microdata Generator
Plugin URI: http://www.itsabhik.com/wp-plugins/opengraph-microdata-generator.html
Description: WprdPress Plugin for Facebook Opengraph and Google Schema
Version: 1.0
Author: Abhik
Author URI: http://www.itsabhik.com/
License: GPL3
*/

// Let's get the image part first.
function iafbschema_image()
{
    $Html = get_the_content();
    $extrae = '/<img .*src=["\']([^ ^"^\']*)["\']/';
		preg_match_all( $extrae  , $Html , $matches );
	$image = $matches[1][0];

    if($image)
    {
		$pos = strpos($image, site_url());
		if ($pos === false) {
			return $_SERVER['HTTP_HOST'].$image;
		} else {
			return $image;
		}
    } else {
		return plugins_url( 'images/thumbnail.png', __FILE__ );
    }
}
// Then the required fields for Facebook OPengraph and Schema Microdata.
function iafbschema() {
	if(is_single() ){
		if (have_posts()) : while (have_posts()) : the_post(); 
			$iafbschemameta[]=get_the_title($post->post_title);
			$iafbschemameta[]=get_permalink();
			$iafbschemameta[]=iafbschema_image();
			$iafbschemameta[]=get_option('blogname');
			$iafbschemameta[]= substr(strip_tags(get_the_content()), 0, 250)." ..." ;
		endwhile; endif; 
	}else{
		$iafbschemameta[]=get_option('blogname');
		$iafbschemameta[]=get_option('siteurl');
		$iafbschemameta[]=iafbschema_image();
		$iafbschemameta[]=get_option('blogname');
		$iafbschemameta[]=get_option('blogdescription');
	}
	
	echo metas($iafbschemameta);
}
//Lastly, put them togather and add to <head> section.
function metas($iafbschemameta){
	$iametainfo.="\n";
	$iametainfo.='<!-- ItsAbhik.com Facebook OpenGraph and Schema Microdata Generator Start -->';
	$iametainfo.="\n";
	$iametainfo.='<meta property="og:title" content="'.$iafbschemameta[0].'" />';
	$iametainfo.="\n";
	$iametainfo.='<meta property="og:type" content="blog" />';
	$iametainfo.="\n";
	$iametainfo.='<meta property="og:url" content="'.$iafbschemameta[1].'" />';
	$iametainfo.="\n";
	$iametainfo.='<meta property="og:image" content="'.$iafbschemameta[2].'" />';
	$iametainfo.="\n";
	$iametainfo.='<meta property="og:site_name" content="'.$iafbschemameta[3].'" />';
	$iametainfo.="\n";
	$iametainfo.='<meta property="og:description" content="'.$iafbschemameta[4].'" />';
	$iametainfo.="\n";
	$iametainfo.='<meta itemprop="name" content="'.$iafbschemameta[0].'">';
	$iametainfo.="\n";
	$iametainfo.='<meta itemprop="description" content="'.$iafbschemameta[4].'">';
	$iametainfo.="\n";
	$iametainfo.='<meta itemprop="image" content="'.$iafbschemameta[2].'">';
	$iametainfo.="\n";
	$iametainfo.='<meta itemprop="url" content="'.$iafbschemameta[1].'">';
	$iametainfo.="\n";
	$iametainfo.='<!-- ItsAbhik.com Facebook OpenGraph and Schema Microdata Generator End -->';
	$iametainfo.="\n";
	return $iametainfo;
}
add_action('wp_head', 'iafbschema');
?>