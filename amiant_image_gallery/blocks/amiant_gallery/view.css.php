<?php     defined('C5_EXECUTE') or die(_("Access Denied.")); ?>

<?php  
	$randID = rand();
	
	include("view.css.php");
	include("view.js.php");
	
	$c = Page::getCurrentPage();
?> 

<div class="AmiantImageGalleryBlock<?php   echo $randID?> AmiantImageGallery<?php   echo $randID?>">

	<?php  if (!empty($title)) { ?>
	<div class="AmiantImageGalleryTitle">
		<?php  echo $title; ?>
	</div>
	<?php  } ?>
	
	<?php    	if($paginator && strlen($paginator->getPages())>0) {
			if (($showAsThumbnails == true) && (!isset($_REQUEST['aigid'.$bID]))) {
	?>
				<div class="AmiantImageGalleryBlockControlBar<?php   echo $randID?>">
					<div  class="ig_pagination" style="clear: both;">
						<div class="ig_pagination_controls">
							<span class="ig_pageLeft"><?php   echo $paginator->getPrevious()?></span>
							<span class="ig_pager"><?php   echo $paginator->getPages()?></span>
							<span class="ig_pageRight"><?php   echo $paginator->getNext()?></span>
						</div>
					</div>
					<div style="clear: both;"></div>
				</div>
	<?php    		} 
		}
	?>
	
	
	<?php   if (($showAsThumbnails == true) && (!isset($_REQUEST['aigid'.$bID]))) {
		$ih = Loader::helper('image');
		$th = Loader::helper('text');
		$uh = Loader::helper('url');
		
		if (isset($_REQUEST['aig_p'.$bID])) {
			$slideNum = $maxThumbnailsPerPage * (intval($_REQUEST['aig_p'.$bID]) - 1);
		} else {
			$slideNum = 0;
		}
		
		$zindex = 100;
		foreach($imagesForThumbnails as $imgInfo) {
			$zindex--;
			
			$f = File::getByID($imgInfo['fID']);
			
			$thumb = $ih->getThumbnail($f, $maxThumbnailWidth, $maxThumbnailHeight);
			
			echo '<div id="AmiantImageGalleryThumbnailContainerWrapper'.$imgInfo['fID'].$randID.'" class="AmiantImageGalleryThumbnailContainerWrapper'.$randID.'" ';
			if ($c->isEditMode()) {
				echo ' >';
			} else {
				echo 'style="z-index: '.$zindex.';">'; // Fix for Internet Explorer for Bubble Popups;
			} 
			echo '<div id="AmiantImageGalleryThumbnailContainer'.$imgInfo['fID'].$randID.'" class="AmiantImageGalleryThumbnailContainer'.$randID.' AmiantImageGalleryThumbnailContainerLoading'.$randID.'" >';
			
			if($imgInfo['url']) {
				echo '<a id="AmiantImageGalleryThumbnailLink'.$imgInfo['fID'].$randID.'" class="AmiantImageGalleryThumbnailLink" href="'.$imgInfo['url'].'">';
			} else {
				$imgTitle = "";
				if ($enableZoomMode) {
					$fullsizeImg = $ih->getThumbnail($f, $zoomModeMaxWidth, $zoomModeMaxHeight);
					$url = $fullsizeImg->src;
					if ($zoomModeDisplayInformation && $zoomModeDisplayCaption) $imgTitle = $imgInfo['caption'];
				} else {
					$url = $uh->setVariable(array("aigid".$bID => $slideNum), false, "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
				}
				echo '<a id="AmiantImageGalleryThumbnailLink'.$imgInfo['fID'].$randID.'" class="AmiantImageGalleryThumbnailLink zoomModeImage'.$randID.'" rel="aigGroup'.$randID.'" href="'.$url.'" title="'.$imgTitle.'">';
			}
			
			echo '</a>';

			echo '</div>';
						
			if($displayThumbnailBubblePopup) {
				if (($displayThumbnailCaption) && (!$imgInfo['caption'])) $displayCaption = false; else $displayCaption = true;
				if ((!$displayCaption) && (($displayThumbnailFileName) || ($displayThumbnailFileType) || ($displayThumbnailFileSize) || ($displayThumbnailDownloadLink))) $withoutCaption = true; else $withoutCaption = false;
				if (($displayCaption) && ((!$displayThumbnailFileName) && (!$displayThumbnailFileType) && (!$displayThumbnailFileSize) && (!$displayThumbnailDownloadLink))) $onlyCaption = true; else $onlyCaption = false;
				
				
				if (($displayCaption) || ($displayThumbnailFileName) || ($displayThumbnailFileType) || ($displayThumbnailFileSize) || ($displayThumbnailDownloadLink)) {
					echo '	<div>
						<table class="AmiantImageGalleryBlockPopup" style="width: '.intval($maxThumbnailWidth * 2).'px;">
							<tbody>
								<tr>
									<td class="top_left"></td>
									<td class="top"></td>
									<td class="top_right"></td>
								</tr>
								<tr>
									<td class="left"></td>
									<td class="center">';
										if ($displayCaption) echo $imgInfo['caption'];
										if ($withoutCaption) echo '<div class="AmiantImageGalleryBlockFileInfoNoCaption">';
										if (($displayCaption) && (!$withoutCaption) && (!$onlyCaption)) echo '<div class="AmiantImageGalleryBlockFileInfo">';
										if ($displayThumbnailFileName) echo t('Name').': '.$th->shorten($imgInfo['fileName'], 20, '...').'<br />';
										if ($displayThumbnailFileType) echo t('Type').': '.$imgInfo['type'].'<br />';
										if ($displayThumbnailFileSize) echo t('Size').': '.$imgInfo['size'].'<br />';
										if ($displayThumbnailDownloadLink) echo	'<a href="'. DIR_REL . '/index.php/download_file/view/'.$imgInfo['fID'].'/">'.t('Download').'</a>';
										if ((!$onlyCaption) || ($withoutCaption)) echo '</div>';
					echo '					
									</td>
									<td class="right"></td>
								</tr>
								<tr>
									<td class="bottom_left"></td>
									<td class="bottom"></td>
									<td class="bottom_right"></td>
								</tr>
							</tbody>
						</table>
						</div>
					';
				}
			}
			
			echo '</div> ';
			
			
			echo '
				<script type="text/javascript">
					$(function () {
						AmiantImageGalleryBlockLoadImage'.$randID.'("'.$thumb->src.'", "AmiantImageGalleryThumbnail'.$randID.'", "'.intval($thumb->height / -2).'", "'.$imgInfo['caption'].'", "'.$imgInfo['caption'].'", "AmiantImageGalleryThumbnailContainer'.$imgInfo['fID'].$randID.'", "AmiantImageGalleryThumbnailLink'.$imgInfo['fID'].$randID.'");
	    			});
				</script>
			';
			
			$slideNum++;
		}
		
		if($displayThumbnailBubblePopup) {
			echo '
				<script type="text/javascript">
					AmiantImageGalleryBlockCreatePopups'.$randID.'();
				</script>
			';
		}
	} else if ((isset($_REQUEST['aigid'.$bID])) || (!$showAsThumbnails)) {
		$th = Loader::helper('text');
		
		if ($c->isEditMode()) {
	?>
		<div class="ccm-edit-mode-disabled-item" style="width:<?php   echo $width?>px; height:<?php   echo $height?>px;">
			<div style="padding:8px 0px; padding-top: <?php   echo round($height/2)-10?>px;"><?php   echo t('Content disabled in Edit Mode.'); ?></div>
		</div>
	<?php  
		} else {
			$ih = Loader::helper('image');
			
			if (!$showAsThumbnails)	$startingSlide = 0;
			else $startingSlide = intval($_REQUEST['aigid'.$bID]);
			
			$slideIndex = 0;
		
	?>
		<div class="AmiantImageGalleryBlockControlBar<?php   echo $randID?>">
			<div class="AmiantImageGallerySlideControllsWrapper<?php   echo $randID?>" style="clear: both;">
				<span id="AmiantImageGalleryImagesLoadingWaiter<?php   echo $randID?>"><img src="<?php   echo $this->getBlockUrl(); ?>/images/ajax-loader-small.gif" width="16" height="16" alt="" /></span>
				<span id="AmiantImageGalleryImagesLoaded<?php   echo $randID?>" style="display: none;">Loaded ?</span>
				<span id="AmiantImageGalleryImageIndex<?php   echo $randID?>">Image X of Y</span> |
				<span id="AmiantImageGallerySlideControllPrev<?php   echo $randID?>" class="AmiantImageGallerySlideControllPrev<?php   echo $randID?>">&laquo; <?php   echo t('Previous'); ?></span>
				|
				<span id="AmiantImageGallerySlideControllNext<?php   echo $randID?>" class="AmiantImageGallerySlideControllNext<?php   echo $randID?>"><?php   echo t('Next'); ?> &raquo;</span>
			</div>
			<div style="clear: both;"></div>
		</div>
		<?php  if ($enableSlidesPager) { ?>
		<div class="AmiantImageGalleryBlockControlBar<?php   echo $randID?>">
			<span id="AmiantImageGallerySlideControllNav<?php    echo $randID?>" class="AmiantImageGallerySlideControllNav<?php    echo $randID?>"></span>
			<div style="clear: both;"></div>
		</div>
		<?php  } ?>
	<?php  

			echo '<div id="AmiantImageGallerySlidesWrapper'.$randID.'" class="AmiantImageGallerySlidesWrapper'.$randID.'">';
			
			foreach($imagesForFullView as $imgInfo) {
				if (($displayThumbnailCaption) && (!$imgInfo['caption'])) $displayCaption = false; else $displayCaption = true;
				if ($displaySlideInformation) {
					$displaySlideInformation = false;
					if ($displayCaption) $displaySlideInformation = true;
					if ($displaySlideFileName) $displaySlideInformation = true;
					if ($displaySlideFileType) $displaySlideInformation = true;
					if ($displaySlideFileSize) $displaySlideInformation = true;
					if ($displaySlideDownloadLink) $displaySlideInformation = true;
				}
				
				$slideIndex++;
				
				$f = File::getByID($imgInfo['fID']);
				
				$thumb = $ih->getThumbnail($f, $width, $height);
				
				echo '<div class="AmiantImageGallerySlide'.$randID.'" imgsrc="'.$thumb->src.'" slideIndex="'.$slideIndex.'">';
				
				if ($displaySlideInformation) {
					echo '<div class="AmiantImageGallerySlideInfo'.$randID.'">';
					
					if ($displayCaption) { 
						if($imgInfo['url']) {
							echo '<h2><a href="'.$imgInfo['url'].'">'.$imgInfo['caption'].'</a></h2>';
						} else {
							echo '<h2>'.$imgInfo['caption'].'</h2>';
						}
					}
					if ($displaySlideFileName) echo '<p>'.t('Name').': '.$th->shorten($imgInfo['fileName'], 20, '...').'</p>';
					if ($displaySlideFileType) echo '<p>'.t('Type').': '.$imgInfo['type'].'</p>';
					if ($displaySlideFileSize) echo '<p>'.t('Size').': '.$imgInfo['size'].'</p>';
					if ($displaySlideDownloadLink) echo	'<p><a href="'. DIR_REL . '/index.php/download_file/view/'.$imgInfo['fID'].'/">'.t('Download').'</a></p>';
					
					echo '</div>';
				}
				
				echo '</div>';
				
			}
			
			echo '</div>';
			
			echo '
				<script type="text/javascript">
					$(function () {
						AmiantImageGalleryBlockCalculateImagesCount'.$randID.'();
						AmiantImageGalleryBlockStartSlideshow'.$randID.'('.$startingSlide.');
					});
				</script>
			';
		}
	}
	
	
	
	?>
	
	
</div>

<div style="height: 10px; clear: both;"></div>

