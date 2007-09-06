<?php
function clock_display($clocktype)
{
if ($clocktype == true) {
    $clock = 'anaclock.swf';
    $cheight = '130';
    $cwidth = '130';

} else {
    $clock = 'digiclock.swf';
        $cheight = '50';
        $cwidth = '100';
}
?>
<div align="center">
<object type="application/x-shockwave-flash" data="./addons/clock/<?php echo $clock; ?>" width="<?php echo $cwidth; ?>" height="<?php echo $cheight; ?>">
<param name="movie" value="./addons/clock/<?php echo $clock; ?>" />
<param name="quality" value="high" />
<param name="bgcolor" value="#FFFFFF" />
<param name="wmode" value="transparent" />
<param name="menu" value="false" />
</object>
</div>
<?php
}
?>