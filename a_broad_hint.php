<?php
/*
Plugin Name: A Broad Hint
Plugin URI: http://www.annu.biz/wordpress-plugin-a-broad-hint/
Description: Mit diesem Plugin bindet man vor oder nach jedem Beitrag ein kleinen Text/Banner ein um so auf etwas Aufmerksam zu machen. Das alles können sie in der Administration einschalten.
Author: Eric-Oliver M&auml;chler v/o Annubis [Annubis](http://www.annu.biz "Annubis Blog") 
Version: 1.0
License: Good news, this plugin is free for everyone! Since it's released under the GPL, you can use it free of charge on your personal. If you will using this Plugin on a commercial blog - its also free of charge but please send me an email (plugins@annu.biz) and inform me.
Author URI: http://www.annu.biz
Update Server: http://www.annu.biz
Min WP Version: 2.7 
*/

//Feld aus DB auslesen



if ('insert' == $HTTP_POST_VARS['action']) {
	
	if ( get_magic_quotes_gpc() ) {
		
    $HTTP_POST_VARS = array_map( 'stripslashes_deep', $HTTP_POST_VARS );
    $_POST      = array_map( 'stripslashes_deep', $_POST );
    $_GET       = array_map( 'stripslashes_deep', $_GET );
    $_COOKIE    = array_map( 'stripslashes_deep', $_COOKIE );
    $_REQUEST   = array_map( 'stripslashes_deep', $_REQUEST );
	}
	update_option("abh_field",$HTTP_POST_VARS['abh_field']);
	
}

if ('insert2' == $HTTP_POST_VARS['action']) {
    update_option("abh_opti",$HTTP_POST_VARS['abh_opti']);
}


function abh_link( $content ) {
    global $post, $abh_field, $abh_opti, $abh_sys;
    
    $permalink = urlencode( get_permalink( $post->ID ) );
 	
	if ($abh_opti == 'up')
			{
				return $abh_field . $content;
			}
	else	{
				return $content . $abh_field;
			}


}

add_filter( "the_content", "abh_link" );

//Admin Bereich Feld in DB eintragen
$abh_field = get_option('abh_field');

$abh_opti = get_option('abh_opti');




function abh_option_page() {
?>

<!-- Start Optionen im Adminbereich (xhtml, auÃerhalb php) -->
    <div class="wrap">
      <h2>A Broad Hint - Admin System</h2>
      <br /><br />
      <h3>Code der angezeigt werden soll hier eingeben:</h3>
      <form name="form1" method="post" action="<?=$location ?>">
      	<textarea name="abh_field" cols="50" rows="5"><?php echo get_option("abh_field"); ?></textarea>
        
      	<input type="submit" value="Speichern" />
      	<input name="action" value="insert" type="hidden" />
      </form>
      <br /><br /><br />
      <h3>Wo soll der Code angezeigt werden, Vor dem Text oder Nach dem Text?</h3>
      <br />Aktiv ist: <?php echo get_option("abh_opti"); ?><br /><br />
      <form name="form1" method="post" action="<?=$location ?>">
      	<!--- <input name="abh_opti" value="<?php echo get_option("abh_opti"); ?>" type="text"  size="10" /> Auswahlmöglichkeiten: <strong>up</strong>/<strong>down</strong> (alles andere wird ignoriert)<br/>--->
        
        <select name="abh_opti"><option value="Bitte Auswählen">+++Bitte Auswählen +++</option><option value="up">Davor / Up</option><option value="down">Danach / Down</option>
		</select><br  />
        
        <input type="submit" value="Speichern" />
      	<input name="action" value="insert2" type="hidden" />
      </form>
    </div>
<br /><br /><br />  
<div class="wrap">
<h3>Über dieses Plugin</h3>
<div class="inside">
<p>Dieses Plugin wurde von <a href="http://www.annu.biz/wordpress-plugin-a-broad-hint/" target="_blank">Eric-Oliver Mächler v/o Annubis</a> entwickelt</p>
<p>&copy; 2011, Eric-Oliver Mächler | Dies ist Version: 1.0</div>
</div>

<?php 

}



// Adminmenu Optionen erweitern
function abh_add_menu() {
    add_option("abh_field","Das Plugin muss konfiguriert werden"); // optionsfield in Tabelle TABLEPRÄFIX_options
	
	add_option("abh_opti","up"); // optionsfield in Tabelle TABLEPRÄFIX_options (up/down)
	
    add_options_page('A Broad Hint', 'A Broad Hint', 9, __FILE__, 'abh_option_page');
}

// Registrieren der WordPress-Hooks
add_action('admin_menu', 'abh_add_menu');




register_deactivation_hook(__FILE__, 'my_deinstall');




/* Delete options in database  */
function my_deinstall() {
	global $wpdb;
	
    delete_option('abh_field');
	delete_option('abh_opti');
	
	$wpdb->query("OPTIMIZE TABLE $wpdb->options");
}


?>