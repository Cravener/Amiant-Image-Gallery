<?php   defined('C5_EXECUTE') or die(_("Access Denied.")); ?> 
<div id="AmiantImageGalleryBlock-imgRow<?php  echo $imgInfo['imgId']?>" class="AmiantImageGalleryBlock-imgRow" >
	<div class="backgroundRow" style="background: url(<?php  echo $imgInfo['thumbPath']?>) no-repeat left top; padding-left: 100px">
		<div class="cm-slideshowBlock-imgRowIcons" >
			<div style="float:right;">
				<a onclick="AmiantImageGalleryBlock.moveUp('<?php  echo $imgInfo['imgId']?>')" class="moveUpLink"></a>
				<a onclick="AmiantImageGalleryBlock.moveDown('<?php  echo $imgInfo['imgId']?>')" class="moveDownLink"></a>									  
			</div>
			<div style="margin-top:4px"><a onclick="AmiantImageGalleryBlock.removeImage('<?php  echo $imgInfo['imgId']?>')"><img src="<?php  echo ASSETS_URL_IMAGES?>/icons/delete_small.png" /></a></div>
		</div>
		<strong><?php  echo $imgInfo['fileName']?></strong>
		<div class="row" style="margin-top: 15px;">
			<div class="span5">
				<?php  echo t('Caption (optional)')?>: <input type="text" name="caption[]" value="<?php  echo $imgInfo['caption']?>" placeholder="<?php echo t('Caption');?>" style="vertical-align: middle; width: 150px" />
			</div>
			<div class="span5">
				<?php  echo t('Link URL (optional)')?>: <input type="url" name="url[]" value="<?php  echo $imgInfo['url']?>" placeholder="http://somesite.url/" style="vertical-align: middle; width: 150px" />
			</div>
			<input type="hidden" name="imgFIDs[]" value="<?php  echo $imgInfo['fID']?>">
			<input type="hidden" name="imgHeight[]" value="<?php  echo $imgInfo['imgHeight']?>">
		</div>
	</div>
</div>
