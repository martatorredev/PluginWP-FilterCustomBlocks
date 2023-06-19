<?php 
class FILTER_Build_Menupage {
    protected $menus;
    protected $submenus;
    public function __construct() {
        $this->menus = [];
        $this->submenus = [];
    }
    public function add_menu_page( $pageTitle, $menuTitle, $capability, $menuSlug, $functionName, $iconUrl = '', $position = null ) {
        $this->menus = $this->add_menu( $this->menus, $pageTitle, $menuTitle, $capability, $menuSlug, $functionName, $iconUrl, $position );
    }
    private function add_menu( $menus, $pageTitle, $menuTitle, $capability, $menuSlug, $functionName, $iconUrl, $position ) {
        $menus[] = [
            'pageTitle'     => $pageTitle,
            'menuTitle'     => $menuTitle,
            'capability'    => $capability,
            'menuSlug'      => $menuSlug,
            'functionName'  => $functionName,
            'iconUrl'       => $iconUrl,
            'position'      => $position
        ];
        return $menus;
    }
    public function add_submenu_page( $parentSlug, $pageTitle, $menuTitle, $capability, $menuSlug, $functionName ) {
        $this->submenus = $this->add_submenu( $this->submenus, $parentSlug, $pageTitle, $menuTitle, $capability, $menuSlug, $functionName );
    }    
    private function add_submenu( $submenus, $parentSlug, $pageTitle, $menuTitle, $capability, $menuSlug, $functionName) {
        $submenus[] = [
            'parentSlug'    => $parentSlug,
            'pageTitle'     => $pageTitle,
            'menuTitle'     => $menuTitle,
            'capability'    => $capability,
            'menuSlug'      => $menuSlug,
            'functionName'  => $functionName
        ];
         return $submenus;
    }
    public function run() {
        foreach( $this->menus as $menus ) {
            extract( $menus, EXTR_OVERWRITE );
            add_menu_page( $pageTitle, $menuTitle, $capability, $menuSlug, $functionName, $iconUrl, $position );
        }
        foreach( $this->submenus as $submenus ) {
            extract( $submenus, EXTR_OVERWRITE );
            add_submenu_page( $parentSlug, $pageTitle, $menuTitle, $capability, $menuSlug, $functionName );
        }
    }
}