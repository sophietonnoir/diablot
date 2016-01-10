<?php global $option_setting; ?>
<?php if ($option_setting['mail-id']) : ?>
	<span class="top-left">
		<i class="fa fa-envelope"></i> <?php echo $option_setting['mail-id']; ?>
	</span>
<?php endif; ?>
<?php if ($option_setting['phone-number']) :?>
	<span class="top-left">
		<i class="fa fa-phone"></i> <?php echo $option_setting['phone-number'] ?>
	</span>	
<?php endif; ?>