<?php
function ml($varname,$text)
{
    $resText = \App\Models\ML\DanaflexMl::ml($varname,$text);
    return $resText;
}
?>