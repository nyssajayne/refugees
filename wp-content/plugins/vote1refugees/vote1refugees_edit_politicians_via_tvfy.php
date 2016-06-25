<?php
    global $wpdb;
?>

<div class="wrap">
    <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

    <?php 
        settings_fields( 'flag_descriptions' );
        $flags = array();
        $flags[] = "Red means the politician or candidiate " . esc_attr(get_option('flag_description_red'));
        $flags[] = "Orange means the politician or candidiate " . esc_attr(get_option('flag_description_orange'));
        $flags[] = "Green means the politician or candidiate " . esc_attr(get_option('flag_description_green'));
        $flags[] = "Unknown means the politician or candidiate " . esc_attr(get_option('flag_description_unknown'));

        foreach ($flags as $flag) {
            echo "<p>" . $flag . "</p>";
        }
    ?>

    <form action="<?php echo plugin_dir_url( __FILE__ ); ?>vote1refugees_form.php" method="post" id="politicians">

        <script>
            jQuery(document).ready(function() {
                jQuery('#pollie_nav a').on('click', function(e)  {
                    var currentAttrValue = jQuery(this).attr('href');
 
                    // Show/Hide Tabs
                    jQuery('#pollies ' + currentAttrValue).show().siblings().hide();

                    // Change/remove current tab to active
                    //jQuery(this).parent('li').addClass('active').siblings().removeClass('active');

                    console.log(currentAttrValue);
 
                    e.preventDefault();
                });
            });
        </script>

        <div id="pollie_nav">
            <a href="#reps">Representatives</a>
            <a href="#senate">Senate</a>
        </div>

        <?php $politicians_array = vote1refugees_fetch_politicians_via_tvfy();

        $reps = array();
        $senate = array();

        foreach ($politicians_array as $politicians) {
            if(strcmp($politicians['house'], 'representatives') == 0) {
                $reps[] = $politicians;
            }
            elseif (strcmp($politicians['house'], 'senate') == 0) {
                $senate[] = $politicians;
            }
        } ?>

        <div id="pollies">
        <table id="reps">

            <tr>
                <td></td>
                <td></td>
                <td><strong>Any comments?</strong></td>
                <td><strong>Contact the politician</strong></td>
            </tr>
        
        <?php foreach($reps as $politician):
            echo "<tr>";
            echo "<td>" . $politician['name'] . " (" . $politician['party'] . ")</td>";
            echo "<td><select name=\"flag[" . $politician['id'] . "]\" id=\"" . $politician['id'] . "\">";

            $red = null;
            $orange = null;
            $green = null;
            $unknown = null;

            $comment = '';
            $contact = '';

            if(isset($politician['comment'])) {
                $comment = $politician['comment'];
            }

            if(isset($politician['contact'])) {
                $contact = $politician['contact'];
            }

            switch ($politician['flag']) {
                case '1':
                    $green = 'selected';
                    break;
                case '2':
                    $orange = 'selected';
                    break;
                case '3':
                    $red = 'selected';
                    break;
                case '4':
                    $unknown = 'selected';
                    break;
                    
                default:
                    $unknown = 'selected';
                    break;
            }

            echo "<option value=\"1\"" . $green . ">Green</option>
                <option value=\"2\"" . $orange . ">Orange</option>
                <option value=\"3\"" . $red . ">Red</option>
                <option value=\"4\"" . $unknown . ">Unknown</option>
                </select></td>";
            echo "<td><input type=\"text\" id=\"comment_" . $politician['id'] . "\" name=\"comment_" . $politician['id'] . "\" value=\"" . $comment . "\" /></td>";
            echo "<td><input type=\"text\" id=\"contact_" . $politician['id'] . "\" name=\"contact_" . $politician['id'] . "\" value=\"" . $contact . "\" /></td>";
            echo "</tr>";

        endforeach;
        ?>
    </table>

    <table id="senate" style="display:none;">
            <tr>
                <td></td>
                <td></td>
                <td><strong>Any comments?</strong></td>
                <td><strong>Contact the politician</strong></td>
            </tr>
    <?php foreach($senate as $politician):
            echo "<tr>";
            echo "<td>" . $politician['name'] . " (" . $politician['party'] . ")</td>";
            echo "<td><select name=\"flag[" . $politician['id'] . "]\" id=\"" . $politician['id'] . "\">";

            $red = null;
            $orange = null;
            $green = null;
            $unknown = null;

            $comment = '';
            $contact = '';

            if(isset($politician['comment'])) {
                $comment = $politician['comment'];
            }

            if(isset($politician['contact'])) {
                $contact = $politician['contact'];
            }

            switch ($politician['flag']) {
                case '1':
                    $green = 'selected';
                    break;
                case '2':
                    $orange = 'selected';
                    break;
                case '3':
                    $red = 'selected';
                    break;
                case '4':
                    $unknown = 'selected';
                    break;
                    
                default:
                    $unknown = 'selected';
                    break;
            }

            echo "<option value=\"1\"" . $green . ">Green</option>
                <option value=\"2\"" . $orange . ">Orange</option>
                <option value=\"3\"" . $red . ">Red</option>
                <option value=\"4\"" . $unknown . ">Unknown</option>
                </select></td>";
            echo "<td><input type=\"text\" id=\"comment_" . $politician['id'] . "\" name=\"comment_" . $politician['id'] . "\" value=\"" . $comment . "\" /></td>";
            echo "<td><input type=\"text\" id=\"contact_" . $politician['id'] . "\" name=\"contact_" . $politician['id'] . "\" value=\"" . $contact . "\" /></td>";
            echo "</tr>";

        endforeach;
        ?>
    </table>
    </div>
    <p><?php submit_button(); ?></p>
</form>
</div>