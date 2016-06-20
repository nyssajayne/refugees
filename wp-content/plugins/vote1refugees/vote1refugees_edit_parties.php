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

    <form action="<?php echo plugin_dir_url( __FILE__ ); ?>vote1refugees_parties_process.php" method="post" id="politicians">

        <?php $parties_array = vote1refugees_fetch_parties(); ?>

        <table id="reps">

            <tr>
                <td></td>
                <td></td>
                <td><strong>Any comments?</strong></td>
                <td><strong>Reference</strong></td>
            </tr>
        
        <?php foreach($parties_array as $party):
            echo "<tr>";
            echo "<td>" . $party['name'] . "</td>";
            echo "<td><select name=\"flag[" . $party['id'] . "]\" id=\"" . $party['id'] . "\">";

            $green = null;
            $red = null;
            $no_stance = null;
            $unclear = null;

            switch ($party['flag']) {
                case '1':
                    $green = 'selected';
                    break;
                case '2':
                    $red = 'selected';
                    break;
                case '3':
                    $no_stance = 'selected';
                    break;
                case '4':
                    $unclear = 'selected';
                    break;
                    
                default:
                    $unclear = 'selected';
                    break;
            }

            echo "<option value=\"1\"" . $green . ">Green</option>
                <option value=\"2\"" . $red . ">Red</option>
                <option value=\"3\"" . $no_stance . ">No Stance</option>
                <option value=\"4\"" . $unclear . ">Unclear</option>
                </select></td>";
            echo "<td><input type=\"text\" id=\"comment_" . $party['id'] . "\" name=\"comment_" . $party['id'] . "\" value=\"" . stripslashes($party['comment']) . "\" /></td>";
            echo "<td><input type=\"text\" id=\"reference_" . $party['id'] . "\" name=\"reference_" . $party['id'] . "\" value=\"" . $party['reference'] . "\" /></td>";
            echo "</tr>";

        endforeach;
        ?>
    </table>
    <p><?php submit_button(); ?></p>
</form>
</div>