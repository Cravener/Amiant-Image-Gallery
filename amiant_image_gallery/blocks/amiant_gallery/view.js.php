<?php    defined('C5_EXECUTE') or die(_("Access Denied.")); ?>

<script type="text/javascript">
	
	var alreadyInitializedFancyBox = false;
	var alreadyInitializedPopups = false;

    <?php  if ($displayThumbnailBubblePopup) { ?>
    
    var zindex = 100;
	
    function AmiantImageGalleryBlockCreatePopups<?php  echo $bID?>() {
		if (alreadyInitializedPopups == false) {
			$('.AmiantImageGalleryThumbnailContainerWrapper<?php  echo $bID; ?>').each(function () {
				
				zindex--;
				$('.AmiantImageGalleryBlockPopup', this).css('z-index', zindex);
				
				var distance = <?php  echo intval($maxThumbnailHeight / 5); ?>;
				var showTime = 250;
				var hideTime = 250;
				var hideDelay = 50;
				var showDelay = 1000;

				var hideDelayTimer = null;
				var showDelayTimer = null;

				var beingShown = false;
				var shown = false;
				var trigger = this;
				var info = $('.AmiantImageGalleryBlockPopup', this).css('opacity', 0);

				$(trigger).mouseover(function () {
					if (hideDelayTimer) clearTimeout(hideDelayTimer);
					if (beingShown || shown) {
						return;
					} else {
						beingShown = true;
						info.css({
							top: <?php  echo intval($maxThumbnailHeight / 3 * 2); ?>,
							left: <?php  echo intval($maxThumbnailWidth - ($maxThumbnailWidth / 5)); ?>,
							display: 'block'
					}).animate({
							top: '+=' + distance + 'px',
							opacity: 1
					}, showTime, 'swing', function() {
							beingShown = false;
							shown = true;
					});
					}

					return false;
				}).mouseout(function () {
					//console.log('mouseout');
					if (hideDelayTimer) clearTimeout(hideDelayTimer);
					hideDelayTimer = setTimeout(function () {
						hideDelayTimer = null;
						info.animate({
							top: '-=' + distance + 'px',
							opacity: 0
						}, hideTime, 'swing', function () {
							shown = false;
							info.css('display', 'none');
						});

					}, hideDelay);

					return false;
				});
			});
			
			alreadyInitializedPopups = true;
		}
    }

    <?php  } ?>
    
    var imgCount<?php  echo $bID?> = 0;

    function AmiantImageGalleryBlockLoadImage<?php  echo $bID?>(source, css_class, margintop, title, alt, objectLoader, objectForInjection) {
    	var img = new Image();
    	$(img).load(function () {
       		$(this).css('display', 'none'); // .hide() doesn't work in Safari when the element isn't on the DOM already
      		$(this).hide();
       		$('#'+objectLoader).removeClass('AmiantImageGalleryThumbnailContainerLoading<?php  echo $bID?>');
       		$('#'+objectForInjection).append(this);
       		$(this).fadeIn(500);
       	}).error(function () {
       		$('#'+objectLoader).removeClass('AmiantImageGalleryThumbnailContainerLoading<?php  echo $bID?>');
       		$('#'+objectLoader).addClass('AmiantImageGalleryThumbnailContainerError<?php  echo $bID?>');
       	}).attr('src', source).attr('class', css_class).attr('alt', alt).css('margin-top', parseInt(margintop)).addClass('AmiantImageGalleryBlockPopupTrigger')<?php  if ($addThumbnailTitleAttr) echo ".attr('title', title);"; else echo ";"; ?>
    }
    
    function AmiantImageGalleryBlockCalculateImagesCount<?php  echo $bID?>() {
		$("#AmiantImageGallerySlidesWrapper<?php  echo $bID?> .AmiantImageGallerySlide<?php  echo $bID?>").each(function() {
			imgCount<?php  echo $bID?>++;
		});
	}
    
    function AmiantImageGalleryBlockCountLoadedImages<?php  echo $bID?>() {
		var imgLoadedCount = 0;
			
		$("#AmiantImageGallerySlidesWrapper<?php  echo $bID?> .AmiantImageGallerySlide<?php  echo $bID?>").each(function() {
			if (!$(this).attr('imgsrc')) {
				imgLoadedCount++;
			}
		});
		
		$("#AmiantImageGalleryImagesLoaded<?php  echo $bID?>").text("Loaded "+imgLoadedCount+" of "+imgCount<?php  echo $bID?>);
	}
		
	function AmiantImageGalleryBlockStartSlideshow<?php  echo $bID?>(slideID) {
		
		$("#AmiantImageGallerySlidesWrapper<?php  echo $bID?>").cycle({
			fx: "<?php  echo $controller->getTransitionFX($transitionFX); ?>",
			cleartypeNoBg: true,
			speed:  "<?php  echo $speed; ?>",
			timeout: <?php  if ($autoSlide == 1) echo $pause; else echo "0"; ?>,
			pause: <?php  echo $pauseOnMouseHover; ?>,
			nowrap: <?php  echo ($continuous == 1) ? 0 : 1; ?>,
			startingSlide: slideID,
			next:   '#AmiantImageGallerySlideControllNext<?php  echo $bID?>', 
			prev:   '#AmiantImageGallerySlideControllPrev<?php  echo $bID?>',
			before: beforeSlide<?php  echo $bID?>,
			after: afterSlide<?php  echo $bID?>,
			end: endSlideshow<?php  echo $bID?>
		});
	}
	
	function AmiantImageGalleryBlockLoadFullImage<?php  echo $bID?>(slideDiv) {
		if ($(slideDiv).attr('imgsrc')) {
			var imgsrc = $(slideDiv).attr('imgsrc');
			var image = new Image();
		
			$(slideDiv).addClass('AmiantImageGalleryThumbnailContainerLoading<?php  echo $bID?>');
			$("#AmiantImageGalleryImagesLoadingWaiter<?php  echo $bID?>").show();
		    	
	    	$(image).load(function () {
	       		$(this).css('display', 'none'); // .hide() doesn't work in Safari when the element isn't on the DOM already
	      		$(this).hide();
	       		$(slideDiv).removeClass('AmiantImageGalleryThumbnailContainerLoading<?php  echo $bID?>');
	       		$(slideDiv).append(this);
	       		$(this).fadeIn(500);
	       		$("#AmiantImageGalleryImagesLoadingWaiter<?php  echo $bID?>").hide();
	       		
	       		<?php  if ($alignSlideToCenter) { ?>
	       		$(this).css('top', '50%').css('margin-top', parseInt(parseInt($(this).attr('height')) / -2));
	       		<?php  } ?>
	       		
	       	}).error(function () {
	       		$(slideDiv).removeClass('AmiantImageGalleryThumbnailContainerLoading<?php  echo $bID?>');
	       		$(slideDiv).addClass('AmiantImageGalleryThumbnailContainerError<?php  echo $bID?>');
       		}).attr('src', imgsrc).css('cursor', 'pointer').css('position', 'relative').click(function () {
				$("#AmiantImageGallerySlidesWrapper<?php  echo $bID?>").cycle('next');
			});
       		
       		$(slideDiv).removeAttr('imgsrc');
		}
		
		AmiantImageGalleryBlockCountLoadedImages<?php  echo $bID?>();
	}
	
	function beforeSlide<?php  echo $bID?>(curr, next, opts, forwardFlag) {

	}
	
	function afterSlide<?php  echo $bID?>(curr, next, opts, forwardFlag) {
		
		AmiantImageGalleryBlockLoadFullImage<?php  echo $bID?>(next);
		
		if (forwardFlag) {
			AmiantImageGalleryBlockLoadFullImage<?php  echo $bID?>($(next).next(".AmiantImageGallerySlide<?php  echo $bID?>"));
		} else {
			AmiantImageGalleryBlockLoadFullImage<?php  echo $bID?>($(next).prev(".AmiantImageGallerySlide<?php  echo $bID?>"));
		}
		
		$("#AmiantImageGalleryImageIndex<?php  echo $bID?>").text("<?php  echo t('Image'); ?> "+$(next).attr('slideindex')+" <?php  echo t('of'); ?> "+imgCount<?php  echo $bID?>);
		
	}
	
	function endSlideshow<?php  echo $bID?>(options) {
		
	}
	
	<?php  if ($enableZoomMode) { ?>
	
	function formatTitle<?php  echo $bID?>(title, currentArray, currentIndex, currentOpts) {
		var titleString = '';
		<?php  if ($zoomModeDisplayInformation) { ?>
			titleString = '<div id="zoom-mode-image-title">';
			<?php  if ($zoomModeDisplayCaption) { ?>
			titleString += (title && title.length ? '<b>' + title + '</b><br />' : '' );
			<?php  } ?>
			<?php  if ($zoomModeDisplayImageIndex) { ?>
			titleString += '<?php  echo t('Image');?> ' + (currentIndex + 1) + ' <?php  echo t('of'); ?> ' + currentArray.length;
			<?php  } ?>
			titleString += '</div>';
		<?php  } ?>
		return titleString;
	}

	
	$(function () {
		if (alreadyInitializedFancyBox == false) {
			$("a.zoomModeImage<?php  echo $bID?>").fancybox({
				'transitionIn'	:	'<?php  echo $controller->getZoomModeFX($zoomModeEffect); ?>',
				'transitionOut'	:	'<?php  echo $controller->getZoomModeFX($zoomModeEffect); ?>',
				'speedIn'		:	<?php  echo intval($zoomModeEffectSpeed); ?>, 
				'speedOut'		:	<?php  echo intval($zoomModeEffectSpeed); ?>, 
				'overlayShow'	:	<?php  echo ($zoomModeShowOverlay) ? "true" : "false"; ?>,
				'overlayOpacity':	<?php  echo $zoomModeOverlayOpacity; ?>,
				'overlayColor'  :	'<?php  echo $zoomModeOverlayColor; ?>',
				'cyclic'		:	<?php  echo ($zoomModeCyclic) ? "true" : "false"; ?>,
				'autoScale'		:	<?php  echo ($zoomModeAutoScale) ? "true" : "false"; ?>,
				'centerOnScroll':	<?php  echo ($zoomModeCenterOnScroll) ? "true" : "false"; ?>,
				'hideOnOverlayClick':	<?php  echo ($zoomModeHideOnOverlayClick) ? "true" : "false"; ?>,
				'hideOnContentClick':	<?php  echo ($zoomModeHideOnContentClick) ? "true" : "false"; ?>,
				'titleShow'		:	<?php  echo ($zoomModeDisplayInformation) ? "true" : "false"; ?>,
				'titlePosition'	:	'<?php  echo $controller->getZoomModeInfoPosition($zoomModeInformationPosition); ?>',
				'showCloseButton':	<?php  echo ($zoomModeShowCloseButton) ? "true" : "false"; ?>,
				'showNavArrows'	:	<?php  echo ($zoomModeShowNavArrows) ? "true" : "false"; ?>,
				'enableEscapeButton': <?php  echo ($zoomModeEnableEscButton) ? "true" : "false"; ?>,
				'titleFormat'	:	formatTitle<?php  echo $bID?>
			});
			
			alreadyInitializedFancyBox = true;
		}
	});
	
	<?php  } ?>
	

  	
</script>
