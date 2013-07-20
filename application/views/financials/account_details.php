<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function getItem($url, $text, $attribute = '') {
    return '<li ' . $attribute . '><a href = "' . $url . '">' . $text . '</a></li>';
}

function getTransactionPaginationString($url, $totalCount, $currentPage, $pageSize) {

    $str = '<div class = "pagination"> <ul>';

    $halfCount = 2;
    $startPage = max(0, $currentPage - $halfCount);
    $totalPage = ceil($totalCount / $pageSize);
    $finalPage = min($totalPage, $currentPage + 2 * $halfCount + 1);

    //echo $totalCount . ' : ' . $pageSize . ' : ' . $totalPage . ' : ' . $startPage . ' : ' . $currentPage . ' : ' . $finalPage . '<br/>';
    //leftest
    $str .= getItem($url, '&laquo;', ($currentPage <= $halfCount ) ? 'class="disabled"' : '');

    for ($i = $startPage; $i <= $finalPage; $i++) {
        $str .= getItem($url, $i, ($i == $currentPage) ? 'class="active"' : '');
    }

    //rightest
    $str .= getItem($url, '&raquo;', ($currentPage >= $totalPage - $halfCount - 1) ? 'class="disabled"' : '');

    $str .='</ul> </div>';
    return $str;
}
?>

<div class="row">
    <div class="span12">
        <form method="post" class="form-inline pull-left" action="<?php echo site_url('financials/transaction/add'); ?>">
            <button class="btn">Deposite</button>
            <input type="hidden" name="mode" value="add"/>
            <input type="hidden" name="type" value="deposite"/>
        </form>

        <form method="post" class="form-inline pull-left" action="<?php echo site_url('financials/transaction/add'); ?>">
            <button class="btn">Spend</button>
            <input type="hidden" name="mode" value="add"/>
            <input type="hidden" name="type" value="spend"/>
        </form>

        <form method="post" class="form-inline pull-left" action="<?php echo site_url('financials/transaction/add'); ?>">
            <button class="btn">Transfer</button>
            <input type="hidden" name="mode" value="add"/>
            <input type="hidden" name="type" value="transfer"/>
        </form>
    </div>
</div>
<hr />


<div class="row">
    <div class="span12">

        <form class="">
            <select name="categories" multiple="multiple">
                <option value="-1">All</option>
                <?php
                foreach ($categories as $categoryID => $categoryName) {
                    ?>
                    <option value="<?php echo $categoryID; ?>" selected=""> <?php echo $categoryName; ?></option>
                    <?php
                }
                ?>
            </select>

        </form>

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
                    foreach ($transactions as $transactionID => $transaction) {
                        ?>
                        <tr>
                            <td><?php echo $transaction['date'] ?></td>
                            <td><?php echo $transaction['category'] ?></td>
                            <td><?php echo $transaction['title'] ?></td>
                            <td><?php echo $transaction['description'] ?></td>
                            <td><?php echo $transaction['amount'] ?></td>
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
    <div class="span6 pull-right">
        <?php echo getTransactionPaginationString(site_url(), $totalTransactionCount, $currentPage, $limit) ?>
    </div>
</div>




<hr />
<div class="">
    <form method="post" class="form-inline" action="<?php echo site_url('financials/'); ?>">
        <input type="text" name="name" value="<?php echo $account['name']; ?>" placeholder="Account name"/>
        <button class="btn"> Update account name</button>
        <input type="hidden" name="mode" value="editaccount"/>
    </form>
</div>
