<?php
//begin
    checksession(); //check if we are called by a valid session
//routing
    $id2=$_GET['id2'];
//default route
    if ($id2=='') $id2=0;
//submenu
    ?>
    <table border="0" cellpadding="0" cellspacing="2">
    <tr>
    <?php if (checkrights("Administrator,ViewDatabase")) { ?>
    <td>
    <form method="get" action="">
    <input type="hidden" name="id" value="10">
    <input type="hidden" name="id2" value="0">
    <input type="submit" value="Item Database">
    </form>
    </td>
    <?php } ?>
    <?php if (checkrights("Administrator,ViewOreValues")) { ?>
    <td>
    <form method="get" action="">
    <input type="hidden" name="id" value="10" />
    <input type="hidden" name="id2" value="7" />
    <input type="submit" value="Ore Values" />
    </form>
    </td>
    <?php } ?>
    </tr></table>
    <?php
//end submenu

//controller
    switch ($id2) {
	    case 0:
		include("a0.php");  //Market tree traverse
		break;
	    case 1:
		include("a1.php");  //Item view
		break;
		case 2:
		include("a2.php");  //Search view
		break;
		case 3:
		include("a3.php");  //Toggle PricesFlag (cfgmarket)
		break;
		case 4:
		include("a4.php");  //Toggle BuyingFlag (cfgbuying)
		break;
		case 5:
		include("a5.php");  //Save ME&PE (cfgbpo)
		break;
		case 6:
		include("a6.php");  //Save Stock (cfgstock)
		break;
                case 7:
		include("a7.php");  //Ore Values Table
		break;
	    }
?>