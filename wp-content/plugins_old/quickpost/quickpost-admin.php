<?php
/*
Plugin Name: QuickPost
Plugin URI: http://web.twelvehorses.com/projects/quickpost/
Description: QuickPost for Wordpress. Based on BlogThis! and the Tumblr Bookmarklet
Author: Josh Kenzer
Version: 0.7
Author URI: http://www.RadicalBehavior.com
License: GPL
*/

function QuickPost_install(){
	add_option("defaultcategorytext", "");
	add_option("defaultcategoryphoto", "");
	add_option("defaultcategoryquote", "");
	add_option("defaultcategoryvideo", "");
	add_option("qp_editor_enabled", "t");
	add_option("qp_comments_enabled", "t");
}
class QuickPost_Admin {
		function add_config_page() {
			global $wpdb, $table_prefix;
			if ( function_exists('add_submenu_page') ) {
				add_submenu_page('plugins.php', 'QuickPost', 'QuickPost', 1, basename(__FILE__), array('QuickPost_Admin','config_page'));
			}
		}
		function config_page()
		{
			global $_REQUEST,$wpdb;
			if($_REQUEST['update']) {
				update_option("defaultcategorytext",$_REQUEST['defaultcategorytext']);
				update_option("defaultcategoryphoto",$_REQUEST['defaultcategoryphoto']);
				update_option("defaultcategoryquote",$_REQUEST['defaultcategoryquote']);
				update_option("defaultcategoryvideo",$_REQUEST['defaultcategoryvideo']);
				update_option("qp_editor_enabled",$_REQUEST['qp_editor_enabled']);
				update_option("qp_comments_enabled",$_REQUEST['qp_comments_enabled']);
			}

					?>
					<style>
					.but {
						background-color:#dddddd;border:2px groove black;padding:5px;padding-top:0px;padding-bottom:2px;color:black;font-family:sans-serif; text-decoration:none; font-size:10pt;margin-top:5px
					}
					.qp-td {
						border:1px solid #cfcfcf;
						padding:5px;
					}
					</style>
					<form action="plugins.php?page=quickpost-admin.php&amp;action=udcat" method="post">
					<br><br>


					<table border=0 cellspacing=3 cellpadding=2 width="80%" align=center>
					<tr>
					<td colspan=2><h2>QuickPost Settings</h2></td>
					</tr>
					<tr>
					<td colspan=2>Enable WYSIWYG Editor in QuickPost <input name="qp_editor_enabled" value='t' type="checkbox" <?if(get_option("qp_editor_enabled") == 't') echo "checked";?>></h2></td>
					</tr>
					<tr>
					<td colspan=2>Enable comments on entries created with QuickPost <input name="qp_comments_enabled" value='t' type="checkbox" <?if(get_option("qp_comments_enabled") == 't') echo "checked";?>></h2></td>
					</tr>
					<tr valign=top>
						<td class="qp-td"  width="200" >
						<h3 class="dbx-handle">Select the default category for each of your QuickPost types.</h3>
						<table border=0 cellspacing=3 cellpadding=2 align=center>
						  <tr>
						    <td nowrap><b>Category</b></td>
						    <td><b>Text/Link</b></td>
						    <td><b>Photos</b></td>
						    <td><b>Quotes</b></td>
						    <td><b>Video</b></td>
						  </tr>
						<?php
								$cats=$wpdb->get_results("SELECT t.term_id as term_id, name FROM $wpdb->terms t, $wpdb->term_taxonomy tt WHERE t.term_id = tt.term_id AND taxonomy = 'category' ORDER BY name");
								foreach($cats as $cat) {
								  echo "<tr>\n";
									if($cat->term_id==get_option("defaultcategorytext")) $t="checked"; else $t="";
									if($cat->term_id==get_option("defaultcategoryphoto")) $p="checked"; else $p="";
									if($cat->term_id==get_option("defaultcategoryquote")) $q="checked"; else $q="";
									if($cat->term_id==get_option("defaultcategoryvideo")) $v="checked"; else $v="";?>
									<td nowrap><?php echo $cat->name;?></td>
									<td align="center"><input type="radio" name="defaultcategorytext" <? echo $t ?> value="<?php echo $cat->term_id;?>"></td>
                  <td align="center"><input type="radio" name="defaultcategoryphoto" <? echo $p ?> value="<?php echo $cat->term_id;?>"></td>
                  <td align="center"><input type="radio" name="defaultcategoryquote" <? echo $q ?> value="<?php echo $cat->term_id;?>"></td>
                  <td align="center"><input type="radio" name="defaultcategoryvideo" <? echo $v ?> value="<?php echo $cat->term_id;?>"></td>
                </tr>
									<?php
								}?>
							</table>
							<br>
						<input type="submit" value="Update" name="update">
						</td>
						<td class="qp-td" >
						<h3 class="dbx-handle">QuickPost Bookmarklet</h3>
						<div class="dbx-content"><br><br>
						Firefox Users: Drag and drop the "QuickPost" bookmarklet below to your links toolbar.<br><br>
						Dear Internet Explorer Users: Yours is a harder path to walk.. Right click the bookmarklet below and select "Add to favourites". Your IE will probably tell you that this is an "Unsafe bookmark to add". Ignore your smart arse browser and click OK. The setup will thus be completed.
						<br><br><br><br><center><a class="but" href="javascript:
						var imgstr='';
						var reg=new RegExp('&');
            for(i=0;i<document.images.length;i++){
              if(! reg.test(document.images[i].src)){
              imgstr = imgstr + document.images[i].src + ',';
              }
            }
            var d=document;
            var w=window;
            var e=w.getSelection;
            var k=d.getSelection;
            var x=d.selection;
            var s=(e?e():(k)?k():(x?x.createRange().text:0));
            var f='<?php echo get_settings('siteurl');?>/wp-content/plugins/quickpost/quickpost.php';
            var l=d.location;
            var e=encodeURIComponent;
            var p='?imagez='+imgstr;
            var u= '&u=' + e(l.href);
            var t= '&t=' + e(d.title);
            var s= '&s=' + e(s);
            var g= f+p+u+t+s;

            function a(){
              if(!w.open(g,'t','toolbar=0,resizable=0,scrollbars=1,status=1,width=700,height=500')){
                l.href=g;
              }
            }
            if(/Firefox/.test(navigator.userAgent)){
              setTimeout(a,0);
            }else{
              a();
            }
            void(0);">QuickPost-it</a></center>
                         
						</td>
					</tr></table>


					</form>


					<?php

		}
}


add_action('init', 'QuickPost_install');
add_action('admin_menu', array('QuickPost_Admin','add_config_page'));
