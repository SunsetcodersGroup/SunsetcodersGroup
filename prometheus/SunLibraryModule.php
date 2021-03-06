<?php
/**
 * Sun Library abstract module.  Any module loaded dynamically should be an instance of this.
 *
 * @author          Simon Mitchell <kartano@gmail.com>
 * @version         1.0.0               2016-12-14 15:14:53 SM:  Prototype
 * @version         1.1.0               2016-12-15 08:32:18 SM:  SwitchMode is now part of the footprint.
 * @version         1.1.1               2016-12-16 17:11:27 SM:  Making use of the FileAttributes library.
 */

abstract class SunLibraryModule
{
    protected $objDB;
    
    /**
     * Constructor.
     *
     * @param \mysqli $objDB Connection to the database.
     */
    public function __construct(\mysqli $objDB)
    {
        $this->objDB=$objDB;
        
        //--------------------------------------------------------------------------------------
        // SM:  As soon as we instantiate this module, we assert that the table exists
        //      and then switch through the current state to see what we need to do.
        //--------------------------------------------------------------------------------------
        
        $this->assertTablesExist();
        $this->switchMode();
    }
    
    /**
     * Render any links such as external JS or external CSS files that we might need.
     * These are all rendered in the HEAD of the document.
     *
     * @return void
     */
    public function renderHeaderLinks()
    {
        //
    }
    
    /**
     * Render any custom javascript a module may need, such as validation or functions for special effects.
     * These are all rendered into the HEAD of the document within a SCRIPT tag.
     *
     * @return void
     */
    public function renderCustomJavaScript()
    {
        //
    }
    
    /**
     * Render any custom javascript inside the document ready function.  Anything here will be called as soon as the DOM is loaded.
     *
     * @return void
     */
    public function documentReadyJavaScript()
    {
        //
    }
    
    /**
     * The common entry point for all modules.
     *
     * @return void
     */
    public function callToFunction()
    {
        //
    }
    
    /**
     * This function is used to assert that necessary tables for a given module exist.
     *
     * @return void
     */
    protected function assertTablesExist()
    {
        //
    }
    
    /**
     * Returns the version of the module, based on the most recent version number inside the files docblock.
     *
     * @return string The full version of the module as read from its docblock.
     */
    public function getVersion()
    {
        return 'unknown';
    }
    
    /**
     * Switch the mode of the module based on what the local action for the script is.
     *
     * @return void
     */
    public function switchMode()
    {
        //
    }
    
    /**
     * Helper function to retrieve the version number from a given file.
     *
     * @param string $txtFile The File to be tested for file attributes.
     *
     * @return string A string value with the full version number of the specified file.
     */
    protected function readVersionFromFile($txtFile)
    {
        $objDetails=FileAttributes\FileAttributes($txtFile);
        return $objDetails->txtVersion;
    }
}
?>
