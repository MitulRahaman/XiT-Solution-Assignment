<?php

// Function to fetch HTML content from a URL using cURL
function fetchHTML($url) {
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $html = curl_exec($curl);
    curl_close($curl);

    return $html;
}

// Function to extract product details from HTML content
function extractProducts($html) {
    $products = [];

    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML($html);

    $xpath = new DOMXPath($dom);
    $query = "//div[contains(@class, 'product-grid')]/div[contains(@class, 'product')]"; // Example XPath, adjust as needed
    $nodes = $xpath->query($query);
    
    foreach ($nodes as $node) {
        echo "loop";
        $productNameNodeList = $node->getElementsByTagName('h4');
        $productPriceNodeList = $node->getElementsByTagName('span');

        if ($productNameNodeList->length > 0 && $productPriceNodeList->length > 0) {
            $productName = trim($productNameNodeList->item(0)->textContent);
            $productPrice = trim($productPriceNodeList->item(0)->textContent);

            
            $products[] = [
                'Name' => $productName,
                'Price' => $productPrice
                
            ];
        } else {
            error_log('Warning: Product details could not be extracted.');
        }
    }

    return $products;
}


// Function to save products to a CSV file
function saveToCSV($products, $filename) {
   
    $file = fopen($filename, 'w');
    fputcsv($file, array_keys($products[0]));
    foreach ($products as $product) {
        fputcsv($file, $product);
    }
    fclose($file);
}


$url = 'https://yourpetpa.com.au/';
$html = fetchHTML($url);
echo htmlspecialchars($html);

$products = extractProducts($html);
saveToCSV($products, 'products.csv');

echo "Product feed generated successfully!\n";