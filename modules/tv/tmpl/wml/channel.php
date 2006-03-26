<?php
/**
 * Channel detail
 *
 * @url         $URL$
 * @date        $Date$
 * @version     $Revision$
 * @author      $Author$
 * @license     GPL
 *
 * @package     MythWeb
 *
/**/

// Print the main page header
    $page_title = 'MythWeb - Channel Detail';
    require_once 'modules/_shared/tmpl/'.tmpl.'/header.php';

// Print out some header info about this channel and time
?>
<p><br />
<?php
        if (! isset($_GET['jump'])) {
            echo '<a href="'.root.'tv/channel?chanid='.$_GET['chanid'].'&amp;jump=y">'.t('Jump to')." ".t('Date').'</a><br />';
        }
        echo "<b>Channel ".$this_channel->channum.' '.$this_channel->callsign."</b></p>";
        echo "<p>";

        if (isset($_SESSION['list_time'])) {
            echo strftime(t('generic_date'), $_SESSION['list_time']);
        }
?>
</p>
<p>
<?php
    $carddata="";
    // Find out how many days into the future we should bother checking
    $result = mysql_query('SELECT TO_DAYS(max(starttime)) - TO_DAYS(NOW()) FROM program')
        or trigger_error('SQL Error: '.mysql_error(), FATAL);
    list($max_days) = mysql_fetch_row($result);
    mysql_free_result($result);
    // Print out the list
    for ($i=-1;$i<=$max_days;$i++) {
        $time = mktime(0,0,0, date('m'), date('d') + $i, date('Y'));
        $date = date("Ymd", $time);
        $carddata.="<a href=\"".root."tv/channel?chanid=".$_GET['chanid']."&amp;time=".$time."\">".strftime(t('generic_date'), $time)."</a><br />\n";
    }

// Print the shows for today
    if (is_array($this_channel->programs)) {

        if (isset($_GET['jump'])) {
            echo '<br />';
            echo $carddata;
            echo '</p></card>';
        }
        else {
?>
<do type="accept">
<go href="<?php echo root ?>tv/detail" method="get">
<postfield name="chanid" value="<?php echo $_GET['chanid'] ?>"/>
<postfield name="starttime" value="$(starttime)"/>
</go>
</do>
<select name="starttime">
<?php

        // Display the results
            $row = 0;

            foreach ($this_channel->programs as $show) {
                // Print the content
                echo '<option value="'.$show->starttime.'">'.htmlspecialchars($show->title)." (".strftime(t('generic_time'), $show->starttime).")</option>\n";
            }
            echo '</select></p></card>';
        }
    }

// Print the main page footer
    require_once 'modules/_shared/tmpl/'.tmpl.'/footer.php';
