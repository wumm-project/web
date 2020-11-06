<?php
/**
 * User: Hans-Gert Gräbe
 * Last Update: 2020-04-18

 * Test of Cavallucci's iframe

 */

require_once 'helper.php';
require_once 'layout.php';

function test() {
    $src="rdf/tt-texts.rdf";
    $out='
<iframe src="https://inventivedesign.unistra.fr/wp-admin/admin-ajax.php?action=h5p_embed&id=12" width="759" height="260" frameborder="0" allowfullscreen="allowfullscreen"></iframe><script src="https://inventivedesign.unistra.fr/wp-content/plugins/h5p/h5p-php-library/js/h5p-resizer.js" charset="UTF-8"></script>';
    return htmlEnv($out);
}

echo showpage(test());

?>

