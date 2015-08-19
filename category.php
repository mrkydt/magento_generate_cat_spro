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
    $parentId = $argv[2];

    addCategory($num, $parentId);

}catch (Exception $e){
    throw $e;
}


function addCategory ($num, $parentId){
    try{
        for($i=0; $i<$num; $i++){

            $parentCategory = Mage::getModel('catalog/category')->load($parentId);
            $model = Mage::getModel('catalog/category');
            $model->setName(phpsum(2, 8));
            $model->setIsActive(1);
            $model->setDisplayMode('PRODUCTS_AND_PAGE');
            $model->setIsAnchor(1);
            $model->setStoreId(\Mage::app()->getStore()->getId());
            $model->setPath($parentCategory->getPath());
            $model->save();
        }
    }catch (Exception $e){
        throw $e;
    }
}