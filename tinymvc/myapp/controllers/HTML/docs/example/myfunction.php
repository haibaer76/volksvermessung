<?php
// $Id: myfunction.php,v 1.5 2006/09/24 16:58:32 thesee Exp $
class Test
{
    function getTestName($dbh, $id) {
        $livesearch = array(
                                1 => 'Orange',
                                2 => 'Apple',
                                3 => 'Pear',
                                4 => 'Banana',
                                5 => 'Blueberry'
                                );
        if ($id > 0) {
            return $livesearch[$id];
        }
    }

    function getTestName2($dbh, $id) {
        $livesearch = array(
                                1 => 'Orange-2',
                                2 => 'Apple-2',
                                3 => 'Pear-2',
                                4 => 'Banana-2',
                                5 => 'Blueberry-2'
                                );
        if ($id > 0) {
            return $livesearch[$id];
        }
    }
}
?>