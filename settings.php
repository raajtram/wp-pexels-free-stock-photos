<?php

/*
	Pexels: Free Stock Photos
	https://raajtram.com/plugins/pexels/
	Author: Raaj Trambadia (https://raajtram.com)
*/

/* Add the menu */

function add_admin_menu() {
  add_submenu_page(
    'upload.php',
    __( 'Pexels: Free Stock Photos', 'pexels_fsp_images' ),
    __( 'Pexels Photos', 'pexels_fsp_images' ),
    'edit_posts',
    'pexels_fsp_images_settings',
    'pexels_fsp_images_settings_page'
  );
  add_action('admin_init', 'register_pexels_fsp_images_options');
}

add_action( 'admin_menu', 'add_admin_menu', 99 );

/* Register the options */

function register_pexels_fsp_images_options(){
    register_setting('pexels_fsp_images_options', 'pexels_fsp_images_options', 'pexels_fsp_images_options_validate');
    add_settings_section('pexels_fsp_images_options_section', '', '', 'pexels_fsp_images_settings');
    add_settings_field('attribution-id', __('Attribution', 'pexels_fsp_images'), 'pexels_fsp_images_render_attribution', 'pexels_fsp_images_settings', 'pexels_fsp_images_options_section');
}

/* Attribution field */

function pexels_fsp_images_render_attribution(){
    $options = get_option('pexels_fsp_images_options');
    echo '<label><input name="pexels_fsp_images_options[attribution]" value="true" type="checkbox"'.(!$options['attribution'] | $options['attribution']=='true'?' checked="checked"':'').'> '.__('Automatically insert image captions with attribution.', 'pexels_fsp_images').'</label>';
}


/* The actions inside the iframe i.e. the works!
 ** The function must begin with "media_" so wp_iframe() adds media css styles)
 */
function media_pexels_fsp_images_tab() {
	?>
	<style scope>
	   body,html{background:#fff}::-webkit-input-placeholder{color:#aaa!important}::-moz-placeholder{color:#aaa!important}:-ms-input-placeholder{color:#aaa!important}[placeholder]{text-overflow:ellipsis}.flex-images{overflow:hidden}.flex-images .item{float:left;margin:4px;background:#f3f3f3;box-sizing:content-box;overflow:hidden;position:relative}.flex-images .item>img{display:block;width:auto;height:100%}.flex-images .download{opacity:0;transition:opacity .3s;position:absolute;top:0;right:0;bottom:0;left:0;cursor:pointer;background:rgba(0,0,0,.65);color:#eee;text-align:center;font-size:14px;line-height:1.5}.flex-images .item.uploading .download,.flex-images .item:hover .download{opacity:1}.flex-images .download img{position:absolute;top:0;left:0;right:0;bottom:0;margin:auto;height:32px;opacity:.7}.flex-images .download div{position:absolute;left:0;right:0;bottom:15px;padding:0 5px}.flex-images .download a{color:#eee}#pexels_fsp_settings_icon{opacity:.65;transition:.3s;box-shadow:none}#pexels_fsp_settings_icon:hover{opacity:1}
	</style>
	<div style="padding:10px 15px 25px">
	   <form id="pexels_fsp_images_form" style="margin:0">
	       <div style="line-height:1.5;margin:1em auto;max-width:640px;position:relative">
	           <input id="q" type="text" value="" style="width:80%;padding:7px 32px 7px 9px" autofocus placeholder="<?= htmlspecialchars(__('Search for images. E.g. "rose", "new york traffic", "sunset"', 'pexels_fsp_images')); ?>">
						 <button type="submit" id="" style="padding: 9px 24px; line-height:1; height: auto; font-size: 14px;" title="<?= _e('Search', 'pexels_fsp_images'); ?>" class="button button-primary button-large"><span class="">Search</span></button>
	       </div>
	   </form>
		 <div style="clear:both;padding:12px 0 0;text-align:center">

       <p>1. Search for your desired photos by using relevant keywords</p>
       <p>2. Find your photo, click on it <u>once</u> to download it. It will be added to your Image Library</p>
       <p>3. Use the image as desired i.e. as a Featured Image or in your posts/page via the Gutenberg/Classic Editor</p>
       <p style="font-size: 11px; font-style: italic;">NOTE: Clicking an image will download and add the image to your Media Library. Do <u>not</u> click an image just to "preview" it.</p>
       </div>
	   <div id="pexels_fsp_results" class="flex-images" style="margin-top:20px;padding-top:25px;border-top:1px solid #ddd"></div>
	</div>
	<script>
	   /*
		 		FlexImages - https://goodies.pixabay.com/jquery/flex-images/demo.html
				Kudos to Simon Steinberger at Pixabay.com!
		 */
	   !function(t){function e(t,a,r,n){function o(t){r.maxRows&&d>r.maxRows||r.truncate&&t&&d>1?w[g][0].style.display="none":(w[g][4]&&(w[g][3].attr("src",w[g][4]),w[g][4]=""),w[g][0].style.width=l+"px",w[g][0].style.height=u+"px",w[g][0].style.display="block")}var g,l,s=1,d=1,f=t.width()-2,w=[],c=0,u=r.rowHeight;for(f||(f=t.width()-2),i=0;i<a.length;i++)if(w.push(a[i]),c+=a[i][2]+r.margin,c>=f){var m=w.length*r.margin;for(s=(f-m)/(c-m),u=Math.ceil(r.rowHeight*s),exact_w=0,l,g=0;g<w.length;g++)l=Math.ceil(w[g][2]*s),exact_w+=l+r.margin,exact_w>f&&(l-=exact_w-f),o();w=[],c=0,d++}for(g=0;g<w.length;g++)l=Math.floor(w[g][2]*s),h=Math.floor(r.rowHeight*s),o(!0);n||f==t.width()||e(t,a,r,!0)}t.fn.flexImages=function(a){var i=t.extend({container:".item",object:"img",rowHeight:180,maxRows:0,truncate:0},a);return this.each(function(){var a=t(this),r=t(a).find(i.container),n=[],o=(new Date).getTime(),h=window.getComputedStyle?getComputedStyle(r[0],null):r[0].currentStyle;for(i.margin=(parseInt(h.marginLeft)||0)+(parseInt(h.marginRight)||0)+(Math.round(parseFloat(h.borderLeftWidth))||0)+(Math.round(parseFloat(h.borderRightWidth))||0),j=0;j<r.length;j++){var g=r[j],l=parseInt(g.getAttribute("data-w")),s=l*(i.rowHeight/parseInt(g.getAttribute("data-h"))),d=t(g).find(i.object);n.push([g,l,s,d,d.data("src")])}e(a,n,i),t(window).off("resize.flexImages"+a.data("flex-t")),t(window).on("resize.flexImages"+o,function(){e(a,n,i)}),a.data("flex-t",o)})}}(jQuery);
	   function getCookie(k){return(document.cookie.match('(^|; )'+k+'=([^;]*)')||0)[2]}
	   function setCookie(n,v,d,s){var o=new Date;o.setTime(o.getTime()+864e5*d+1000*(s||0)),document.cookie=n+"="+v+";path=/;expires="+o.toGMTString()}
	   function escapejs(s){return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,"\\'");}
		 /* set vars */
	   var post_id = <?= absint($_REQUEST['post_id']) ?>,
	       per_page = 15,
				 form = jQuery('#pexels_fsp_images_form'), photos, q;
		/* form submit action */
	   form.submit(function(e){
	       e.preventDefault();
	       q = jQuery('#q', form).val();
	       jQuery('#pexels_fsp_results').html('');
	       call_api(q, 1);
	   });
		 /*
		 		Initiate the call to the Pexels API (https://www.pexels.com/api/)
				An API key is provided upon request, hence hardcoded here.
				No user action required upon installation.
		 */
	   function call_api(q, p){
	     var xhr = new XMLHttpRequest();
	     xhr.open('GET', 'https://api.pexels.com/v1/search?query='+encodeURIComponent(q)+'&per_page='+per_page+'&page='+p);
			 xhr.setRequestHeader('Authorization', '563492ad6f91700001000001a626f8ddac7d48a88fc0856cb7622195');
	     xhr.onreadystatechange = function(){
	       if (this.status == 200 && this.readyState == 4) {
	           var data = JSON.parse(this.responseText);
	           if (!(data.total_results > 0)) {
	               jQuery('#pexels_fsp_results').html('<div style="color:#bbb;font-size:24px;text-align:center;margin:40px 0">—— <?= _e('No matches', 'pexels_fsp_images') ?> ——</div>');
	               return false;
	           }
	           render_px_results(q, p, data);
	       }
	     };
	     xhr.send();
	     return false;
	   }

	   function render_px_results(q, p, data){
		 	 /* store for upload click */
	     photos = data['photos'];
			 /* pagination */
	     pages = Math.ceil(data.total_results/per_page);
	     var s = '';
	     jQuery.each(data.photos, function(k, v) {
				 s += '<div class="item upload" data-url="'+v.src.original+'" data-user="'+v.photographer+'" data-src-page="'+v.url+'" data-w="'+v.width+'" data-h="'+v.height+'"><img src="'+v.src.medium+'"><div class="download"><img src="<?= plugin_dir_url(__FILE__) . 'img/download.svg' ?>"><div>'+(v.width)+'×'+(v.height)+'<br><a href="'+v.url+'/" rel="noopener nofollow" target="_blank"">'+v.photographer+' at Pexels</a></div></div></div>';
	     });
	     jQuery('#pexels_fsp_results').html(jQuery('#pexels_fsp_results').html()+s);
	     jQuery('#load_animation').remove();
			 /* add a "load more" button which will make a new call to the next page */
			 if (p < pages) {
				 jQuery('#pexels_fsp_results').after('<div style="clear:both;padding:45px 0 0;text-align:center"><button type="button" id="load_animation" class="button button-primary button-large"><span class="">Load More Images</span></button></div>');

				 jQuery('#load_animation').click(function(){
							call_api(q, p+1);
				 });
			 }

	     jQuery('.flex-images').flexImages({rowHeight: 260});
	   }

		 jQuery(document).on('click', '.upload', function(e) {
       if (jQuery(e.target).is('a')) return;
       jQuery(document).off('click', '.upload');
       // loading animation
       jQuery(this).addClass('uploading').find('.download img').replaceWith('<img src="<?= plugin_dir_url(__FILE__) . 'img/loading.svg' ?>" style="height:80px !important">');
       jQuery.post('.', {
         pexels_fsp_upload: "1",
				 image_url: jQuery(this).data('url'),
				 image_src_page: jQuery(this).data('src-page'),
         image_user: jQuery(this).data('user'),
         q: q,
         wpnonce: '<?= wp_create_nonce('pexels_fsp_images_security_nonce'); ?>'
       },
       function(data){

         if (parseInt(data) == data) {
           window.location = 'upload.php?item=' + data;
           jQuery(".uploading").find('.download img').replaceWith('<img src="<?= plugin_dir_url(__FILE__) . 'img/check.svg' ?>" style="height:40px !important; opacity: 1;">');
         }
         else {
           alert(data);
         }
     });
       return false;
	   });
	</script>
	<?php
}



/* HTML for the settings page */

function pexels_fsp_images_settings_page() { ?>
    <div class="wrap">
    <h2><?= _e('Pexels: Free Stock Photos Images', 'pexels_fsp_images'); ?></h2>
    <?php media_pexels_fsp_images_tab(); ?>
    <form method="post" action="options.php">
        <?php
            settings_fields('pexels_fsp_images_options');
            do_settings_sections('pexels_fsp_images_settings');
            submit_button();
        ?>
    </form>
    <hr style="margin-bottom:20px">
    <p>
        Photos provided by <a href="https://pexels.com/?utm_source=wordpress-plugin&utm_medium=settings-page" target="_blank"><img src="<?= plugin_dir_url(__FILE__).'img/pexels-logo.png' ?>" style="margin:0 3px;position:relative;top:4px" width="80"></a>. Plugin developed and maintained by <a href="https://raajtram.com/?utm_source=pexels-wp-plugin&utm_medium=settings-page">@raajtram</a>.
    </p>
    <p>
        If this plugin helped you, you can show your appreciation by <a href="https://wordpress.org/support/plugin/wp-pexels-free-stock-photos/reviews/#new-post" target="_blank" rel="noopener nofollow">leaving a review</a>.

    </p>
        View the <a href="https://raajtram.com/plugins/pexels/?utm_source=pexels-wp-plugin&utm_medium=settings-page" target="_blank">plugin documentation</a> for help.
    <p>
    </p>
    </div>
<?php }

/* validate settings */

function pexels_fsp_images_options_validate($input){
    global $pexels_fsp_images_gallery_languages;
    $options = get_option('pexels_fsp_images_options');
    if ($input['attribution']) $options['attribution'] = 'true'; else $options['attribution'] = 'false';
    return $options;
}

/* Download and upload the chosen image */
if (isset($_POST['pexels_fsp_upload'])) {
	// "pluggable.php" is required for wp_verify_nonce() and other upload related helpers
	if (!function_exists('wp_verify_nonce'))
		require_once(ABSPATH . 'wp-includes/pluggable.php');

	$nonce = $_POST['wpnonce'];
	if (!wp_verify_nonce($nonce, 'pexels_fsp_images_security_nonce')) {
		die('Error: Invalid request.');
		exit;
	}

	$post_id                    = absint($_REQUEST['post_id']);
	$pexels_fsp_images_settings = get_option('pexels_fsp_images_options');

	/* get image file */
	$response = wp_remote_get(esc_url($_POST['image_url']));
	if (is_wp_error($response))
		die('Error: ' . $response->get_error_message());

	$q_tags = explode(' ', sanitize_text_field($_POST['q']));
	array_splice($q_tags, 2);
	foreach ($q_tags as $k => $v) {
		// remove ../../../..
		$v          = str_replace("..", "", $v);
		$v          = str_replace("/", "", $v);
		$q_tags[$k] = sanitize_file_name($v);
	}
	/* Name the file. */
	$path_info = pathinfo(esc_url($_POST['image_url']));
	$file_name = sanitize_file_name(implode('_', $q_tags) . '_' . time() . '.' . $path_info['extension']);

	$wp_upload_dir     = wp_upload_dir();
	$image_upload_path = $wp_upload_dir['path'];

	if (!is_dir($image_upload_path)) {
		if (!@mkdir($image_upload_path, 0777, true))
			die('Error: Failed to create upload folder ' . $image_upload_path);
	}

	$target_file_name = $image_upload_path . '/' . $file_name;
	$result           = @file_put_contents($target_file_name, $response['body']);
	unset($response['body']);
	if ($result === false)
		die('Error: Failed to write file ' . $target_file_name);

	/* Check/verify that we are dealing with an image only */
	require_once(ABSPATH . 'wp-admin/includes/image.php');
	if (!wp_read_image_metadata($target_file_name)) {
		unlink($target_file_name);
		die('Error: File is not an image.');
	}

	/* add the image title */
	$image_title = ucwords(implode(', ', $q_tags));

	/* add the caption */
	$attachment_caption = '';
	if (!$pexels_fsp_images_settings['attribution'] | $pexels_fsp_images_settings['attribution'] == 'true')
		$attachment_caption = '<a href="' . esc_url($_POST['image_src_page']) . '" target="_blank" rel="noopener">' . sanitize_text_field($_POST['image_user']) . '</a> at Pexels';

	/* insert the attachment */
	$wp_filetype = wp_check_filetype(basename($target_file_name), null);
	$attachment  = array(
		'guid' => $wp_upload_dir['url'] . '/' . basename($target_file_name),
		'post_mime_type' => $wp_filetype['type'],
		'post_title' => preg_replace('/\.[^.]+$/', '', $image_title),
		'post_status' => 'inherit'
	);
	$attach_id   = wp_insert_attachment($attachment, $target_file_name, $post_id);
	if ($attach_id == 0)
		die('Error: File attachment error');

	$attach_data = wp_generate_attachment_metadata($attach_id, $target_file_name);
	$result      = wp_update_attachment_metadata($attach_id, $attach_data);

  /* @TODO: Need to find a more reliable way to fix metadata error

	if ($result === false)
	die('Error: File attachment metadata error');

  */

	$image_data                 = array();
	$image_data['ID']           = $attach_id;
	$image_data['post_excerpt'] = $attachment_caption;
	wp_update_post($image_data);

	echo $attach_id;
	exit;
}

?>
