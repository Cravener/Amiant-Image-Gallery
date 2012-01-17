<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));
$al = Loader::helper('concrete/asset_library');
$ah = Loader::helper('concrete/interface');

if (!$zoomModeOverlayOpacity) $zoomModeOverlayOpacity = "0.5";
if (!$zoomModeOverlayColor) $zoomModeOverlayColor = "#000000";

?>
<style type="text/css">
#AmiantImageGalleryBlock-imgRows a{cursor:pointer}
#AmiantImageGalleryBlock-imgRows .AmiantImageGalleryBlock-imgRow,
#AmiantImageGalleryBlock-fsRow {margin-bottom:16px;clear:both;padding:7px;background-color:#eee}
#AmiantImageGalleryBlock-imgRows .AmiantImageGalleryBlock-imgRow a.moveUpLink{ display:block; background:url(<?php  echo DIR_REL?>/concrete/images/icons/arrow_up.png) no-repeat center; height:10px; width:16px; }
#AmiantImageGalleryBlock-imgRows .AmiantImageGalleryBlock-imgRow a.moveDownLink{ display:block; background:url(<?php  echo DIR_REL?>/concrete/images/icons/arrow_down.png) no-repeat center; height:10px; width:16px; }
#AmiantImageGalleryBlock-imgRows .AmiantImageGalleryBlock-imgRow a.moveUpLink:hover{background:url(<?php  echo DIR_REL?>/concrete/images/icons/arrow_up_black.png) no-repeat center;}
#AmiantImageGalleryBlock-imgRows .AmiantImageGalleryBlock-imgRow a.moveDownLink:hover{background:url(<?php  echo DIR_REL?>/concrete/images/icons/arrow_down_black.png) no-repeat center;}
#AmiantImageGalleryBlock-imgRows .cm-slideshowBlock-imgRowIcons{ float:right; width:35px; text-align:left; }
#ccm-gallery-options-tab input, #ccm-gallery-options-tab select, #ccm-gallery-options-tab label{margin-top: 5px;}
.required_field {color: #FF0000; font-weight: bold;}

#colorSelector {
	width: 36px;
	height: 36px;
	position: relative;
}

#colorSelector div {
	background: url(<?php  echo $this->getBlockURL(); ?>/images/select-color.png) repeat scroll center center transparent;
	height: 28px;
	left: 4px;
	top: 4px;
	width: 28px;
	position: absolute;
}

.ui-slider { position: relative; text-align: left; margin-top: 5px; }
.ui-slider .ui-slider-handle { position: absolute; z-index: 2; width: 1.2em; height: 1.2em; cursor: default; }
.ui-slider .ui-slider-range { position: absolute; z-index: 1; font-size: .7em; display: block; border: 0; background-position: 0 0; }

.ui-slider-horizontal { height: .8em; }
.ui-slider-horizontal .ui-slider-handle { top: -.3em; margin-left: -.6em; }
.ui-slider-horizontal .ui-slider-range { top: 0; height: 100%; }
.ui-slider-horizontal .ui-slider-range-min { left: 0; }
.ui-slider-horizontal .ui-slider-range-max { right: 0; }

.ui-slider-vertical { width: .8em; height: 100px; }
.ui-slider-vertical .ui-slider-handle { left: -.3em; margin-left: 0; margin-bottom: -.6em; }
.ui-slider-vertical .ui-slider-range { left: 0; width: 100%; }
.ui-slider-vertical .ui-slider-range-min { bottom: 0; }
.ui-slider-vertical .ui-slider-range-max { top: 0; }
</style>


<script type="text/javascript">

	$(function () {
		var ccm_fpActiveTab = "ccm-gallery-type";
		$("#ccm-gallery-tabs a").click(function() {
			ccm_fpActiveTab = $("#ccm-gallery-tabs li.ccm-nav-active a").attr("id");
			$("#ccm-gallery-tabs li.ccm-nav-active").removeClass('ccm-nav-active');
			$("#" + ccm_fpActiveTab + "-tab").hide();
			ccm_fpActiveTab = $(this).attr('id');
			$(this).parent().addClass("ccm-nav-active");
			$("#" + ccm_fpActiveTab + "-tab").show();
			
			if ($("#showAsThumbnails").attr('checked')) {
				$("#thumbnails_set_off_warning").hide();
			} else {
				$("#thumbnails_set_off_warning").show();
			}
			
			if ($("#enableZoomMode").attr('checked')) {
				$("#zoommode_set_off_warning").hide();
			} else {
				$("#zoommode_set_off_warning").show();
			}
		});
		
		
		<?php  if ($showAsThumbnails == true) { ?>
			$("#thumbnails_set_off_warning").hide();
		<?php  } else { ?>
			$("#thumbnails_set_off_warning").show();
		<?php  } ?>
		
		$("#showAsThumbnails").click(function() {
			$("#zoomMode-div").slideToggle("slow");
		});
		
		<?php  if ($displayThumbnailBubblePopup == true) { ?>
			$("#thumbnailPopupBubbleOptions").show();
		<?php  } else { ?>
			$("#thumbnailPopupBubbleOptions").hide();
		<?php  } ?>
		
		$("#displayThumbnailBubblePopup").click(function() {
			$("#thumbnailPopupBubbleOptions").slideToggle("slow");
		});
		
		<?php  if ($displaySlideInformation == true) { ?>
			$("#slideInformationOptions").show();
		<?php  } else { ?>
			$("#slideInformationOptions").hide();
		<?php  } ?>
		
		$("#displaySlideInformation").click(function() {
			$("#slideInformationOptions").slideToggle("slow");
		});
		
		<?php  if ($zoomModeDisplayInformation == true) { ?>
			$("#zoom-mode-image-information-settings").show();
		<?php  } else { ?>
			$("#zoom-mode-image-information-settings").hide();
		<?php  } ?>
		
		$("#zoomModeDisplayInformation").click(function() {
			$("#zoom-mode-image-information-settings").slideToggle("slow");
		});

		$('#overlay-opacity-slider').slider({
			min: 0,
			max: 10,
			value: <?php  echo $zoomModeOverlayOpacity; ?> * 10,
			change: function(event, ui) {
				var op = ui.value / 10;
				$('#zoomModeOverlayOpacity').val(op);
				$('#overlay-opacity-span').html(op);
			},
			slide: function(event, ui) {
				var op = ui.value / 10;
				$('#zoomModeOverlayOpacity').val(op);
				$('#overlay-opacity-span').html(op);
			}
		});	

		$('#colorSelector').ColorPicker({
			color: '<?php  echo $zoomModeOverlayColor; ?>',
			onShow: function (colpkr) {
				$(colpkr).fadeIn(500);
				return false;
			},
			onHide: function (colpkr) {
				$(colpkr).fadeOut(500);
				return false;
			},
			onChange: function (hsb, hex, rgb) {
				$('#colorSelector div').css('backgroundColor', '#' + hex);
				$('#zoomModeOverlayColor').val('#' + hex);
			}
		});
	});
	
</script>

<ul class="ccm-dialog-tabs" id="ccm-gallery-tabs">
	<li class="ccm-nav-active"><a href="javascript:void(0)" id="ccm-gallery-type"><?php  echo t('Images')?></a></li>
	<li><a href="javascript:void(0)" id="ccm-gallery-options"><?php  echo t('Gallery Options')?></a></li>
	<li><a href="javascript:void(0)" id="ccm-thumbnail-options"><?php  echo t('Thumbnail Options')?></a></li>
	<li><a href="javascript:void(0)" id="ccm-slide-options"><?php  echo t('Slide Options')?></a></li>
	<li><a href="javascript:void(0)" id="ccm-zoom-options"><?php  echo t('Zoom Mode Options')?></a></li>
	<li><a href="javascript:void(0)" id="ccm-info-options"><?php  echo t('Information')?></a></li>
</ul>

<div id="ccm-gallery-type-tab" style="margin-top:15px">
	<div id="newImg">
		<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
				<td>
					<strong><?php  echo t('Type')?></strong>
					<select name="type" style="vertical-align: middle">
						<option value="CUSTOM"<?php   if ($type == 'CUSTOM') { ?> selected<?php   } ?>><?php  echo t('Custom Slideshow')?></option>
						<option value="FILESET"<?php   if ($type == 'FILESET') { ?> selected<?php   } ?>><?php  echo t('Pictures from File Set')?></option>
					</select>
				</td>
			</tr>
			<tr style="padding-top: 8px">
				<td>
					<br />
					<span id="AmiantImageGalleryBlock-chooseImg"><?php  echo $ah->button_js(t('Add Image'), 'AmiantImageGalleryBlock.chooseImg()', 'left');?></span>
				</td>
			</tr>
		</table>
	</div>
	<br/>
	
	<div id="AmiantImageGalleryBlock-imgRows">
	<?php   if ($fsID <= 0) { ?>
	<?php 
		foreach($images as $imgInfo){ 
			$f = File::getByID($imgInfo['fID']);
			$fp = new Permissions($f);
			$imgInfo['thumbPath'] = $f->getThumbnailSRC(1);
			$imgInfo['fileName'] = $f->getTitle();
			if ($fp->canRead()) { 
				$this->inc('image_row_include.php', array('imgInfo' => $imgInfo));
			}
		}
	} ?>
	</div>
	
	<?php  
	Loader::model('file_set');
	$s1 = FileSet::getMySets();
	$sets = array();
	foreach ($s1 as $s){
		$sets[$s->fsID] = $s->fsName;
	}
	$fsInfo['fileSets'] = $sets;
	
	if ($fsID > 0) {
		$fsInfo['fsID'] = $fsID;
	} else {
		$fsInfo['fsID']='0';
	}
	$this->inc('fileset_row_include.php', array('fsInfo' => $fsInfo)); ?> 
	
	<div id="imgRowTemplateWrap" style="display:none">
	<?php  
	$imgInfo['imgId']='tempImgId';
	$imgInfo['slideshowImgId']='tempSlideshowImgId';
	$imgInfo['fID']='tempFID';
	$imgInfo['fileName']='tempFilename';
	$imgInfo['origfileName']='tempOrigFilename';
	$imgInfo['thumbPath']='tempThumbPath';
	$imgInfo['groupSet']=0;
	$imgInfo['imgHeight']=tempHeight;
	$imgInfo['url']='';
	$imgInfo['caption']='';
	$imgInfo['class']='AmiantImageGalleryBlock-imgRow';
	?>
	<?php   $this->inc('image_row_include.php', array('imgInfo' => $imgInfo)); ?>
	</div>
</div>

<div id="ccm-gallery-options-tab" style="display:none; margin-top: 15px;">
<div id="AmiantImageGalleryOptions">
	<h2><?php  echo t('Amiant Image Gallery Options'); ?></h2>

	<div class="ccm-block-field-group">
		<?php  echo $form->label('title', t('Gallery Title:'));?>&nbsp;
		<?php  echo $form->text('title', $title, array('style' => 'width: 350px;'));?>
	</div>
	<div class="ccm-block-field-group">
		<table style="width: 100%">
			<tr>
				<td style="vertical-align: top;">
					<?php  echo $form->label('width', t('Width:'));?>&nbsp;
					<?php  echo $form->text('width', $width, array('style' => 'width: 50px;'));?>
					<?php  echo $form->label('height', t('Height:'));?>&nbsp;
					<?php  echo $form->text('height', $height, array('style' => 'width: 50px;'));?>
				</td>
				<td style="vertical-align: top;">
					<div class="ccm-note"><?php  echo t('This is a size of Amiant Image Gallery block container.'); ?></div>
					<div class="ccm-note"><?php  echo t('Must be set to same size as images or higher <span class="required_field">(required)</span>.'); ?></div>
				</td>
			</tr>
		</table>
	</div>
	<div class="ccm-block-field-group">
		<?php  echo $form->checkbox('showAsThumbnails', 1, $showAsThumbnails); ?>
		<?php  echo $form->label('showAsThumbnails', t('Show As Thumbnails'));?>
		<?php  if ($showAsThumbnails) { ?>
		<div id="zoomMode-div">
		<?php  } else {?>
		<div id="zoomMode-div" style="display: none;">
		<?php  } ?>
			<br />
			<?php  echo $form->checkbox('enableZoomMode', 1, $enableZoomMode); ?>
			<?php  echo $form->label('enableZoomMode', t('Enable Zoom Mode'));?>
		</div>
	</div>
	<div class="ccm-block-field-group">
		<table style="width: 100%">
			<tr>
				<td style="vertical-align: top;">
					<?php  echo $form->label('transitionFX', t('Transition Type'));?>
					<?php  echo $form->select('transitionFX',
					array('blindX', 'blindY', 'blindZ', 'cover', 'curtainX', 'curtainY', 'fade', 'fadeZoom', 'growX', 'growY', 'none', 'scrollUp', 'scrollDown', 'scrollLeft', 'scrollRight', 'scrollHorz', 'scrollVert', 'shuffle', 'slideX', 'slideY', 'toss', 'turnUp', 'turnDown', 'turnLeft', 'turnRight', 'uncover', 'wipe', 'zoom'), $transitionFX, array('style' => 'width: 120px'));?>
				</td>
				<td style="vertical-align: top;">
					<?php  echo $form->label('speed', t('Transition speed:'));?>
					<?php  echo $form->text('speed', $speed, array('style' => 'width: 40px'));?>
					<?php  echo t('milliseconds.');?>
				</td>
			</tr>
		</table>
		
	</div>
	<div class="ccm-block-field-group">
		<table style="width: 100%">
			<tr>
				<td style="vertical-align: top;">
					<?php  echo $form->checkbox('autoSlide', 1, $autoSlide); ?>
					<?php  echo $form->label('autoSlide', t('Auto Play'));?>
				</td>
			</tr>
			<tr>
				<td style="vertical-align: top;">
					<?php  echo $form->checkbox('pauseOnMouseHover', 1, $pauseOnMouseHover); ?>
					<?php  echo $form->label('pauseOnMouseHover', t('Pause slideshow on mouse hover'));?>
				</td>
			</tr>
			<tr>
				<td style="vertical-align: top;">
					<?php  echo $form->checkbox('continuous', 1, $continuous); ?>
					<?php  echo $form->label('continuous', t('Loop images continuously'));?>
				</td>
			</tr>
			<tr>
				<td style="vertical-align: top;">
					<?php  echo $form->label('pause', t('Duration of each slide:'));?>
					<?php  echo $form->text('pause', ($pause/1000), array('style' => 'width: 40px'));?>
					<?php  echo t('seconds.');?>
				</td>
			</tr>
		</table>
	</div>
</div>
</div>

<div id="ccm-thumbnail-options-tab" style="display:none; margin-top: 15px;">
<div id="AmiantImageGalleryThumbnailOptions">
	<h2><?php  echo t('Thumbnail Options'); ?></h2>
	<div style="font-size: 10px; color: red;" id="thumbnails_set_off_warning"><?php  echo t('Please turn on "Show As Thumbnails" option on the "Gallery Options" tab, if you want to activate the options below.'); ?></div>
	<br />
	
	<div class="ccm-block-field-group">
		<div id="thumbnailsOptions">
			<table style='width: 100%;'>
				<tr>
					<td style='width: 200px; text-align: right; padding-right: 5px;'>
						<?php  echo $form->label('maxThumbnailsPerPage', t('Maximum Thumbnails per page:'));?>
					</td>
					<td>
						<?php  echo $form->text('maxThumbnailsPerPage', $maxThumbnailsPerPage, array('style' => 'width: 50px;'));?>
						<span class='required_field'><?php  echo t('(required)'); ?></span>
					</td>
				</tr>
				<tr>
					<td style='width: 200px; text-align: right; padding-right: 5px;'>
						<?php  echo $form->label('maxThumbnailWidth', t('Maximum Thumbnail Width:'));?>
					</td>
					<td>
						<?php  echo $form->text('maxThumbnailWidth', $maxThumbnailWidth, array('style' => 'width: 50px;'));?>
						<span class='required_field'><?php  echo t('(required)'); ?></span>
					</td>
				</tr>
				<tr>
					<td style='width: 200px; text-align: right; padding-right: 5px;'>
						<?php  echo $form->label('maxThumbnailHeight', t('Maximum Thumbnail Height:'));?>
					</td>
					<td>
						<?php  echo $form->text('maxThumbnailHeight', $maxThumbnailHeight, array('style' => 'width: 50px;'));?>
						<span class='required_field'><?php  echo t('(required)'); ?></span>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="ccm-block-field-group">
		<?php  echo $form->checkbox('addThumbnailTitleAttr', 1, $addThumbnailTitleAttr); ?>
		<?php  echo $form->label('addThumbnailTitleAttr', t('Add Title Attribute to IMG tag for thumbnails'));?>
	</div>
	<div class="ccm-block-field-group">
		<?php  echo $form->checkbox('displayThumbnailBubblePopup', 1, $displayThumbnailBubblePopup); ?>
		<?php  echo $form->label('displayThumbnailBubblePopup', t('Show Pop-up Bubble with Information for Thumbnails'));?><br />
		
		<div id="thumbnailPopupBubbleOptions" style="padding: 10px;">
			<?php  echo $form->checkbox('displayThumbnailCaption', 1, $displayThumbnailCaption); ?>
			<?php  echo $form->label('displayThumbnailCaption', t('Show File Caption in a Pop-up Bubble'));?><br />
		
			<?php  echo $form->checkbox('displayThumbnailFileName', 1, $displayThumbnailFileName); ?>
			<?php  echo $form->label('displayThumbnailFileName', t('Show File Name in a Pop-up Bubble'));?><br />
		
			<?php  echo $form->checkbox('displayThumbnailFileType', 1, $displayThumbnailFileType); ?>
			<?php  echo $form->label('displayThumbnailFileType', t('Show File Type in a Pop-up Bubble'));?><br />
		
			<?php  echo $form->checkbox('displayThumbnailFileSize', 1, $displayThumbnailFileSize); ?>
			<?php  echo $form->label('displayThumbnailFileSize', t('Show File Size in a Pop-up Bubble'));?><br />
		
			<?php  echo $form->checkbox('displayThumbnailDownloadLink', 1, $displayThumbnailDownloadLink); ?>
			<?php  echo $form->label('displayThumbnailDownloadLink', t('Show a Link to Download the File in a Pop-up Bubble'));?>
		</div>
	</div>
</div>
</div>

<div id="ccm-slide-options-tab" style="display:none; margin-top: 15px;">
<div id="AmiantImageGallerySlideOptions">
	<h2><?php  echo t('Slide Options'); ?></h2>
	<br />
	
	<div class="ccm-block-field-group">
		<?php  echo $form->checkbox('alignSlideToCenter', 1, $alignSlideToCenter); ?>
		<?php  echo $form->label('alignSlideToCenter', t('Align the image in the center of the block'));?>
	</div>
	<div class="ccm-block-field-group">
		<?php  echo $form->checkbox('displaySlideInformation', 1, $displaySlideInformation); ?>
		<?php  echo $form->label('displaySlideInformation', t('Show information about the image on the slide'));?><br />
		
		<div id="slideInformationOptions" style="padding: 10px;">
			<?php  echo $form->checkbox('displaySlideCaption', 1, $displaySlideCaption); ?>
			<?php  echo $form->label('displaySlideCaption', t('Show File Caption'));?><br />
		
			<?php  echo $form->checkbox('displaySlideFileName', 1, $displaySlideFileName); ?>
			<?php  echo $form->label('displaySlideFileName', t('Show File Name'));?><br />
		
			<?php  echo $form->checkbox('displaySlideFileType', 1, $displaySlideFileType); ?>
			<?php  echo $form->label('displaySlideFileType', t('Show File Type'));?><br />
		
			<?php  echo $form->checkbox('displaySlideFileSize', 1, $displaySlideFileSize); ?>
			<?php  echo $form->label('displaySlideFileSize', t('Show File Size'));?><br />
		
			<?php  echo $form->checkbox('displaySlideDownloadLink', 1, $displaySlideDownloadLink); ?>
			<?php  echo $form->label('displaySlideDownloadLink', t('Show a Link to Download the File'));?>
		</div>
	</div>
</div>
</div>

<div id="ccm-zoom-options-tab" style="display:none; margin-top: 15px;">
<div id="AmiantImageGalleryZoomOptions">
	<h2><?php  echo t('Zoom Mode Options'); ?></h2>
	<div style="font-size: 10px; color: red;" id="zoommode_set_off_warning"><?php  echo t('Please turn on "Show As Thumbnails" and "Enable Zoom Mode" options on the "Gallery Options" tab, if you want to activate the options below.'); ?></div>
	<br />
	
	<div class="ccm-block-field-group">
		<table style="width: 100%;">
			<tr>
				<td> 
					<?php  echo $form->label('zoomModeEffect', t('Effect:'));?>
					<?php  echo $form->select('zoomModeEffect', array('none', 'fade', 'elastic'), $zoomModeEffect, array('style' => 'width: 120px'));?>
				</td>
				<td>
					<?php  echo $form->label('zoomModeEffectSpeed', t('Effect speed:'));?>
					<?php  echo $form->text('zoomModeEffectSpeed', $zoomModeEffectSpeed, array('style' => 'width: 50px;')).' '.t('milliseconds.'); ?>
				</td>
			</tr>
		</table>
	</div>
	<div class="ccm-block-field-group">
		<table style="width: 100%;">
			<tr>
				<td> 
					<?php  echo $form->checkbox('zoomModeShowCloseButton', 1, $zoomModeShowCloseButton); ?>
					<?php  echo $form->label('zoomModeShowCloseButton', t('Show Close Button'));?>
				</td>
				<td>
					<?php  echo $form->checkbox('zoomModeShowNavArrows', 1, $zoomModeShowNavArrows); ?>
					<?php  echo $form->label('zoomModeShowNavArrows', t('Show Navigation Arrows'));?>
				</td>
			</tr>
		</table>
	</div>
	<div class="ccm-block-field-group">
		<table style="width: 100%;">
			<tr>
				<td> 
					<?php  echo $form->checkbox('zoomModeShowOverlay', 1, $zoomModeShowOverlay); ?>
					<?php  echo $form->label('zoomModeShowOverlay', t('Show Overlay'));?>
					<br />
					<?php  echo $form->checkbox('zoomModeHideOnOverlayClick', 1, $zoomModeHideOnOverlayClick); ?>
					<?php  echo $form->label('zoomModeHideOnOverlayClick', t('Hide Overlay on Click'));?>
					<br />
					<?php  echo $form->checkbox('zoomModeHideOnContentClick', 1, $zoomModeHideOnContentClick); ?>
					<?php  echo $form->label('zoomModeHideOnContentClick', t('Hide Overlay if Clicking the content'));?>
					<br />
					<?php  echo $form->checkbox('zoomModeEnableEscButton', 1, $zoomModeEnableEscButton); ?>
					<?php  echo $form->label('zoomModeEnableEscButton', t('Enable Escape Button'));?>
				</td>
				<td>
					<div style="width: 150px;">
						<?php  echo t('Overlay Opacity:'); ?>
						<span id="overlay-opacity-span"><?php  echo $zoomModeOverlayOpacity; ?></span>
						<?php  echo $form->hidden('zoomModeOverlayOpacity', $zoomModeOverlayOpacity); ?>
						<div id="overlay-opacity-slider"></div>
						<br />
						<div style="position: relative; display: block;">
							<?php  echo $form->hidden('zoomModeOverlayColor', $zoomModeOverlayColor); ?>
							<?php  echo t('Overlay Color:'); ?>
							<div id="colorSelector"><div style="background-color: <?php  echo $zoomModeOverlayColor; ?>;"></div></div>
						</div>
					</div>
				</td>
			</tr>
		</table>
	</div>
	<div class="ccm-block-field-group">
		<table style="width: 100%">
			<tr>
				<td>
					<?php  echo $form->checkbox('zoomModeCenterOnScroll', 1, $zoomModeCenterOnScroll); ?>
					<?php  echo $form->label('zoomModeCenterOnScroll', t('Center Image On Page Scroll'));?>
					<br />
					<?php  echo $form->checkbox('zoomModeCyclic', 1, $zoomModeCyclic); ?>
					<?php  echo $form->label('zoomModeCyclic', t('Cyclic View'));?>
					<br />
					<?php  echo $form->checkbox('zoomModeAutoScale', 1, $zoomModeAutoScale); ?>
					<?php  echo $form->label('zoomModeAutoScale', t('Auto Scale to fit in Viewport'));?>
					<br />
				</td>
				<td>
					<table style="width: 100%;">
						<tr>
							<td style="padding: 1px;">
								<?php  echo $form->label('zoomModeMaxWidth', t('Max Image Width:'));?>
							</td>
							<td style="padding: 1px;">
								<?php  echo $form->text('zoomModeMaxWidth', $zoomModeMaxWidth, array('style' => 'width: 50px;')).' '.t('pixels.'); ?>
							</td>
						</tr>
						<tr>
							<td style="padding: 1px;">
								<?php  echo $form->label('zoomModeMaxHeight', t('Max Image Height:'));?>
							</td>
							<td style="padding: 1px;">
								<?php  echo $form->text('zoomModeMaxHeight', $zoomModeMaxHeight, array('style' => 'width: 50px;')).' '.t('pixels.'); ?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</div>
	<div class="ccm-block-field-group">
		<?php  echo $form->checkbox('zoomModeDisplayInformation', 1, $zoomModeDisplayInformation); ?>
		<?php  echo $form->label('zoomModeDisplayInformation', t('Display Information About Image'));?>
		<div id="zoom-mode-image-information-settings" style="padding: 10px;">
			<?php  echo $form->checkbox('zoomModeDisplayCaption', 1, $zoomModeDisplayCaption); ?>
			<?php  echo $form->label('zoomModeDisplayCaption', t('Display Image Caption'));?>
			<br />
			<?php  echo $form->checkbox('zoomModeDisplayImageIndex', 1, $zoomModeDisplayImageIndex); ?>
			<?php  echo $form->label('zoomModeDisplayImageIndex', t('Display Image Index Counter'));?>
			<br />
			<?php  echo $form->label('zoomModeInformationPosition', t('Information Position:'));?>
			<?php  echo $form->select('zoomModeInformationPosition', array('inside', 'outside', 'over'), $zoomModeInformationPosition, array('style' => 'width: 120px'));?>
		</div>
	</div>
	
</div>
</div>

<div id="ccm-info-options-tab" style="display:none; margin-top: 15px;">
<div id="AmiantImageGalleryInfo">
	<div class="ccm-block-field-group">
		<h2><?php  echo t('Information');?></h2>
	</div>
	
	<div class="ccm-block-field-group">
		<h3><?php  echo t('System');?></h3>
		<p><span style="font-weight: bold;"><?php  echo t('Concrete5 CMS Version'); ?>: </span><?php  echo APP_VERSION; ?></p>
		<p><span style="font-weight: bold;"><?php  echo t('Product Name'); ?>: </span><?php  echo $controller->getBlockTypeName(); ?></p>
		<p><span style="font-weight: bold;"><?php  echo t('Product Description'); ?>: </span><?php  echo $controller->getBlockTypeDescription(); ?></p>
		<p><span style="font-weight: bold;"><?php  echo t('Product Version'); ?>: </span><?php  echo $controller->getProductVersion(); ?></p>
	</div>
	<div class="ccm-block-field-group">
		<h3><?php  echo t('License');?></h3>
		<p><?php  echo t('This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License (GPL) Version 2. License is included in the distribution in the file: LICENSE');?></p>
		<p><?php  echo t('This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.');?></p>
	</div>
	<div class="ccm-block-field-group">
		<h3><?php  echo t('Contacts');?></h3>
		<p><?php  echo t('For any questions, bug reports and comments please, contact me at <a href="mailto:cravener@gmail.com">cravener@gmail.com</a>.');?></p>
		<p><?php  echo t('Or check my blog at <a href="http://countraven.blogspot.com" target="_blank">http://countraven.blogspot.com</a>.');?></p>
		<p><?php  echo t('Or follow me on Twitter <a href="http://twitter.com/Count_Raven" target="_blank">Count_Raven</a>.');?></p>
	</div>
	
	
	
</div>
</div>
