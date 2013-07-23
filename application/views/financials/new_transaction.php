<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function itemStart($name = NULL, $isRequired = FALSE) {
    echo '<div class="control-group">';
    if ($name != NULL) { {
            echo '<label class="control-label" for="' . strtolower($name) . '">' . $name . ' ' . ($isRequired ? '*' : '') . '</label>';
        }
    }
    echo '<div class="controls">';
}

function itemEnd() {
    echo '
        </div>
        </div>
        ';
}

var_dump($_POST);
?>


<div class="row transaction-div">
    <?php echo form_open('financials/transaction/' . $type, array('class' => 'form-horizontal')); ?>

    <?php itemStart('Title', TRUE); ?>
    <input type="text" name="title" placeholder="Title" value="<?php echo set_value('title'); ?>">
    <?php echo form_error('title'); ?>
    <?php itemEnd(); ?>

    <?php itemStart('Description'); ?>
    <textarea rows="2" name="description" placeholder="Description" value="<?php echo set_value('description'); ?>"></textarea>
    <?php echo form_error('description'); ?>
    <?php itemEnd(); ?>

    <?php
    if ($type == 'transfer') {
        itemStart('Account to transfer', TRUE);
        ?>
        <select name="secondary-account-id">
            <option value="0">Select target account</option>
            <?php
            foreach (array_diff_key($accounts, array($account['id'] => '')) as $accID => $acc) {
                ?>
                <option value="<?php echo $accID; ?>"   <?php echo set_select('secondary-account-id', $accID); ?>  ><?php echo $acc['name']; ?></option>
                <?php
            }
            ?>
        </select>
        <?php echo form_error('secondary-account-id'); ?>
        <?php
        itemEnd();
    }
    ?>

    <?php
    if ($type != 'transfer') {
        itemStart('Category', TRUE);
        ?>
        <select name="category">
            <option value="0">Select a category</option>
            <?php
            foreach ($categories as $categoryID => $categoryName) {
                ?>
                <option value="<?php echo $categoryID; ?>"   <?php echo set_select('category', $categoryID); ?>  ><?php echo $categoryName; ?></option>
                <?php
            }
            ?>
        </select>
        <?php echo form_error('category'); ?>
        <?php
        itemEnd();
    }
    ?>




    <?php itemStart('Amount', TRUE); ?>
    <input type="number" name="amount" placeholder="Amount" value="<?php echo set_value('amount'); ?>"/>
    <?php echo form_error('amount'); ?>
    <?php itemEnd(); ?>

    <?php itemStart(); ?>
    <button type="submit" class="btn"><?php echo strtoupper($type); ?></button>
    <?php itemEnd(); ?>
</div>
</div>

<input type="hidden" name="type" value="<?php echo $type; ?>" />
<input type="hidden" name="account-id" value="<?php echo $account['id']; ?>" />
<input type="hidden" name="mode" value="formsubmit" />
</form>


</div>