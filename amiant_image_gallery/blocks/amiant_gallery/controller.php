<?php 

/**
 * @package Amiant Image Gallery
 * @author Count Raven Amiant <cravener@gmail.com>
 * @copyright  Copyright (c) 2010-2012 Count Raven Amiant. (http://countraven.blogspot.com)
 * @license    http://www.gnu.org/licenses/gpl-2.0.html     GNU GPL Version 2
 *
 */

/**
 * An object used by the Amiant Image Gallery Block to load and processing images
 *
 * @package Amiant Image Gallery
 * @author Count Raven Amiant <cravener@gmail.com>
 * @copyright  Copyright (c) 2010-2012 Count Raven Amiant. (http://countraven.blogspot.com)
 * @license    http://www.gnu.org/licenses/gpl-2.0.html     GNU GPL Version 2
 *
 */
 
defined('C5_EXECUTE') or die(_("Access Denied."));

class AmiantGalleryBlockController extends BlockController {

    protected $btTable = 'btAmiantGallery';
    protected $btInterfaceWidth = "850";
    protected $btInterfaceHeight = "500";
    protected $btWrapperClass = 'ccm-ui';
    
    protected $productVersion = "0.7.1-20121217";
    
    // array of transitions for jQuery Cycle
    protected $transitions = array('0' => 'blindX',
        '1' => 'blindY',
        '2' => 'blindZ',
        '3' => 'cover',
        '4' => 'curtainX',
        '5' => 'curtainY',
        '6' => 'fade',
        '7' => 'fadeZoom',
        '8' => 'growX',
        '9' => 'growY',
        '10' => 'none',
        '11' => 'scrollUp',
        '12' => 'scrollDown',
        '13' => 'scrollLeft',
        '14' => 'scrollRight',
        '15' => 'scrollHorz',
        '16' => 'scrollVert',
        '17' => 'shuffle',
        '18' => 'slideX',
        '19' => 'slideY',
        '20' => 'toss',
        '21' => 'turnUp',
        '22' => 'turnDown',
        '23' => 'turnLeft',
        '24' => 'turnRight',
        '25' => 'uncover',
        '26' => 'wipe',
        '27' => 'zoom');
       
    // Arrays of jQuery FancyBox parameters
    protected $zoomModeEffects = array('0' => 'none', '1' => 'fade', '2' => 'elastic');
    protected $zoomModeInfoPosition = array('0' => 'inside', '1' => 'outside', '2' => 'over');

    public function getBlockTypeDescription() {
        return t("Display an images with various ways.");
    }

    public function getBlockTypeName() {
        return t("Amiant Image Gallery");
    }
    
    /**
	* Returns the Version of the Amiant Image Gallery Block
	* @return string
	*/ 
    public function getProductVersion() {
		return $this->productVersion;
	}

	// Needed for translating of JavaScript Strings
    public function getJavaScriptStrings() {
        return array(
            'choose-file' => t('Choose Image/File'),
            'choose-min-2' => t('Please choose at least two images.'),
            'choose-fileset' => t('Please choose a file set.'),
            'set-size' => t('Please set the width and height of block container.'),
            'set-max-thumbnails-per-page' => t('Please set the maximum thumbnails count per page.'),
            'set-max-thumbnail-width' => t('Please set the maximum thumbnail width.'),
            'set-max-thumbnail-height' => t('Please set the maximum thumbnail height.'),
            'set-item-to-show-in-pop-up-bubble' => t('Please select at least one item to display in the pop-up bubble, or disable this option.'),
            'max-thumbnails-per-page-limit' => t('Maximum number of thumbnails on a page should be not more than 50.'),
            'max-thumbnail-width-limit' => t('Maximum width of thumbnail should be not less than 70.'),
            'max-thumbnail-height-limit' => t('Maximum height of thumbnail should be not less than 70.'),
            'max-thumbnails-per-page-only-numbers' => t('The Maximum Thumbnails per Page must contain only digits.'),
            'max-thumbnail-width-only-numbers' => t('The Maximum Thumbnail Width must contain only digits.'),
            'max-thumbnail-height-only-numbers' => t('The Maximum Thumbnail Height must contain only digits.'),
            'set-item-to-show-in-slide-information' => t('Please select at least one item to display in the slide information area, or disable this option.'),
            'set-zoom-mode-effect-speed' => t('Please set the effect speed in milliseconds for Zoom Mode.'),
            'set-zoom-mode-max-width' => t('Please set the maximum image width for Zoom Mode.'),
            'set-zoom-mode-max-height' => t('Please set the maximum image height for Zoom Mode.'),
            'set-item-to-show-in-zoom-mode-information' => t('Please select at least one item to display in the Zoom Mode information area, or disable this option.'),
            'set-watermark-image-file' => t('Please choose image for watermark or disable this option.'),
            'set-slide-size' => t('Please set maximum width and height for slides.')
        );
    }

    function __construct($obj = null) {
        parent::__construct($obj);

        $this->db = Loader::db();
        if ($this->fsID == 0) {
            $this->loadImagesForThumbnails();
            if ((isset($_REQUEST['aigid'.$this->bID])) || (!$this->showAsThumbnails))
                $this->loadImagesForFullView();
        } else {
            $this->loadFileSetForThumbnails();
            if ((isset($_REQUEST['aigid'.$this->bID])) || (!$this->showAsThumbnails))
                $this->loadFileSetForFullView();
        }
        
        $this->set('fsID', $this->fsID);
        $this->set('fsName', $this->getFileSetName());
        $this->set('images', $this->images);
        $this->set('imagesForThumbnails', $this->imagesForThumbnails);
        $this->set('imagesForFullView', $this->imagesForFullView);
        $type = ($this->fsID > 0) ? 'FILESET' : 'CUSTOM';
        $this->set('type', $type);
        $this->set('bID', $this->bID);
        $this->set('width', $this->width);
        $this->set('widthInUnits', $this->widthInUnits);
        $this->set('height', $this->height);
        $this->set('title', $this->title);
        $this->set('autoSlide', $this->autoSlide);
        $this->set('pauseOnMouseHover', $this->pauseOnMouseHover);
        $this->set('pause', ($this->pause) ? $this->pause : 2000);
        $this->set('continuous', $this->continuous);
        $this->set('transitionFX', $this->transitionFX);
        $this->set('speed', ($this->speed) ? $this->speed : 800);

        $this->set('showAsThumbnails', $this->showAsThumbnails);
        $this->set('enableZoomMode', $this->enableZoomMode);
        $this->set('maxThumbnailWidth', $this->maxThumbnailWidth);
        $this->set('maxThumbnailHeight', $this->maxThumbnailHeight);
        $this->set('maxThumbnailsPerPage', $this->maxThumbnailsPerPage);
        $this->set('cropToFillThumbnail', $this->cropToFillThumbnail);

        $this->set('alignSlideToCenter', $this->alignSlideToCenter);
        $this->set('displaySlideInformation', $this->displaySlideInformation);
        $this->set('displaySlideCaption', $this->displaySlideCaption);
        $this->set('displaySlideFileName', $this->displaySlideFileName);
        $this->set('displaySlideFileType', $this->displaySlideFileType);
        $this->set('displaySlideFileSize', $this->displaySlideFileSize);
        $this->set('displaySlideDownloadLink', $this->displaySlideDownloadLink);
        $this->set('enableSlidesPager', $this->enableSlidesPager);

		$this->set('enableWatermark', $this->enableWatermark);
		$this->set('fIDWatermark', $this->fIDWatermark);

        $this->set('maxSlideHeight', $this->maxSlideHeight);
        $this->set('maxSlideWidth', $this->maxSlideWidth);
        
        $this->set('zoomModeEffect', $this->zoomModeEffect);
        $this->set('zoomModeEffectSpeed', $this->zoomModeEffectSpeed);
        $this->set('zoomModeShowCloseButton', $this->zoomModeShowCloseButton);
        $this->set('zoomModeShowNavArrows', $this->zoomModeShowNavArrows);
        $this->set('zoomModeEnableEscButton', $this->zoomModeEnableEscButton);
        $this->set('zoomModeMaxWidth', $this->zoomModeMaxWidth);
        $this->set('zoomModeMaxHeight', $this->zoomModeMaxHeight);
        $this->set('zoomModeDisplayInformation', $this->zoomModeDisplayInformation);
        $this->set('zoomModeDisplayCaption', $this->zoomModeDisplayCaption);
        $this->set('zoomModeInformationPosition', $this->zoomModeInformationPosition);
        $this->set('zoomModeShowOverlay', $this->zoomModeShowOverlay);
        $this->set('zoomModeOverlayOpacity', $this->zoomModeOverlayOpacity);
        $this->set('zoomModeOverlayColor', $this->zoomModeOverlayColor);
        $this->set('zoomModeHideOnOverlayClick', $this->zoomModeHideOnOverlayClick);
        $this->set('zoomModeHideOnContentClick', $this->zoomModeHideOnContentClick);
        $this->set('zoomModeCenterOnScroll', $this->zoomModeCenterOnScroll);
        $this->set('zoomModeCyclic', $this->zoomModeCyclic);
        $this->set('zoomModeAutoScale', $this->zoomModeAutoScale);
        $this->set('zoomModeDisplayImageIndex', $this->zoomModeDisplayImageIndex);
        
    }

    function view() {
        
    }

    public function on_page_view() {

        $html = Loader::helper('html');
        $b = $this->getBlockObject();
        $bv = new BlockView();
        $bv->setBlockObject($b);
		$this->addHeaderItem('<link rel="stylesheet" type="text/css" href="' . $bv->getBlockURL() . '/jquery.fancybox-1.3.4.css" />');
		$this->addHeaderItem('<script type="text/javascript" src="' . $bv->getBlockURL() . '/jquery.fancybox-1.3.4.pack.js"></script>');
		$this->addHeaderItem('<script type="text/javascript" src="' . $bv->getBlockURL() . '/jquery.cycle.all.min.js"></script>');
    }

	/**
	* Returns the Name of a File Set for Images
	* @return string
	*/ 
    function getFileSetName() {
        $sql = "SELECT fsName FROM FileSets WHERE fsID=" . intval($this->fsID);
        return $this->db->getOne($sql);
    }

    /**
	* Loads File Set for Thumbnails View and create an array with information about images
	* @return array
	*/ 
    function loadFileSetForThumbnails() {
        if (intval($this->fsID) < 1) {
            return false;
        }

        Loader::helper('concrete/file');
        Loader::model('file_attributes');
        Loader::library('file/types');
        Loader::model('file_list');
        Loader::model('file_set');


        $paginator = Loader::helper('pagination');
        $paginator->queryStringPagingVariable = "aig_p".$this->bID;
        $pageBase = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        if (empty($_REQUEST["aig_p".$this->bID]))
            $page = 1;
        else
            $page = $_REQUEST["aig_p".$this->bID];

        $akUrl = FileAttributeKey::getByHandle('link_url');
        $akCaption = FileAttributeKey::getByHandle('image_caption');

        $fs = FileSet::getByID($this->fsID);
        $fileList = new FileList();
        $fileList->filterBySet($fs);
        $fileList->filterByType(FileType::T_IMAGE);
        $fileList->sortByFileSetDisplayOrder();

        $paginator->init(intval($page), $fileList->getTotal(), $pageBase, intval($this->maxThumbnailsPerPage));
        $this->set('paginator', $paginator);

        $files = $fileList->get($this->maxThumbnailsPerPage, (intval($page) - 1) * intval($this->maxThumbnailsPerPage));


        $image = array();
        $image['groupSet'] = 0;
        $image['url'] = '';
        $image['caption'] = '';
        $images = array();
        $maxHeight = 0;
        foreach ($files as $f) {
            $fp = new Permissions($f);
            if (!$fp->canRead()) {
                continue;
            }
            $image['fID'] = $f->getFileID();
            $image['fileName'] = $f->getFileName();
            $image['fullFilePath'] = $f->getPath();
            //$image['url']			= $f->getRelativePath();

            $fv = $f->getApprovedVersion();

            if ($akUrl) {
                $vo = $f->getAttributeValueObject($akUrl);
                if (is_object($vo)) {
                    $image['url'] = $vo->getValue('url');
                }
            }

            if ($akCaption) {
                $vo = $f->getAttributeValueObject($akCaption);
                if (is_object($vo)) {
                    $image['caption'] = $vo->getValue('caption');
                } else {
                    $image['caption'] = $fv->getDescription();
                }
            }

            $image['size'] = $fv->getSize();
            $image['type'] = $fv->getType();

            $images[] = $image;

            $image['url'] = '';
            $image['caption'] = '';
        }

        $this->images = $images;
        $this->imagesForThumbnails = $images;
    }

	/**
	* Loads File Set for Slide View and create an array with information about images
	* @return array
	*/ 
    function loadFileSetForFullView() {
        if (intval($this->fsID) < 1) {
            return false;
        }

        Loader::helper('concrete/file');
        Loader::model('file_attributes');
        Loader::library('file/types');
        Loader::model('file_list');
        Loader::model('file_set');


        $akUrl = FileAttributeKey::getByHandle('link_url');
        $akCaption = FileAttributeKey::getByHandle('image_caption');

        $fs = FileSet::getByID($this->fsID);
        $fileList = new FileList();
        $fileList->filterBySet($fs);
        $fileList->filterByType(FileType::T_IMAGE);
        $fileList->sortByFileSetDisplayOrder();


        $files = $fileList->get(1000, 0);

        $image = array();
        $image['groupSet'] = 0;
        $image['url'] = '';
        $image['caption'] = '';
        $images = array();
        $maxHeight = 0;
        foreach ($files as $f) {
            $fp = new Permissions($f);
            if (!$fp->canRead()) {
                continue;
            }
            $image['fID'] = $f->getFileID();
            $image['fileName'] = $f->getFileName();
            $image['fullFilePath'] = $f->getPath();
            //$image['url']			= $f->getRelativePath();

            $fv = $f->getApprovedVersion();

            if ($akUrl) {
                $vo = $f->getAttributeValueObject($akUrl);
                if (is_object($vo)) {
                    $image['url'] = $vo->getValue('url');
                }
            }

            if ($akCaption) {
                $vo = $f->getAttributeValueObject($akCaption);
                if (is_object($vo)) {
                    $image['caption'] = $vo->getValue('caption');
                } else {
                    $image['caption'] = $fv->getDescription();
                }
            }

            $image['size'] = $fv->getSize();
            $image['type'] = $fv->getType();

            $images[] = $image;

            $image['url'] = '';
            $image['caption'] = '';
        }

        $this->images = $images;
        $this->imagesForFullView = $images;
    }

	/**
	* Loads Images for Thumbnails View and create an array with information about images
	* @return array
	*/ 
    function loadImagesForThumbnails() {
        if (intval($this->bID) == 0) {
            $this->images = array();
            return array();
        }

        $sql = "SELECT * FROM btAmiantGalleryImg WHERE bID=" . intval($this->bID) . ' ORDER BY position';
        $imgs = $this->db->getAll($sql);

        $this->images = $imgs;

        $paginator = Loader::helper('pagination');
        $paginator->queryStringPagingVariable = "aig_p".$this->bID;
        $pageBase = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        if (empty($_REQUEST["aig_p".$this->bID]))
            $page = 1;
        else
            $page = $_REQUEST["aig_p".$this->bID];

        $paginator->init(intval($page), count($imgs), $pageBase, intval($this->maxThumbnailsPerPage));
        $this->set('paginator', $paginator);

        $imgs = array_slice($imgs, (intval($page) - 1) * intval($this->maxThumbnailsPerPage), $this->maxThumbnailsPerPage);


        $image = array();
        $images = array();

        foreach ($imgs as $img) {
            $f = File::getByID($img['fID']);

            $fp = new Permissions($f);
            if (!$fp->canRead()) {
                continue;
            }
            $image['fID'] = $f->getFileID();
            $image['fileName'] = $f->getFileName();
            $image['fullFilePath'] = $f->getPath();

            $fv = $f->getApprovedVersion();

            $image['size'] = $fv->getSize();
            $image['type'] = $fv->getType();

            $image['url'] = $img['url'];
            $image['caption'] = $img['caption'];

            $images[] = $image;
        }

        $this->imagesForThumbnails = $images;
    }

	/**
	* Loads Images for Slide View and create an array with information about images
	* @return array
	*/ 
    function loadImagesForFullView() {
        if (intval($this->bID) == 0) {
            $this->images = array();
            return array();
        }

        $sql = "SELECT * FROM btAmiantGalleryImg WHERE bID=" . intval($this->bID) . ' ORDER BY position';
        $imgs = $this->db->getAll($sql);

        $this->images = $imgs;

        $image = array();
        $images = array();

        foreach ($imgs as $img) {
            $f = File::getByID($img['fID']);

            $fp = new Permissions($f);
            if (!$fp->canRead()) {
                continue;
            }
            $image['fID'] = $f->getFileID();
            $image['fileName'] = $f->getFileName();
            $image['fullFilePath'] = $f->getPath();

            $fv = $f->getApprovedVersion();

            $image['size'] = $fv->getSize();
            $image['type'] = $fv->getType();

            $image['url'] = $img['url'];
            $image['caption'] = $img['caption'];

            $images[] = $image;
        }

        $this->imagesForFullView = $images;
    }

	/**
	* Returns Name of the Effect for jQuery Cycle Plugin
	* @return string
	*/ 
    public function getTransitionFX($transitionID) {
        return $this->transitions[$transitionID];
    }
    
    /**
	* Returns Name of the Effect for jQuery FancyBox Plugin
	* @return string
	*/ 
    public function getZoomModeFX($fxID) {
        return $this->zoomModeEffects[$fxID];
    }
    
	/**
	* Returns Name of the Position of Information about Image for jQuery FancyBox Plugin
	* @return string
	*/ 
    public function getZoomModeInfoPosition($posID) {
        return $this->zoomModeInfoPosition[$posID];
    }

    function delete() {
        $this->db->query("DELETE FROM btAmiantGalleryImg WHERE bID=" . intval($this->bID));
        parent::delete();
    }

    function save($data) {
		$txt = Loader::helper('text');
		
        $args['title'] = $data['title'];
        $args['height'] = intval($data['height']);
        $args['width'] = intval($data['width']);
        $args['widthInUnits'] = intval($data['widthInUnits']);
        $args['autoSlide'] = intval($data['autoSlide']);
        $args['pauseOnMouseHover'] = intval($data['pauseOnMouseHover']);
        $args['pause'] = intval($data['pause'] * 1000);
        $args['continuous'] = intval($data['continuous']);

        $args['transitionFX'] = intval($data['transitionFX']);

        $args['speed'] = intval($data['speed']);

        $args['showAsThumbnails'] = intval($data['showAsThumbnails']);
        $args['enableZoomMode'] = intval($data['enableZoomMode']);
        $args['maxThumbnailWidth'] = intval($data['maxThumbnailWidth']);
        $args['maxThumbnailHeight'] = intval($data['maxThumbnailHeight']);
        $args['maxThumbnailsPerPage'] = intval($data['maxThumbnailsPerPage']);

        $args['addThumbnailTitleAttr'] = intval($data['addThumbnailTitleAttr']);
		$args['cropToFillThumbnail'] = intval($data['cropToFillThumbnail']);
        $args['displayThumbnailBubblePopup'] = intval($data['displayThumbnailBubblePopup']);
        $args['displayThumbnailCaption'] = intval($data['displayThumbnailCaption']);
        $args['displayThumbnailFileName'] = intval($data['displayThumbnailFileName']);
        $args['displayThumbnailFileType'] = intval($data['displayThumbnailFileType']);
        $args['displayThumbnailFileSize'] = intval($data['displayThumbnailFileSize']);
        $args['displayThumbnailDownloadLink'] = intval($data['displayThumbnailDownloadLink']);

        $args['alignSlideToCenter'] = intval($data['alignSlideToCenter']);
        $args['displaySlideInformation'] = intval($data['displaySlideInformation']);
        $args['displaySlideCaption'] = intval($data['displaySlideCaption']);
        $args['displaySlideFileName'] = intval($data['displaySlideFileName']);
        $args['displaySlideFileType'] = intval($data['displaySlideFileType']);
        $args['displaySlideFileSize'] = intval($data['displaySlideFileSize']);
        $args['displaySlideDownloadLink'] = intval($data['displaySlideDownloadLink']);
        $args['enableSlidesPager'] = intval($data['enableSlidesPager']);

		$args['enableWatermark'] = intval($data['enableWatermark']);
        $args['fIDWatermark'] = intval($data['fIDWatermark']);

        $args['maxSlideHeight'] = intval($data['maxSlideHeight']);
        $args['maxSlideWidth'] = intval($data['maxSlideWidth']);

		$args['zoomModeEffect'] = intval($data['zoomModeEffect']);
		$args['zoomModeEffectSpeed'] = intval($data['zoomModeEffectSpeed']);
		$args['zoomModeShowCloseButton'] = intval($data['zoomModeShowCloseButton']);
		$args['zoomModeShowNavArrows'] = intval($data['zoomModeShowNavArrows']);
		$args['zoomModeEnableEscButton'] = intval($data['zoomModeEnableEscButton']);
		$args['zoomModeMaxWidth'] = intval($data['zoomModeMaxWidth']);
		$args['zoomModeMaxHeight'] = intval($data['zoomModeMaxHeight']);
		$args['zoomModeDisplayInformation'] = intval($data['zoomModeDisplayInformation']);
		$args['zoomModeDisplayCaption'] = intval($data['zoomModeDisplayCaption']);
		$args['zoomModeDisplayImageIndex'] = intval($data['zoomModeDisplayImageIndex']);
		$args['zoomModeInformationPosition'] = intval($data['zoomModeInformationPosition']);
		$args['zoomModeShowOverlay'] = intval($data['zoomModeShowOverlay']);
		$args['zoomModeOverlayOpacity'] = $txt->sanitize($data['zoomModeOverlayOpacity']);
		$args['zoomModeOverlayColor'] = $txt->sanitize($data['zoomModeOverlayColor']);
		$args['zoomModeHideOnOverlayClick'] = intval($data['zoomModeHideOnOverlayClick']);
		$args['zoomModeHideOnContentClick'] = intval($data['zoomModeHideOnContentClick']);
		$args['zoomModeCenterOnScroll'] = intval($data['zoomModeCenterOnScroll']);
		$args['zoomModeCyclic'] = intval($data['zoomModeCyclic']);
		$args['zoomModeAutoScale'] = intval($data['zoomModeAutoScale']);
		

        if ($data['type'] == 'FILESET' && $data['fsID'] > 0) {
            $args['fsID'] = $data['fsID'];

            $files = $this->db->getAll("SELECT fv.fID FROM FileSetFiles fsf, FileVersions fv WHERE fsf.fsID = " . $data['fsID'] .
                            " AND fsf.fID = fv.fID AND fvIsApproved = 1");

            //delete existing images
            $this->db->query("DELETE FROM btAmiantGalleryImg WHERE bID=" . intval($this->bID));
        } else if ($data['type'] == 'CUSTOM' && count($data['imgFIDs'])) {
            $args['fsID'] = 0;

            //delete existing images
            $this->db->query("DELETE FROM btAmiantGalleryImg WHERE bID=" . intval($this->bID));

            //loop through and add the images
            $pos = 0;
            foreach ($data['imgFIDs'] as $imgFID) {
                if ($data['caption'][$pos] == '') {
                    // get the description to show as caption
                    $fileObj = File::getByID(intval($imgFID));
                    $fVersion = $fileObj->getVersion();
                    $data['caption'][$pos] = $fVersion->getDescription();
                }

                if (intval($imgFID) == 0 || $data['fileNames'][$pos] == 'tempFilename')
                    continue;
                $vals = array(intval($this->bID), intval($imgFID), trim($data['url'][$pos]), intval($data['imgHeight'][$pos]), $pos, $data['caption'][$pos]);
                $this->db->query("INSERT INTO btAmiantGalleryImg (bID,fID,url,imgHeight,position,caption) values (?,?,?,?,?,?)", $vals);
                $pos++;
            }
        }

        $ip = Loader::helper('imageprocessor', 'amiant_image_gallery');
        $ip->clearImageCache();

        parent::save($args);
    }

}

?>
