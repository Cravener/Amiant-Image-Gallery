<?php 
/**
 * @package Amiant Image Gallery
 * @author Count Raven Amiant <cravener@gmail.com>
 * @copyright  Copyright (c) 2010-2013 Amiant Web Development Solutions. (http://amiant-dev.ru/)
 * @license    http://www.gnu.org/licenses/gpl-2.0.html     GNU GPL Version 2
 */

/**
 * An object used by the Amiant Image Gallery Block to load and processing images
 *
 * @package Amiant Image Gallery
 * @author Count Raven Amiant <cravener@gmail.com>
 * @copyright  Copyright (c) 2010-2014 Amiant Web Development Solutions. (http://amiant-dev.ru/)
 * @license    http://www.gnu.org/licenses/gpl-2.0.html     GNU GPL Version 2
 *
 */

 
defined('C5_EXECUTE') or die(_("Access Denied."));
class ImageprocessorHelper {

	private $cache_dir;

	public function __construct() {
		$this->cache_dir = DIR_FILES_CACHE.'/aig';
	}

	public function resizeAndCrop($prefix, $file, $width, $height) {
		Loader::library('3rdparty/zebra_image/zebra_image', 'amiant_image_gallery');

		$image = new Zebra_Image();
		$image->jpeg_quality = 100;

		$fp = new Permissions($file);
		if (!$fp->canRead()) {
			return false;
		}

		$fv = $file->getApprovedVersion();
		$ext = $fv->getExtension();
		$fID = $fv->getFileID();

		if ($this->checkImageCache()) {
			$cache_dir = $this->cache_dir;
		} else {
			return false;
		}
		
		if (file_exists($cache_dir.'/aig_'.$prefix.'_cropped_'.$fID.'_'.$width.'x'.$height.'.'.$ext)) {
			return BASE_URL.DIR_REL.'/files/cache/aig/aig_'.$prefix.'_cropped_'.$fID.'_'.$width.'x'.$height.'.'.$ext;
		} else {
			$image->source_path = $fv->getPath();
			$image->target_path = $cache_dir.'/aig_'.$prefix.'_cropped_'.$fID.'_'.$width.'x'.$height.'.'.$ext;
			
			if (!$image->resize($width, $height, ZEBRA_IMAGE_CROP_CENTER, -1)) return false;
			
			return BASE_URL.DIR_REL.'/files/cache/aig/aig_'.$prefix.'_cropped_'.$fID.'_'.$width.'x'.$height.'.'.$ext;
		}
	}

	public function resizeAndWatermark($prefix, $file, $watermark_file, $width, $height) {
		Loader::library('3rdparty/zebra_image/zebra_image', 'amiant_image_gallery');

		$image = new Zebra_Image();
		$image->jpeg_quality = 100;

		$fp = new Permissions($file);
		if (!$fp->canRead()) {
			return false;
		}

		$fv = $file->getApprovedVersion();
		$ext = $fv->getExtension();
		$fID = $fv->getFileID();

		if ($this->checkImageCache()) {
			$cache_dir = $this->cache_dir;
		} else {
			return false;
		}
		
		if (file_exists($cache_dir.'/aig_'.$prefix.'_watermark_'.$fID.'_'.$width.'x'.$height.'.'.$ext)) {
			return BASE_URL.DIR_REL.'/files/cache/aig/aig_'.$prefix.'_watermark_'.$fID.'_'.$width.'x'.$height.'.'.$ext;
		} else {
			$image->source_path = $fv->getPath();
			$image->target_path = $cache_dir.'/aig_'.$prefix.'_resized_'.$fID.'_'.$width.'x'.$height.'.'.$ext;
			if (!$image->resize($width, $height, ZEBRA_IMAGE_NOT_BOXED, -1)) return false;
			
			$image->source_path = $cache_dir.'/aig_'.$prefix.'_resized_'.$fID.'_'.$width.'x'.$height.'.'.$ext;
			$image->target_path = $cache_dir.'/aig_'.$prefix.'_watermark_'.$fID.'_'.$width.'x'.$height.'.'.$ext;
			if (!$image->watermark($watermark_file, 35)) return false;
			
			return BASE_URL.DIR_REL.'/files/cache/aig/aig_'.$prefix.'_watermark_'.$fID.'_'.$width.'x'.$height.'.'.$ext;
		}
	}

	public function checkImageCache() {
		if (!is_dir($this->cache_dir)) {
			if (!mkdir($this->cache_dir, 0755, true)) {
				return false;
			}
		}
		return true;
	}
	
	public function clearImageCache() {
		if (is_dir($this->cache_dir)) {
			$pattern = $this->cache_dir.'/aig_*.*';

			$imageCache = glob($pattern);
			
			if ((count($imageCache) > 0) && (is_array($imageCache))) {
				foreach ($imageCache as $imageFile) {
					@unlink($imageFile);
				}
			}
		}
	}
	
}

?>
