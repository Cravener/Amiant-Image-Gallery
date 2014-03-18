<?php   

/**
 * @package Amiant Image Gallery
 * @author Count Raven Amiant <cravener@gmail.com>
 * @copyright  Copyright (c) 2010-2013 Amiant Web Development Solutions. (http://amiant-dev.ru/)
 * @license    http://www.gnu.org/licenses/gpl-2.0.html     GNU GPL Version 2
 *
 */

/**
 * An object used by the Amiant Image Gallery Package to install block and attributes
 *
 * @package Amiant Image Gallery
 * @author Count Raven Amiant <cravener@gmail.com>
 * @copyright  Copyright (c) 2010-2014 Amiant Web Development Solutions. (http://amiant-dev.ru/)
 * @license    http://www.gnu.org/licenses/gpl-2.0.html     GNU GPL Version 2
 *
 */
 
defined('C5_EXECUTE') or die(_("Access Denied."));

class AmiantImageGalleryPackage extends Package {

	protected $pkgHandle = 'amiant_image_gallery';
	protected $appVersionRequired = '5.5.0';
	protected $pkgVersion = '0.7.3.1';
	
	public function getPackageDescription() {
		return t('Displays Images with effects in various ways.');
	}
	
	public function getPackageName() {
		return t('Amiant Image Gallery');
	}
	
	public function install() {
		$pkg = parent::install();
		
		// install attributes
		$this->addFileAttribute('TEXT', 'image_caption', t('Caption For Image'), true);
        $this->addFileAttribute('TEXT', 'link_url', t('Link For Image'), true);
        
		//install block
		BlockType::installBlockTypeFromPackage('amiant_gallery', $pkg); 

	}
	
	private function addFileAttribute($type, $handle, $name, $searchable)
    {
		Loader::model('file_attributes');
        $key = FileAttributeKey::getByHandle($handle);
        
        if (!is_object($key)) {
            $key = FileAttributeKey::add(AttributeType::getByHandle($type),
                                        array('akIsAutoCreated' => 0,
                                              'akHandle' => $handle,
                                              'akName' => $name,
                                              'akIsSearchable' => $searchable
                                        ));
        }
 
        return $key;
    }
    
}
