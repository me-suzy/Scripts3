<?

#
# $Id: product.php,v 1.49 2002/11/29 14:46:02 vod Exp $
#

require "./auth.php";

#
# Put all product info into $product array
#

$product_info = func_select_product($productid, $user_account['membership']);
if (empty($cat)) {
        $cat = $product_info["categoryid"];
}

if($active_modules["Detailed_Product_Images"])
        include "../modules/Detailed_Product_Images/product_images.php";

if($active_modules["Product_Options"])
        include "../modules/Product_Options/customer_options.php";

if($active_modules["Upselling_Products"])
        include "../modules/Upselling_Products/related_products.php";

if($active_modules["Advanced_Statistics"])
    include "../modules/Advanced_Statistics/prod_viewed.php";

require "../include/categories.php";

if(($login)&& ($product_info != "") && ($active_modules["stock_notify"])) {
// ie they are looged in and have selected a product
// echo "logged and ready to roll";
$userinfo = func_userinfo($login,$login_type);
// echo $userinfo['email'];
// echo "INSERT INTO `xcart_notify` ( `email` , `productid` ) VALUES ('" . $userinfo['email'] . "', '$productid');";

// deletes their email if they are already watching this product
db_query("delete from `xcart_notify` where email='" . $userinfo['email'] . "' and productid = $productid");
// add them to the notify table
db_query("INSERT INTO `xcart_notify` ( `email` , `productid` ) VALUES ('" . $userinfo['email'] . "', '$productid')");
}
else {

        if (($guestemail) && ($product_info != "") && ($active_modules["stock_notify"])){
        // deletes their email if they are already watching this product
        db_query("delete from `xcart_notify` where email='" . $guestemail . "' and productid = $productid");
        // add them to the notify table
        db_query("INSERT INTO `xcart_notify` ( `email` , `productid` ) VALUES ('" . $guestemail . "', '$productid')");
        $smarty->assign("guestlogged","true");
        }
        else{
        if ($guest!="true"){
                header("Location: error_message.php?access_denied");
                exit();
                }
        }
}
$smarty->assign("productid",$productid);
$smarty->assign("userinfo",$userinfo);
$smarty->assign("product",$product_info);
$smarty->assign("main","notify");
$smarty->display("customer/home.tpl");
?>