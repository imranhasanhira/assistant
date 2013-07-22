<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function itemStart($name = NULL) {
    echo '<div class="control-group">';
    if ($name != NULL) { {
            echo '<label class="control-label" for="' . strtolower($name) . '">' . $name . '</label>';
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
?>
<div class="row">
    <?php echo form_open('financials/transaction/' . $type, array('class' => 'form-horizontal')); ?>

    <?php itemStart('Title'); ?>
    <input type="text" id="title" placeholder="Title">
    <?php itemEnd(); ?>

    <?php itemStart('Description'); ?>
    <textarea rows="2" id="description" placeholder="Description"></textarea>
    <?php itemEnd(); ?>

    <?php itemStart('Category'); ?>
    <select name="category">
        <option value="-5">Select a category</option>
        <?php
        foreach ($categories as $categoryID => $categoryName) {
            ?>
            <option value="<?php echo $categoryID; ?>"><?php echo $categoryName; ?></option>
            <?php
        }
        ?>
    </select>
    <?php itemEnd(); ?>

    <?php itemStart('Amount'); ?>
    <input type="number" name="amount" placeholder="Amount" />
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