<?php
if (PHP_SAPI != 'cli') {
    exit;
}
if (version_compare(phpversion(), '5.2.0', '<')) {
    echo 'It looks like you have an invalid PHP version. Magento supports PHP 5.2.0 or newer';
    exit;
}

$magentoRootDir = getcwd();
$bootstrapFilename = $magentoRootDir . '/app/bootstrap.php';
$mageFilename = $magentoRootDir . '/app/Mage.php';

if (!file_exists($bootstrapFilename)) {
    echo 'Bootstrap file not found';
    exit;
}
if (!file_exists($mageFilename)) {
    echo 'Mage file not found';
    exit;
}
require $bootstrapFilename;
require $mageFilename;
require 'lorem-phpsum.php';
try{

    Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
    $num = $argv[1];
    $categoryId = $argv[2];
    $attributeSetId =$argv[3];

    addProduct($num, $categoryId, $attributeSetId);

}catch (Exception $e){
    throw $e;
}


function addProduct ($num, $categoryId, $attributeSetId){
    try{
        for($i=0; $i<$num; $i++){
            $product = Mage::getModel('catalog/product');
            $product->setWebsiteIds(array(1));
            $product->setAttributeSetId($attributeSetId);
            $product->setTypeId('simple');
            $product->setCreatedAt(strtotime('now'));
            $product->setSku('bc-'.uniqid());
            $product->setName(phpsum(5, 20));
            $product->setWeight(rand(1111, 9999));
            $product->setStatus(1);
            $tax = array(0, 2, 4);
            $product->setTaxClassId($tax[rand(0,2)]);
            $product->setVisibility(4);
            $product->setCountryOfManufacture(4);
            $product->setPrice(rand(100,10000));
            $product->setCost(rand(500,1000));
            $product->setMetaTitle(phpsum(10, 100));
            $product->setMetaKeyword(phpsum(100, 300));
            $product->setMetaDescription(phpsum(100, 200));
            $product->setDescription(phpsum(400, null, 2));
            $product->setShortDescription(phpsum(100, 200));
            $product->setStockData(array(
                'use_config_manage_stock' => 0, //'Use config settings' checkbox
                'manage_stock'=>1, //manage stock
                'min_sale_qty'=>1, //Minimum Qty Allowed in Shopping Cart
                'max_sale_qty'=>2, //Maximum Qty Allowed in Shopping Cart
                'is_in_stock' => 1, //Stock Availability
                'qty' => rand(10, 1000) //qty
            ));
            $product->setCategoryIds(array($categoryId));
            $product->save();
        }
    }catch (Exception $e){
        throw $e;
    }
}