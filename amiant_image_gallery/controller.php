<?php   

defined('C5_EXECUTE') or die(_("Access Denied."));

class AmiantImageGalleryPackage extends Package {

	protected $pkgHandle = 'amiant_image_gallery';
	protected $appVersionRequired = '5.5.0';
	protected $pkgVersion = '0.5';
	
	public function getPackageDescription() {
		return t('Displays Images with effects in various ways.');
	}
	
	public function getPackageName() {
		return t('Amiant Image Gallery');
	}
	
	public function install() {
		$pkg = parent::install();
		Loader::model('file_attributes');
		
		// install attributes
		$this->addFileAttribute('TEXT', 'image_caption', t('Caption For Image'), true);
        $this->addFileAttribute('TEXT', 'link_url', t('Link For Image'), true);
        
		//install block
		BlockType::installBlockTypeFromPackage('amiant_gallery', $pkg); 

	}
	
	private function addFileAttribute($type, $handle, $name, $searchable)
    {
        $key = FileAttributeKey::getByHandle($handle);
        if ($key == null) {
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
