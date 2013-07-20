<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>


<div class="row">
    <div class="span8 offset2 pagination-centered">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr> 
                    <th>Account ID</th>
                    <th>Name</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($accounts != NULL) {
                    foreach ($accounts as $accountID => $account) {
                        ?>
                        <tr>
                            <td><?php echo $accountID; ?></td>
                            <td> <a class="btn" href="<?php echo site_url('financials/account/' . $accountID); ?>"> <?php echo $account['name']; ?></a> </td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<hr />

<div class="row">
    <div class="span6 pagination-centered">
        <?php
        if (isset($borrowedMoney) && $borrowedMoney != 0) {
            echo 'You have borrowed ' . $borrowedMoney . ' amount of money still unpaid';
        }
        ?>
        <a class="btn btn-danger" href="<?php echo site_url('financials/transaction/newtransation?cat=borrow'); ?>">Borrow Money</a>
        <a class="btn btn-primary" href="<?php echo site_url('financials/transaction/newtransation?cat=pay-back'); ?>">Pay-back Money</a>
    </div>



    <div class="span6 pagination-centered">
        <?php
        if (isset($loanedMoney) && $loanedMoney != 0) {
            echo 'You have loaned ' . $loanedMoney . ' amount of money to other person';
        }
        ?>
        <a class="btn btn-danger" href="<?php echo site_url('financials/transaction/newtransation?cat=borrow'); ?>">Loan Money</a>
        <a class="btn btn-primary" href="<?php echo site_url('financials/transaction/newtransation?cat=pay-back'); ?>">Fill-back Loan</a>
    </div>
</div>


<hr />
<div class="">
    <form method="post" class="form-inline" action="<?php echo site_url('financials/'); ?>">
        <input type="text" name="name" placeholder="Account name"/>
        <button class="btn"> Create a financial account </button>
        <input type="hidden" name="mode" value="newaccount"/>
    </form>
</div>


