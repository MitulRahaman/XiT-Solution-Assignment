<?php

$userAgents = [
    'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.71 Safari/537.36',
    'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.110 Safari/537.36',
    'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.71 Safari/537.36',
];
// Function to fetch HTML content from a URL using cURL
function fetchHTML($url, $userAgents) {
    $randomUserAgent = $userAgents[array_rand($userAgents)];

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_USERAGENT, $randomUserAgent);
    $html = curl_exec($curl);
    curl_close($curl);

    return $html;
}

// Function to extract product details from HTML content
function extractProducts($html) {
    $productTitle = [];
    $productPrice = [];
    $productURL = [];
    $imageURL = [];

    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $loaded = $dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    
    if (!$loaded) {
        $errors = libxml_get_errors();
        foreach ($errors as $error) {
            echo "DOMDocument error: {$error->message}\n";
        }
        libxml_clear_errors();
    } else {
        $xpath = new DOMXPath($dom);

        $productQuery = "//div[@class='product-block__title']/a";
        $priceQuery = "//div[@class='product-price']/span[1]";
        $productURLQuery = "//div[@class='product-block__image-container']/div/a/@href";
        $imageURLQuery = "//div[@class='product-block__image-container']/div/a/div/div/div/noscript/img/@src";

        $titles = $xpath->query($productQuery);
        $prices = $xpath->query($priceQuery);
        $productURLs = $xpath->query($productURLQuery);
        $imageURLs = $xpath->query($imageURLQuery);
        
        foreach ($titles as $title) {
            $titleList = $title->nodeValue;
            if ($titleList) {
                $productTitle[] = [
                    'Title' => $titleList,
                ];
            } else {
                error_log('Warning: Product name could not be extracted.');
            }
        }
        foreach ($prices as $price) {
            $priceList = $price->nodeValue;
            if($priceList) {
                $productPrice[] = [
                    'Price' => $priceList,
                ];
            } else {
                error_log('Warning: Product price could not be extracted.');
            }
        }
        foreach ($productURLs as $url) {
            $productURLList = $url->nodeValue;
            $productURLList = "https://yourpetpa.com.au" . $productURLList;
            if ($productURLList) {
                $productURL[] = [
                    'ProductURL' => $productURLList,
                ];
            } else {
                error_log('Warning: ProductURL could not be extracted.');
            }
        }
        foreach ($imageURLs as $url) {
            $imageURLList = $url->nodeValue;
            $imageURLList = "https:" . $imageURLList;
            if ($imageURLList) {
                $imageURL[] = [
                    'ImageURL' => $imageURLList,
                ];
            } else {
                error_log('Warning: ImageURL could not be extracted.');
            }
        }
        return [$productTitle, $productPrice, $productURL, $imageURL];
    }
}

// Function to save products to a CSV file
function saveToCSV($products, $filename) {
    $file = fopen($filename, 'w');
    fputcsv($file, ['Title', 'Price', 'ProductURL', 'ImageURL',]);
    $rows = array_map(null, $products[0], $products[1], $products[2], $products[3],);

    foreach ($rows as $row) {
        $title = isset($row[0]['Title']) ? $row[0]['Title'] : '';
        $price = isset($row[1]['Price']) ? $row[1]['Price'] : '';
        $productURL = isset($row[2]['ProductURL']) ? $row[2]['ProductURL'] : '';
        $imageURL = isset($row[3]['ImageURL']) ? $row[3]['ImageURL'] : '';
        
        fputcsv($file, [$title, $price, $productURL, $imageURL]);
    }
    fclose($file);
}

$url = 'https://yourpetpa.com.au/';
$html = fetchHTML($url, $userAgents);

$products = extractProducts($html);
saveToCSV($products, 'products.csv');

echo "Product feed generated successfully!\n";