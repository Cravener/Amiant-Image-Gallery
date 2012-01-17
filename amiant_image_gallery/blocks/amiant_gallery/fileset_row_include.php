<?php   defined('C5_EXECUTE') or die(_("Access Denied.")); ?> 
<div id="AmiantImageGalleryBlock-fsRow" class="AmiantImageGalleryBlock-fsRow" >
	
	<div class="backgroundRow" style="padding-left: 100px">
		<strong><?php  echo t('File Set');?>:</strong> <span class="ccm-file-set-pick-cb"><?php  echo $form->select('fsID', $fsInfo['fileSets'], $fsInfo['fsID'])?></span><br/><br/>
	&nbsp;</div>
	<div class="ccm-note"><?php  echo t('Picture will link to url if (link_url) file attribute is set.');?></div>
	<div class="ccm-note"><?php  echo t('Picture will show caption if (image_caption) file attribute is set.');?></div>
</div>
