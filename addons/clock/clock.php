<?php
/////////////////////////////////////////////////////////////////////////
// xBtit - Bittorrent tracker/frontend
//
// Copyright (C) 2004 - 2007  Btiteam
//
//    This file is part of xBtit.
//
//    xBtit is free software: you can redistribute it and/or modify
//    it under the terms of the GNU General Public License as published by
//    the Free Software Foundation, either version 3 of the License, or
//    (at your option) any later version.
//
//    xBtit is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU General Public License for more details.
//
//    You should have received a copy of the GNU General Public License
//    along with xBtit.  If not, see <http://www.gnu.org/licenses/>.
//
/////////////////////////////////////////////////////////////////////////

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