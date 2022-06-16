<?php
defined('RESTRICTED') or die('Restricted access');
$roles = $this->get('roles');
?>

<div class="pageheader">

    <div class="pageicon"><span class="<?php echo $this->getModulePicture() ?>"></span></div>
    <div class="pagetitle">
        <h5><?php echo $this->__('label.administration') ?></h5>
        <h1><h1><?php echo $this->__('headlines.users'); ?></h1></h1>
    </div>
</div><!--pageheader-->

<div class="maincontent">
    <div class="maincontentinner">

        <?php echo $this->displayNotification() ?>

        <div class="row">
            <div class="col-md-6">
                <?php echo $this->displayLink('users.newUser', "<i class='iconfa-plus'></i> ".$this->__('buttons.add_user'), null, array('class' => 'btn btn-primary btn-rounded')) ?>

            </div>
            <div class="col-md-6 align-right">

            </div>
        </div>

        <table class="table table-bordered" cellpadding="0" cellspacing="0" border="0" id="allUsersTable">
            <colgroup>
                <col class="con1">
                <col class="con0">
            </colgroup>
            <thead>
                <tr>
                    <th class='head1'><?php echo $this->__('label.name'); ?></th>
                    <th class='head0'><?php echo $this->__('label.email'); ?></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($this->get('allUsers') as $row): ?>
                    <tr>
                        <td style="padding:6px 10px;">
                        <?php if($login::userIsAtLeast("admin")) : ?>
                                <?php echo $this->displayLink('users.editUser', sprintf( $this->__("text.full_name"), $this->escape($row["firstname"]), $this->escape($row["lastname"])), array('id' => $row['id'])); ?>
                            <?php else: ?>
                            <?php echo sprintf( $this->__("text.full_name"), $this->escape($row["firstname"]), $this->escape($row["lastname"])); ?>
                        <?php endif; ?>
                        </td>
                        <td><?php echo $row['username']; ?></td>
                        <?php if($login::userIsAtLeast("admin")) : ?>
                        <td><a href="<?=BASE_URL ?>/users/delUser/<?php echo $row['id']?>" class="delete"><i class="fa fa-trash"></i> <?=$this->__('links.delete');?></a></td>
                        <?php endif; ?>
                    </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function() {
            leantime.usersController.initUserTable();
            leantime.usersController._initModals();
        }
    );

</script>
