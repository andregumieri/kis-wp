<?php get_header(); ?>
<?php
////////////////////////////////
// KIS THUMBNAIL
////////////////////////////////
echo "KIS THUMBNAIL - kis_thumbnail_crop <br />";
echo kis_thumbnail_crop(1, 600, 200, "tag", array("class"=>"thumbnail"));

kis_social_google_plusone_button();
?>
<?php get_footer(); ?>