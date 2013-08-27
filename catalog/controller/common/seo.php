<?php

require_once('config.php');
require_once(DIR_SYSTEM . 'startup.php');

// Registry
$registry = new Registry();

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

// Database
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$registry->set('db', $db);

function seoURL($str) {
    $str = preg_replace('/[^a-zA-Z0-9]+/', '-', $str);
    $str = trim($str, '-');
    $str = strtolower($str);
            
    return $str;
}

?>
<html>
    <head>
        <title>Create SEO-links</title>
    </head>
    <style type="text/css">
        body {
            font-family: "Arial";
            font-size: 12px;
        }
    </style>
    <body>
        <center>
        <h2>Script by Kartoffelz</h2>
<?php

if(isset($_GET['products'])) {
$products   = $db->query("SELECT * FROM " . DB_PREFIX . "product");
$products   = $products->rows;

foreach($products as $product) {
    
    $url = $db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product['product_id'] . "'");
    $url = $url->rows;
    
    if(!empty($url)) {
        echo 'Match found for ' . $product['product_id'] . '). No action taken.<br>---------------<br>';
    } else {
        echo 'URL needed for ' . $product['product_id'] . '. Fetching information...<br>';
        $info = $db->query("SELECT * FROM " . DB_PREFIX . "product_description WHERE product_id = '" . $product['product_id'] . "' LIMIT 1");
        $info = $info->rows;
        
        foreach($info as $data) {        
            echo 'Name: ' . $data['name'] . ' | Converting to: ' . seoURL($data['name']);
            $data['name'] = seoURL($data['name']);
            sleep(1);
            $db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product['product_id'] . "', keyword = '" . $db->escape($data['name']) . "'");
            echo '<br>Inserted!<br>---------------<br>';
        }
    }
}

echo '<br><br>All done! <a href="seo-links.php">Back</a>';
}
elseif(isset($_GET['categories'])) {
$categories   = $db->query("SELECT * FROM " . DB_PREFIX . "category");
$categories   = $categories->rows;

foreach($categories as $category) {
    
    $url = $db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=" . (int)$category['category_id'] . "'");
    $url = $url->rows;
    
    if(!empty($url)) {
        echo 'Match found for ' . $category['category_id'] . '). No action taken.<br>---------------<br>';
    } else {
        echo 'URL needed for ' . $category['category_id'] . '. Fetching information...<br>';
        $info = $db->query("SELECT * FROM " . DB_PREFIX . "category_description WHERE category_id = '" . $category['category_id'] . "' LIMIT 1");
        $info = $info->rows;
        
        foreach($info as $data) {        
            echo 'Name: ' . $data['name'] . ' | Converting to: ' . seoURL($data['name']);
            $data['name'] = seoURL($data['name']);
            sleep(1);
            $db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'category_id=" . (int)$category['category_id'] . "', keyword = '" . $db->escape($data['name']) . "'");
            echo '<br>Inserted!<br>---------------<br>';
        }
    }
}

echo '<br><br>All done! <a href="seo-links.php">Back</a>';
} elseif(isset($_GET['information'])) {
$informationp    = $db->query("SELECT * FROM " . DB_PREFIX . "information");
$informationp    = $informationp->rows;

foreach($informationp as $information) {
    
    $url = $db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE query = 'information_id=" . (int)$information['information_id'] . "'");
    $url = $url->rows;
    
    if(!empty($url)) {
        echo 'Match found for ' . $information['information_id'] . '. No action taken.<br>---------------<br>';
    } else {
        echo 'URL needed for ' . $information['information_id'] . '. Fetching information...<br>';
        $info = $db->query("SELECT * FROM " . DB_PREFIX . "information_description WHERE information_id = '" . $information['information_id'] . "' LIMIT 1");
        $info = $info->rows;
        
        foreach($info as $data) {        
            echo 'Name: ' . $data['title'] . ' | Converting to: ' . seoURL($data['title']);
            $data['title'] = seoURL($data['title']);
            sleep(1);
            $db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'information_id=" . (int)$information['information_id'] . "', keyword = '" . $db->escape($data['title']) . "'");
            echo '<br>Inserted!<br>---------------<br>';
        }
    }
}
echo '<br><br>All done! <a href="seo-links.php">Back</a>';
    
}
else {
    echo '<p><a href="?products">Products</a> - Create product-URLs</p>';
    echo '<p><a href="?categories">Categories</a> - Create category-URLs</p>';
    echo '<p><a href="?information">Information</a> - Create information-URLs</p>';
}

?>
</center>
</body>
</html>