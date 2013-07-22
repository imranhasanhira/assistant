<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function getURL($accountID, $currentCategoryID, $currentSortOptionID, $curPageNum, $lim) {
    return site_url('financials/account/' . $accountID . '?category=' . $currentCategoryID . '&sort-by=' . $currentSortOptionID . '&page=' . $curPageNum . '&limit=' . $lim);
}

function getItem($url, $text, $attribute = '') {
    return '<li ' . $attribute . '><a href = "' . $url . '" title="Page ' . $text . '" >' . $text . '</a></li>';
}

function getTransactionPaginationString($accountID, $currentCategoryID, $currentSortOptionID, $totalTransactionCount, $currentPage, $pageSize, $paginationRight = '') {

    $str = '<div class = "pagination ' . $paginationRight . '"> <ul>';

    $halfCount = 2;
    $totalPage = ceil($totalTransactionCount / $pageSize);
    if ($totalPage == 1) {
        return '';
    }
    $startPage = max(0, min($currentPage - $halfCount, $totalPage - 2 * $halfCount - 1));
    $finalPage = min($totalPage - 1, $startPage + 2 * $halfCount);
//    echo $totalCount . ' : ' . $pageSize . ' : ' . $totalPage . ' : ' . $startPage . ' : ' . $currentPage . ' : ' . $finalPage . '<br/>';
    //leftest
    $str .= getItem(getURL($accountID, $currentCategoryID, $currentSortOptionID, 1, $pageSize), '&laquo;', ($currentPage <= $halfCount ) ? 'class="disabled"' : '');

    for ($i = $startPage; $i <= $finalPage; $i++) {
        $str .= getItem(getURL($accountID, $currentCategoryID, $currentSortOptionID, $i + 1, $pageSize), $i + 1, ($i == $currentPage) ? 'class="active"' : '');
    }

    //rightest
    $str .= getItem(getURL($accountID, $currentCategoryID, $currentSortOptionID, $totalPage, $pageSize), '&raquo;', ($currentPage >= $totalPage - $halfCount - 1) ? 'class="disabled"' : '');

    $str .='</ul> </div>';
    return $str;
}

$paginationOptions = array(10, 20, 50, $pageSize, 100, 500);
sort($paginationOptions, SORT_NUMERIC);
$paginationOptions = array_unique($paginationOptions);
?>

<h2> Account : <?php echo $account['name']; ?></h2>

<div class="row">
    <div class="span12">
        <form method="post" class="form-inline pull-left" action="<?php echo site_url('financials/transaction/add'); ?>">
            <button class="btn btn-primary">Deposite</button>
            <input type="hidden" name="mode" value="add"/>
            <input type="hidden" name="type" value="deposite"/>
        </form>

        <form method="post" class="form-inline pull-left" action="<?php echo site_url('financials/transaction/add'); ?>">
            <button class="btn btn-primary">Spend</button>
            <input type="hidden" name="mode" value="add"/>
            <input type="hidden" name="type" value="spend"/>
        </form>

        <form method="post" class="form-inline pull-left" action="<?php echo site_url('financials/transaction/add'); ?>">
            <button class="btn btn-primary">Transfer</button>
            <input type="hidden" name="mode" value="add"/>
            <input type="hidden" name="type" value="transfer"/>
        </form>
    </div>
</div>
<hr />


<div class="row">
    <div class="span8 pull-left">

        <form class="form-inline" action="<?php echo site_url('financials/account/' . $accountID); ?>">
            <select name="category" title="Category of the transaction">
                <option value="-1">All</option>
                <?php
                foreach ($categories as $categoryID => $categoryName) {
                    ?>
                    <option value="<?php echo $categoryID; ?>" <?php if ($currentCategoryID == $categoryID) echo 'selected="selected"'; ?>"> <?php echo $categoryName; ?></option>
                    <?php
                }
                ?>
            </select>
            <select name="sort-by" title="Sorting criteria of the transactions">
                <?php
                foreach ($sortOptions as $sortOptionID => $sortOptionName) {
                    echo '<option value="' . $sortOptionID . '" ' . ($sortOptionID == $currentSortOptionID ? 'selected="selected"' : '') . '>' . $sortOptionName . '</option>';
                }
                ?>
            </select>
            <select class="input-mini" name="limit" title="Number of transactions shown on a page">
                <?php
                foreach ($paginationOptions as $paginationOption) {
                    echo '<option value="' . $paginationOption . '" ' . ($paginationOption == $pageSize ? 'selected="selected"' : '') . '>' . $paginationOption . '</option>';
                }
                ?>
            </select>
            <button class="btn"> Show</button> 

        </form>
    </div>
    <div class="span4 pull-right pagination-topalign">
        <?php echo getTransactionPaginationString($accountID, $currentCategoryID, $currentSortOptionID, $totalTransactionCount, $currentPage, $pageSize, 'pagination-right'); ?>
    </div>
</div>
<div class="row">
    <div class="span12">


        <?php
        if ($transactions != NULL) {
            ?>
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Category</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
//                    $count = 0;
                    foreach ($transactions as $transactionID => $transaction) {
//                        $count++;
//                        if($count>$pageSize)break;
                        ?>
                        <tr>
                            <td><?php echo $transaction['date'] ?></td>
                            <td><?php echo $transaction['category'] ?></td>
                            <td><?php echo $transaction['title'] ?></td>
                            <td><?php echo $transaction['description'] ?></td>
                            <td><?php echo $transaction['amount'] ?></td>
                            <td class="transaction-actions">
                                <a href="<?php echo site_url('financials/transaction/edit/' . $transactionID); ?>"><i class="icon-edit"></i></a>
                                <a href="<?php echo site_url('financials/transaction/trash/' . $transactionID); ?>"><i class="icon-trash"></i></a>
                                <a class="disabled"><i class="icon-info-sign" title="<?php echo $transaction['info']; ?>"></i></a>
                            </td>
                            <td><?php echo $transactionID; ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <?php
        }
        ?>
    </div>
</div>

<div class="row">
    <div class="span12 pagination-topalign pagination-centered">
        <?php echo getTransactionPaginationString($accountID, $currentCategoryID, $currentSortOptionID, $totalTransactionCount, $currentPage, $pageSize); ?>
    </div>
</div>




<hr />
<div class="">
    <form method="post" class="form-inline" action="<?php echo site_url('financials/account/' . $accountID); ?>">
        <input type="text" name="name" value="<?php echo $account['name']; ?>" placeholder="Account name"/>
        <button class="btn btn-primary"> Update account name</button>
        <input type="hidden" name="mode" value="editaccount"/>
    </form>
</div>
