<?php
ob_start();
include_once('../config.php');
include_once('../database/SQLiteConnection.php');
include_once('../Model/Product.php');

if (isset($_POST['query'])) {
    if (isset($_POST['page'])) {
        $query = $_POST['query'];
        $page = 1;
        $resultsPerPage = 10;

        if ($_POST['page'] > 1) {
            $page = $_POST['page'];
            $pageResult = ($page - 1) * $resultsPerPage;
        } else {
            $pageResult = 0;
        }

        // Sqlite conn
        $databasePath = '../database/' . DATABASE_NAME . '.sqlite';
        $pdo = (new SQLiteConnection())->connect($databasePath);
        $product = new Product($pdo);
        // Get number of pages from database
        $countData = $product->countSearch($_POST['query']);
        $numberOfPage = ceil($countData / $resultsPerPage);
        $products = $product->getPaginatedSearch($pageResult, $resultsPerPage, $_POST['query']);


        $productHtml = '';

        if ($products) {
            foreach ($products as $product) {
                $price = number_format($product['price'],2,",",".");
                $productHtml .=  "<a href='Views/Product.php?id={$product['id']}' id='{$product['id']}'>
                <div class='product-card'>
                    <div class='product-image'>
                        <img src='{$product['image']}' alt='{$product['name']}' />
                    </div>
                    <div class='product-info'>
                        <div class='title text' id='title-item'>{$product['name']}</div>
                        <div class='sub-info'>
                            <div class='price'>Rp{$price}</div>
                            <div class='rating'>
                                Sold {$product['sold']}
                            </div>
                        </div>
                    </div>
                </div>
            </a>";
            }
        } else {
            $productHtml .= "<h1>No data available</h1>";
        }

        // Pagination link
        $paginationHtml = '';

        $previousLink = '';

        $nextLink = '';

        $page_link = '';

        if ($numberOfPage > 4) {
            if ($page < 5) {
                for ($count = 1; $count <= 5; $count++) {
                    $page_array[] = $count;
                }
                $page_array[] = '...';
                $page_array[] = $numberOfPage;
            } else {
                $end_limit = $numberOfPage - 3;
                if ($page > $end_limit) {
                    $page_array[] = 1;

                    $page_array[] = '...';
                    for ($count = $end_limit; $count <= $numberOfPage; $count++) {
                        $page_array[] = $count;
                    }
                } else {
                    $page_array[] = 1;
                    $page_array[] = '...';
                    for ($count = $page - 1; $count <= $page + 1; $count++) {
                        $page_array[] = $count;
                    }

                    $page_array[] = '...';

                    $page_array[] = $numberOfPage;
                }
            }
        } else {
            for ($count = 1; $count <= $numberOfPage; $count++) {
                $page_array[] = $count;
            }
        }

        if (isset($page_array)) {
            for ($count = 0; $count < count($page_array); $count++) {
                if ($page == $page_array[$count]) {
                    $page_link .= "<a href='#' class='active'>{$page_array[$count]}</a>";

                    $previous_id = $page_array[$count] - 1;

                    if ($previous_id > 0) {
                        $previousLink =  "<a href='javascript:load_data(`{$query}`,{$previous_id})'>&laquo;</a>";
                    } else {
                        $previousLink =  "<a class='disabled' href=''>&laquo;</a>";
                    }

                    $next_id = $page_array[$count] + 1;
                    if ($next_id > $numberOfPage) {
                        $next_link = "<a class='disabled' href='#'>&raquo;</a>";
                    } else {
                        $next_link =   "<a href='javascript:load_data(`{$query}`,{$next_id})'>&raquo;</a>";
                    }
                } else {
                    if ($page_array[$count] == '...') {
                        $page_link .= "<a class='disabled' href='#'>...</a>";
                    } else {
                        $page_link .= "<a href='javascript:load_data(`{$query}`,{$page_array[$count]})'>{$page_array[$count]}</a>";
                    }
                }
            }

            $paginationHtml .= $previousLink . $page_link . $next_link;
        }


        $result = [
            'products' => $productHtml,
            'pagination' => $paginationHtml,
        ];

        echo json_encode($result);
    }
}
