var AmiantImageGalleryBlock = {
	
	init:function(){},	
	
	chooseImg:function(){ 
		ccm_launchFileManager('&fType=' + ccmi18n_filemanager.FTYPE_IMAGE);
	},
	
	showImages:function(){
		$("#AmiantImageGalleryBlock-imgRows").show();
		$("#AmiantImageGalleryBlock-chooseImg").show();
		$("#AmiantImageGalleryBlock-fsRow").hide();
	},

	showFileSet:function(){
		$("#AmiantImageGalleryBlock-imgRows").hide();
		$("#AmiantImageGalleryBlock-chooseImg").hide();
		$("#AmiantImageGalleryBlock-fsRow").show();
	},

	selectObj:function(obj){
		if (obj.fsID != undefined) {
			$("#AmiantImageGalleryBlock-fsRow input[name=fsID]").attr("value", obj.fsID);
			$("#AmiantImageGalleryBlock-fsRow input[name=fsName]").attr("value", obj.fsName);
			$("#AmiantImageGalleryBlock-fsRow .AmiantImageGalleryBlock-fsName").text(obj.fsName);
		} else {
			this.addNewImage(obj.fID, obj.thumbnailLevel1, obj.height, obj.title);
		}
	},

	addImages:0, 
	addNewImage: function(fID, thumbPath, imgHeight, title) { 
		this.addImages--;
		var slideshowImgId=this.addImages;
		var templateHTML=$('#imgRowTemplateWrap .AmiantImageGalleryBlock-imgRow').html().replace(/tempFID/g,fID);
		templateHTML=templateHTML.replace(/tempImgId/g,slideshowImgId);
		templateHTML=templateHTML.replace(/tempThumbPath/g,thumbPath);
		templateHTML=templateHTML.replace(/tempFilename/g,title);
		templateHTML=templateHTML.replace(/tempSlideshowImgId/g,slideshowImgId).replace(/tempHeight/g,imgHeight);
		var imgRow = document.createElement("div");
		imgRow.innerHTML=templateHTML;
		imgRow.id='AmiantImageGalleryBlock-imgRow'+parseInt(slideshowImgId);	
		imgRow.className='AmiantImageGalleryBlock-imgRow';
		document.getElementById('AmiantImageGalleryBlock-imgRows').appendChild(imgRow);
		var bgRow=$('#AmiantImageGalleryBlock-imgRow'+parseInt(fID)+' .backgroundRow');
		bgRow.css('background','url('+thumbPath+') no-repeat left top');
	},
	
	removeImage: function(fID){
		$('#AmiantImageGalleryBlock-imgRow'+fID).remove();
	},
	
	moveUp:function(fID){
		var thisImg=$('#AmiantImageGalleryBlock-imgRow'+fID);
		var qIDs=this.serialize();
		var previousQID=0;
		for(var i=0;i<qIDs.length;i++){
			if(qIDs[i]==fID){
				if(previousQID==0) break; 
				thisImg.after($('#AmiantImageGalleryBlock-imgRow'+previousQID));
				break;
			}
			previousQID=qIDs[i];
		}	 
	},
	moveDown:function(fID){
		var thisImg=$('#AmiantImageGalleryBlock-imgRow'+fID);
		var qIDs=this.serialize();
		var thisQIDfound=0;
		for(var i=0;i<qIDs.length;i++){
			if(qIDs[i]==fID){
				thisQIDfound=1;
				continue;
			}
			if(thisQIDfound){
				$('#AmiantImageGalleryBlock-imgRow'+qIDs[i]).after(thisImg);
				break;
			}
		} 
	},
	serialize:function(){
		var t = document.getElementById("AmiantImageGalleryBlock-imgRows");
		var qIDs=[];
		for(var i=0;i<t.childNodes.length;i++){ 
			if( t.childNodes[i].className && t.childNodes[i].className.indexOf('AmiantImageGalleryBlock-imgRow')>=0 ){ 
				var qID=t.childNodes[i].id.replace('AmiantImageGalleryBlock-imgRow','');
				qIDs.push(qID);
			}
		}
		return qIDs;
	},	

	validate:function(){
		var failed=0;
		var failSource = "";
		var errMessage = "";
		
		if ($("#showAsThumbnails").attr('checked')) {

			if ($("#maxThumbnailsPerPage").val() <= 0) {
				errMessage += ccm_t('set-max-thumbnails-per-page')+"\n";
				failSource = "#maxThumbnailsPerPage";
				failed=1;
			} else if ($("#maxThumbnailWidth").val() <= 0) {
				errMessage += ccm_t('set-max-thumbnail-width')+"\n";
				failSource = "#maxThumbnailWidth";
				failed=1;
			} else if ($("#maxThumbnailHeight").val() <= 0) {
				errMessage += ccm_t('set-max-thumbnail-height')+"\n";
				failSource = "#maxThumbnailHeight";
				failed=1;
			}
			
			if ($("#maxThumbnailsPerPage").val() > 50) {
				errMessage += ccm_t('max-thumbnails-per-page-limit')+"\n";
				failSource = "#maxThumbnailsPerPage";
				failed=1;
			} else if (($("#maxThumbnailWidth").val() < 70) && ($("#maxThumbnailWidth").val() > 0)){
				errMessage += ccm_t('max-thumbnail-width-limit')+"\n";
				failSource = "#maxThumbnailWidth";
				failed=1;
			} else if (($("#maxThumbnailHeight").val() < 70) && ($("#maxThumbnailHeight").val() > 0)){
				errMessage += ccm_t('max-thumbnail-height-limit')+"\n";
				failSource = "#maxThumbnailHeight";
				failed=1;
			}
			
			if (failed == 1) {
				// switch to the thumbnail options tab
				ccm_fpActiveTab = $("#ccm-gallery-tabs li.ccm-nav-active a").attr("id");
				$("#ccm-gallery-tabs li.ccm-nav-active").removeClass('ccm-nav-active');
				$("#" + ccm_fpActiveTab + "-tab").hide();
				ccm_fpActiveTab = 'ccm-thumbnail-options';
				$("#" + ccm_fpActiveTab).parent().addClass("ccm-nav-active");
				$("#" + ccm_fpActiveTab + "-tab").show();
				$(failSource).focus();
			}
		}
		
		if ($("#displayThumbnailBubblePopup").attr('checked')) {
			if ($("#thumbnailPopupBubbleOptions input:checked").length < 1) {
				errMessage += ccm_t('set-item-to-show-in-pop-up-bubble')+"\n";
				failed=1;
			}
			
			if (failed == 1) {
				// switch to the thumbnail options tab
				ccm_fpActiveTab = $("#ccm-gallery-tabs li.ccm-nav-active a").attr("id");
				$("#ccm-gallery-tabs li.ccm-nav-active").removeClass('ccm-nav-active');
				$("#" + ccm_fpActiveTab + "-tab").hide();
				ccm_fpActiveTab = 'ccm-thumbnail-options';
				$("#" + ccm_fpActiveTab).parent().addClass("ccm-nav-active");
				$("#" + ccm_fpActiveTab + "-tab").show();
			}
		}
		
		if ($("#displaySlideInformation").attr('checked')) {
			if ($("#slideInformationOptions input:checked").length < 1) {
				errMessage += ccm_t('set-item-to-show-in-slide-information')+"\n";
				failed=1;
			}
			
			if (failed == 1) {
				// switch to the thumbnail options tab
				ccm_fpActiveTab = $("#ccm-gallery-tabs li.ccm-nav-active a").attr("id");
				$("#ccm-gallery-tabs li.ccm-nav-active").removeClass('ccm-nav-active');
				$("#" + ccm_fpActiveTab + "-tab").hide();
				ccm_fpActiveTab = 'ccm-slide-options';
				$("#" + ccm_fpActiveTab).parent().addClass("ccm-nav-active");
				$("#" + ccm_fpActiveTab + "-tab").show();
			}
		}

		if ($("#enableWatermark").attr('checked')) {
			if ($("#watermarkFile-fm-value").val() <= 0) {
				errMessage += ccm_t('set-watermark-image-file')+"\n";
				failed=1;
			}

			if (failed == 1) {
				ccm_fpActiveTab = $("#ccm-gallery-tabs li.ccm-nav-active a").attr("id");
				$("#ccm-gallery-tabs li.ccm-nav-active").removeClass('ccm-nav-active');
				$("#" + ccm_fpActiveTab + "-tab").hide();
				ccm_fpActiveTab = 'ccm-slide-options';
				$("#" + ccm_fpActiveTab).parent().addClass("ccm-nav-active");
				$("#" + ccm_fpActiveTab + "-tab").show();
			}
		}
		
		if ($("#enableZoomMode").attr('checked')) {
			var failSource = "";

			if ($("#zoomModeEffectSpeed").val() <= 0) {
				errMessage += ccm_t('set-zoom-mode-effect-speed')+"\n";
				failSource = "#zoomModeEffectSpeed";
				failed=1;
			}
			
			if ($("#zoomModeMaxWidth").val() <= 0) {
				errMessage += ccm_t('set-zoom-mode-max-width')+"\n";
				failSource = "#zoomModeMaxWidth";
				failed=1;
			}
			
			if ($("#zoomModeMaxHeight").val() <= 0) {
				errMessage += ccm_t('set-zoom-mode-max-height')+"\n";
				failSource = "#zoomModeMaxHeight";
				failed=1;
			}
			
			if ($("#zoomModeDisplayInformation").attr('checked')) {
				if ($("#zoom-mode-image-information-settings input:checked").length < 1) {
					errMessage += ccm_t('set-item-to-show-in-zoom-mode-information')+"\n";
					failSource = "#zoomModeDisplayInformation";
					failed=1;
				}
			}
			
			if (failed == 1) {
				// switch to the zoom mode options tab
				ccm_fpActiveTab = $("#ccm-gallery-tabs li.ccm-nav-active a").attr("id");
				$("#ccm-gallery-tabs li.ccm-nav-active").removeClass('ccm-nav-active');
				$("#" + ccm_fpActiveTab + "-tab").hide();
				ccm_fpActiveTab = 'ccm-zoom-options';
				$("#" + ccm_fpActiveTab).parent().addClass("ccm-nav-active");
				$("#" + ccm_fpActiveTab + "-tab").show();
				$(failSource).focus();
			}
		}
		
		if ($("#AmiantImageGalleryOptions input[name=width]").val() <= 0 || $("#AmiantImageGalleryOptions input[name=height]").val() <= 0) {
		
			// switch to the gallery options tab
			ccm_fpActiveTab = $("#ccm-gallery-tabs li.ccm-nav-active a").attr("id");
			$("#ccm-gallery-tabs li.ccm-nav-active").removeClass('ccm-nav-active');
			
			$("#" + ccm_fpActiveTab + "-tab").hide();
			ccm_fpActiveTab = 'ccm-gallery-options';
			$("#" + ccm_fpActiveTab).parent().addClass("ccm-nav-active");
			$("#" + ccm_fpActiveTab + "-tab").show();
				
			errMessage += ccm_t('set-size')+"\n";
			if ($("#AmiantImageGalleryOptions input[name=width]").val() <= 0){
				$("#AmiantImageGalleryOptions input[name=width]").focus();
			} else {
				$("#AmiantImageGalleryOptions input[name=height]").focus();
			}
			failed=1;
		}

		if ($("#AmiantImageGallerySlideOptions input[name=maxSlideWidth]").val() <= 0 || $("#AmiantImageGallerySlideOptions input[name=maxSlideHidth]").val() <= 0) {
			ccm_fpActiveTab = $("#ccm-gallery-tabs li.ccm-nav-active a").attr("id");
			$("#ccm-gallery-tabs li.ccm-nav-active").removeClass('ccm-nav-active');
			$("#" + ccm_fpActiveTab + "-tab").hide();
			ccm_fpActiveTab = 'ccm-slide-options';
			$("#" + ccm_fpActiveTab).parent().addClass("ccm-nav-active");
			$("#" + ccm_fpActiveTab + "-tab").show();

			errMessage += ccm_t('set-slide-size')+"\n";
			if ($("#AmiantImageGallerySlideOptions input[name=maxSlideWidth]").val() <= 0){
				$("#AmiantImageGallerySlideOptions input[name=maxSlideWidth]").focus();
			} else {
				$("#AmiantImageGallerySlideOptions input[name=maxSlideHeight]").focus();
			}
			failed=1;
		}
		
		if ($("#newImg select[name=type]").val() == 'FILESET')
		{
			if ($("#AmiantImageGalleryBlock-fsRow input[name=fsID]").val() <= 0) {
				errMessage += ccm_t('choose-fileset')+"\n";
				$('#AmiantImageGalleryBlock-AddImg').focus();
				failed=1;
				
				ccm_fpActiveTab = $("#ccm-gallery-tabs li.ccm-nav-active a").attr("id");
				$("#ccm-gallery-tabs li.ccm-nav-active").removeClass('ccm-nav-active');
			
				$("#" + ccm_fpActiveTab + "-tab").hide();
				ccm_fpActiveTab = 'ccm-gallery-type';
				$("#" + ccm_fpActiveTab).parent().addClass("ccm-nav-active");
				$("#" + ccm_fpActiveTab + "-tab").show();
			}	
		} else {
			qIDs=this.serialize();
			if( qIDs.length<2 ){
				errMessage += ccm_t('choose-min-2')+"\n";
				$('#AmiantImageGalleryBlock-AddImg').focus();
				failed=1;
				
				ccm_fpActiveTab = $("#ccm-gallery-tabs li.ccm-nav-active a").attr("id");
				$("#ccm-gallery-tabs li.ccm-nav-active").removeClass('ccm-nav-active');
			
				$("#" + ccm_fpActiveTab + "-tab").hide();
				ccm_fpActiveTab = 'ccm-gallery-type';
				$("#" + ccm_fpActiveTab).parent().addClass("ccm-nav-active");
				$("#" + ccm_fpActiveTab + "-tab").show();
			}	
		}
		
		if (failed == 1) {
			ccm_isBlockError = 1;
			alert("Please correct following issues:\n\n"+errMessage);
			return false;
		}
		return true;
	} 
}

ccmValidateBlockForm = function() { return AmiantImageGalleryBlock.validate(); }
ccm_chooseAsset = function(obj) { AmiantImageGalleryBlock.selectObj(obj); }

$(function() {
	if ($("#newImg select[name=type]").val() == 'FILESET') {
		$("#newImg select[name=type]").val('FILESET');
		AmiantImageGalleryBlock.showFileSet();
	} else {
		$("#newImg select[name=type]").val('CUSTOM');
		AmiantImageGalleryBlock.showImages();
	}

	$("#newImg select[name=type]").change(function(){
		if (this.value == 'FILESET') {
			AmiantImageGalleryBlock.showFileSet();
		} else {
			AmiantImageGalleryBlock.showImages();
		}
	});
});

