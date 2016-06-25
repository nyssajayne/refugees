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

                    e.preventDefault();
                });
            });
        </script>

        <div id="pollie_nav">
            <a href="#reps">Representatives</a>
            <a href="#senate">Senate</a>
        </div>

        <?php $candidates_array = vote1refugees_fetch_candidates();

        $reps = array();
        $senate = array();

        foreach ($candidates_array as $candidates) {
            if(strcmp($candidates['house'], 'reps') == 0) {
                $reps[] = $candidates;
            }
            elseif (strcmp($candidates['house'], 'senate') == 0) {
                $senate[] = $candidates;
            }
        } ?>

        <div id="pollies">
        <table id="reps">

            <tr>
                <td></td>
                <td></td>
                <td><strong>Contact phone</strong></td>
                <td><strong>Contact Email</strong></td>
                <td><strong>Any comments?</strong></td>
            </tr>
        
        <?php foreach($reps as $candidate):
            echo "<tr>";
            echo "<td>" . $candidate['name'] . " (" . $candidate['partyName'] . ")</td>";
            echo "<td><select name=\"flag[" . $candidate['id'] . "]\" id=\"" . $candidate['id'] . "\">";

            $red = null;
            $orange = null;
            $green = null;
            $unknown = null;

            switch ($candidate['flag']) {
                case '1':
                    $green = 'selected';
                    break;
                case '2':
                    $red = 'selected';
                    break;
                case '3':
                    $orange = 'selected';
                    break;
                case '4':
                    $unknown = 'selected';
                    break;
                    
                default:
                    $unknown = 'selected';
                    break;
            }

            echo "<option value=\"1\"" . $green . ">Green</option>
                <option value=\"2\"" . $red . ">Red</option>
                <option value=\"3\"" . $orange . ">Orange</option>
                <option value=\"4\"" . $unknown . ">Unknown</option>
                </select></td>";
            echo "<td><input type=\"text\" id=\"phone_" . $candidate['id'] . "\" name=\"phone_" . $candidate['id'] . "\" value=\"" . $candidate['phone'] . "\" /></td>";
            echo "<td><input type=\"text\" id=\"email_" . $candidate['id'] . "\" name=\"email_" . $candidate['id'] . "\" value=\"" . $candidate['email'] . "\" /></td>";
            echo "<td><input type=\"text\" id=\"comment_" . $candidate['id'] . "\" name=\"comment_" . $candidate['id'] . "\" value=\"" . $candidate['comment'] . "\" /></td>";
            echo "</tr>";

        endforeach;
        ?>
    </table>

    <table id="senate" style="display:none;">
            <tr>
                <td></td>
                <td></td>
                <td><strong>Contact phone</strong></td>
                <td><strong>Contact Email</strong></td>
                <td><strong>Any comments?</strong></td>
            </tr>
    <?php foreach($senate as $candidate):
            echo "<tr>";
            echo "<td>" . $candidate['name'] . " (" . $candidate['partyName'] . ")</td>";
            echo "<td><select name=\"flag[" . $candidate['id'] . "]\" id=\"" . $candidate['id'] . "\">";

            $red = null;
            $orange = null;
            $green = null;
            $unknown = null;

            switch ($candidate['flag']) {
                case '1':
                    $green = 'selected';
                    break;
                case '2':
                    $red = 'selected';
                    break;
                case '3':
                    $orange = 'selected';
                    break;
                case '4':
                    $unknown = 'selected';
                    break;
                    
                default:
                    $unknown = 'selected';
                    break;
            }

            echo "<option value=\"1\"" . $green . ">Green</option>
                <option value=\"2\"" . $red . ">Red</option>
                <option value=\"3\"" . $orange . ">Orange</option>
                <option value=\"4\"" . $unknown . ">Unknown</option>
                </select></td>";
            echo "<td><input type=\"text\" id=\"phone_" . $candidate['id'] . "\" name=\"phone_" . $candidate['id'] . "\" value=\"" . $candidate['phone'] . "\" /></td>";
            echo "<td><input type=\"text\" id=\"email_" . $candidate['id'] . "\" name=\"email_" . $candidate['id'] . "\" value=\"" . $candidate['email'] . "\" /></td>";
            echo "<td><input type=\"text\" id=\"comment_" . $candidate['id'] . "\" name=\"comment_" . $candidate['id'] . "\" value=\"" . $candidate['comment'] . "\" /></td>";
            echo "</tr>";

        endforeach;
        ?>
    </table>
    </div>
    <p><?php submit_button(); ?></p>
</form>
</div>