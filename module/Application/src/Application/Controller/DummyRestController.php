<?php

/**
 * Zippel Media
 *
 * @author Ralf Schneider <ralf@hr-interactive.de>
 */

namespace Application\Controller;

use Symfony\Component\Config\Definition\Exception\Exception;
use Zend\Authentication\Storage\Session;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;


/**
 * Class DummyRestController
 *
 * @package Application\Controller
 */
class DummyRestController extends AbstractRestfulController
{
    private $bStatus = false;
    private $sMessage = 'something failed (initial message state)';
    private $aMessages = array();
    private $aData;

    private function getDataPost(){
        $oRequest = $this->getRequest();
        $aData = (array) $oRequest->getPost();
        file_put_contents('/var/www/html/storage/data.txt', json_encode($aData));
        return $aData;
    }

    private function getHash(){
        return hash('sha256', time());
    }

    public function indexAction(){
        /*** TODO DML the rest service should read the current user. Actually it responses only for logged in users,
         * but we do not know who is the user which is logged in ***/
        $this->setOK();
        $this->setMessage('just an index test...');
        $this->addObject('test','1');
        return $this->renderResult();
    }

    public function getRastersAction(){
        $config = $this->getServiceLocator()->get('config');
        $sGridPath = $config['rasterspath'];
        $aCollection = array();
        if ($hDir = opendir($sGridPath)) {
            while (false !== ($sItem = readdir($hDir))) {
                if ($sItem != "." && $sItem != ".." && strpos($sItem,".json")!==false) {
                    $sFile = str_replace('//', '/', $sGridPath.'/'.$sItem);
                    $json = file_get_contents($sFile);
                    $json = preg_replace( "/\r|\n/", "", $json );
                    $this->addMessage('Test "' . $sItem . '" ("'.$json.'")...');
                    if(!empty($json)) {
                        $aItem = json_decode($json);
                        array_push($aCollection, $aItem);
                    } else {
                        $this->addMessage('File "' . $sFile . '" is empty.');
                    }
                }
            }
            closedir($hDir);
        }
        if(count($aCollection)>0) {
            $this->addObject($aCollection);
            $this->setOK();
            $this->setMessage('list of rasters has been generated.');
        } else {
            $this->setMessage('could not find any raster assets.');
        }
        return $this->renderResult();
    }

    public function saverasterAction(){
        $aData = $this->getDataPost();
        $config = $this->getServiceLocator()->get('config');
        $sRasterPath = $config['rasterspath'];
        if(is_dir($sRasterPath)){
            $sFilePath = $sRasterPath . '/' . $this->getHash() . '.json';
            $sFilePath = str_replace('//', '/', $sFilePath);
            if(file_put_contents($sFilePath, json_encode($aData))){
                $this->setOK();
                $this->sMessage = ' The raster has been saved to ' . $sFilePath . '"';
            } else {
                $this->sMessage = 'Grid #' . $aItem->grid . 'cannot save to "' . $sFilePath . '"';
            }
        } else {
            $this->setMessage('Cannot find raster storage path "' . $sRasterPath . '" or it is inaccessible');
        }
        return $this->renderResult();
    }

    public function getVideosAction(){
        $config = $this->getServiceLocator()->get('config');
        $sVideoPath = $config['videopath'];
        $sConfig = file_get_contents('http://' . $sVideoPath . 'config.json');
        $aConfig = json_decode($sConfig, true);
        if( is_array($aConfig)  &&  count($aConfig)>0){
            if( isset( $aConfig['config'] ) ){
                foreach( $aConfig['config']  as &$aItem){
                    if(isset($aItem['path'])){
                        $aItem['path'] = 'http://' . str_replace('//', '/', $sVideoPath . $aItem['path']);
                    }
                    if(isset($aItem['graphic']['thumb'])){
                        foreach($aItem['graphic']['thumb'] as &$aThumb){
                            $aThumb = 'http://' . str_replace('//', '/', $sVideoPath . $aThumb);
                        }
                    }
                    if(isset($aItem['graphic']['image'])){
                        $aItem['graphic']['image'] = 'http://' . str_replace('//', '/', $sVideoPath . $aItem['graphic']['image']);
                    }
                }
                $this->addObject($aConfig['config']);
            }
        }
        return $this->renderResult();
    }

    private function getItem($aItem, $sPath){
        $this->addMessage('test');
        $aReturn = $aItem;
        if (isset($aReturn['image'])) {
            $aReturn['image'] = $sPath . $aReturn['image'];
        }
        return $aReturn;
    }

    public function getBackgroundsAction(){
        $aReturn = array();
        $config = $this->getServiceLocator()->get('config');
        $sPath = $config['dam']['path'];
        if( isset( $config['rest-fake']['backgrounds'])) {
            $aCollection = $config['rest-fake']['backgrounds'];
            foreach($aCollection as $sCollectionKey=>$aCollectionItem){
                foreach($aCollectionItem as $aCollectionItemKey=>$aCollectionImageItem){
                    foreach($aCollectionImageItem as $iKey=>$aImageItem){
                        $aImageItem['image'] = $sPath . $aImageItem['image'];
                        $aReturn[$aCollectionItemKey][] = $aImageItem;
                    }
                }
            }
        } else{
            $this->addMessage('could not find the "' . $sType[0] . '" collection.');
        }
        if(count($aReturn)>0) {
            $this->addObject($aReturn);
            $this->setOK();
            $this->setMessage('list of backgrounds has been generated');
        } else {
            $this->setMessage('could not find any background assets');
        }
        return $this->renderResult();
    }

    public function getImagesAction(){
        //** todo not ready, yet,... need to change the collection in configuration. It has not the attibutes and it misses the thumbs */
        $aReturn = array();
        $config = $this->getServiceLocator()->get('config');
        $sPath = $config['dam']['path'];
        if( isset( $config['rest-fake']['backgrounds'])) {
            $aCollection = $config['rest-fake']['images'];
            foreach($aCollection as &$aItem){
                if (isset($aItem['image'])) {
                    $aItem['image'] = $sPath . $aItem['image'];
                }
                if (isset($aItem['thumb']['small'])) {
                    $aItem['thumb']['small'] = $sPath . $aItem['thumb']['small'];
                }
                if (isset($aItem['thumb']['medium'])) {
                    $aItem['thumb']['medium'] = $sPath . $aItem['thumb']['medium'];
                }
                if (isset($aItem['thumb']['large'])) {
                    $aItem['thumb']['large'] = $sPath . $aItem['thumb']['large'];
                }
                array_push($aReturn, $aItem);
            }
        } else{
            $this->addMessage('Could not find the "' . $sType[0] . '" collection.');
        }
        if(count($aReturn)>0) {
            $this->addObject($aReturn);
            $this->setOK();
            $this->setMessage('List of backgrounds has been generated.');
        } else {
            $this->setMessage('could not find any background assets');
        }
        return $this->renderResult();
    }

    public function getFormatsAction(){
        //** todo not ready, yet,... need to change the collection in configuration. It has not the attibutes and it misses the thumbs */
        $aReturn = array();
        $config = $this->getServiceLocator()->get('config');
        $sPath = $config['dam']['path'];
        if( isset( $config['rest-fake']['formats'])) {
            $this->addObject($config['rest-fake']['formats']);
            $this->setOK();
            $this->setMessage('List of formats has been generated.');
        } else{
            $this->addMessage('Could not find the format collection.');
        }
        return $this->renderResult();
    }

    public function getArticlesAction(){
        //** todo not ready, yet,... need to change the collection in configuration. It has not the attibutes and it misses the thumbs */
        $aReturn = array();
        $config = $this->getServiceLocator()->get('config');
        $sPath = $config['dam']['path'];
        $aArray = array();
        if( isset( $config['rest-fake']['articles'])) {
            $aItems = $config['rest-fake']['articles'];
            foreach($aItems as $aItem){
                if(isset( $aItem['image'])){
                    if(isset($aItem['image']['image'])){$aItem['image']['image'] = $sPath . $aItem['image']['image'];}
                    if( isset( $aItem['image']['thumb']['big'] ) ){$aItem['image']['thumb']['big'] = $sPath . $aItem['image']['thumb']['big'];}
                    if( isset( $aItem['image']['thumb']['medium'] ) ){$aItem['image']['thumb']['medium'] = $sPath . $aItem['image']['thumb']['medium'];}
                    if( isset( $aItem['image']['thumb']['small'] ) ){$aItem['image']['thumb']['small'] = $sPath . $aItem['image']['thumb']['small'];}
                }
                $aArray[] = $aItem;
            }
            $this->setOK();
            $this->setMessage('List of articles has been generated.');
        } else{
            $this->addMessage('Could not find the article collection.');
        }
        $this->addObject($aArray);
        return $this->renderResult();
    }

    public function getFontsAction(){
        //** todo not ready, yet,... need to change the collection in configuration. It has not the attibutes and it misses the thumbs */
        $aReturn = array();
        $config = $this->getServiceLocator()->get('config');
        $sPath = $config['dam']['path'];
        if( isset( $config['rest-fake']['fonts'])) {
            $this->addObject($config['rest-fake']['fonts']);
            $this->setOK();
            $this->setMessage('List of fonts has been generated.');
        } else{
            $this->addMessage('Could not find the font collection.');
        }
        return $this->renderResult();
    }

    public function getLogosAction(){
        //** todo not ready, yet,... need to change the collection in configuration. It has not the attibutes and it misses the thumbs */
        $aReturn = array();
        $config = $this->getServiceLocator()->get('config');
        $sPath = $config['dam']['path'];
        if( isset( $config['rest-fake']['logos'])) {
            foreach($config['rest-fake']['logos'] as &$aItem){
                if(isset($aItem['image'])){
                    $aItem['image'] = $sPath . $aItem['image'];
                }
            }
            $this->addObject($config['rest-fake']['logos']);
            $this->setOK();
            $this->setMessage('List of logos has been generated.');
        } else{
            $this->addMessage('Could not find the logo collection.');
        }
        return $this->renderResult();
    }


















    public function getAction(){
        // this is a quick and dirty first solution of a rest service to mock rest / dam / pim for the front end
        $aData = $this->getDataPost();
        /*** TODO DML the rest service should read the current user. Actually it responses only for logged in users,
         * but we do not know who is the user which is logged in ***/
        $sType = $aData['type'];
        $config = $this->getServiceLocator()->get('config');
        $aReturn = array();
        $dampath = $config['dam']['path'];
            if( isset( $config['rest-fake']['elements'] ) ) {
                $aCollection = $this->getServiceLocator()->get('config')['rest-fake']['elements'];
                foreach($aCollection as $aItem){
                    if( $aItem['type']==$sType ){
                        if(isset($aItem['file'])){
                            $aItem['file'] = $dampath . $aItem['file'];
                        }
                        array_push($aReturn, $aItem);
                    }
                }
            }
        if(count($aReturn)>0) {
            $this->addObject('collection', $aReturn);
            $this->setOK();
            $this->setMessage('list of ' . $sType . ' has been responded');
        } else {
            $this->setMessage('could not find any assets of type "' . $sType . '"');
        }
        return $this->renderResult();
    }



    public function saveAction(){
        $oRequest = $this->getRequest();
        $aData = (array) $oRequest->getPost();
        file_put_contents('/var/www/html/storage/data.txt', json_encode($aData));
        if( null!==$aData){
            // we should decide how we connect to other servers? via SSH?
            if(isset($aData['project'])){
                if(isset($aData['project']['content'])){
                    if( !isset( $aData['project']['content']['name'] )){
                        $this->addMessage('Frontend did not specifiy a project name to save');
                    } else if( !isset( $aData['project']['content']['id'] )){
                        $this->addMessage('Frontend did not specifiy a project id to save');
                    } else {
                        $sId = $aData['project']['content']['id'];
                        $sName = $aData['project']['content']['id'];
                        if (isset($aData['project']['content'])) {
                            $config = $this->getServiceLocator()->get('config');
                            $sPath = $config['project']['path'];
                            $sPath .= '/' . $sId;
                            $sPath = str_replace('//', '/', $sPath);
                            if (!is_dir($sPath)) {
                                if (!mkdir($sPath, 0775, true)) {
                                    $this->addMessage('could not create the path "' . $sPath . '".');
                                } else {
                                    $this->addMessage('path "' . $sPath . '" has been created.');
                                }
                            }
                            $sFile = $sPath . '/project.json';
                            $sFile = str_replace('//', '/', $sFile);
                            if( file_put_contents($sFile, json_encode($aData)) ){
                                $this->addMessage('project has been saved to "' . $sFile . '".');
                            }
                            $this->setOK();
                            $this->setMessage('Project has been saved!');
                        } else {
                            $this->setMessage('project content is missing....');
                        }
                    }
                } else {
                    $this->setMessage('Frontend did not specifiy a content object to save');
                }
            } else if(isset($aData['grid'])){

            }
            else {
                $this->addMessage('can not identify what frontend would like to save (missing project = id)');
            }
        }
        return $this->renderResult();
    }

    public function loadAction(){
        if( null!==$this->params()->fromQuery('data')){
            // we should decide how we connect to other servers? via SSH?
            $aData = $this->params()->fromQuery('data');
            if( null!==$aData ){
                if(isset($aData['project'])){
                    $sId = $aData['project'];
                    $config = $this->getServiceLocator()->get('config');
                    $sPath = $config['project']['path'];
                    $sPath .= '/' . $sId;
                    $sPath .= '/project.json';
                    $sPath = str_replace('//','/', $sPath);
                    if( file_exists( $sPath )){
                        $aData = file_get_contents($sPath);
                        $this->addObject($aData);
                        $this->setOK();
                        $this->sMessage = 'Project "' . $sId . '" has been loaded.';
                    } else {
                        $this->sMessage = 'Cannot load project file "'.$sPath.'".';
                    }
                }
                else {
                    $this->addMessage('can not identify what frontend would like to save (missing project = id)');
                }
            }
        }
        return $this->renderResult();
    }

    public function printAction(){
        // disable DOMPDF's internal autoloader if you are using Composer
        define('DOMPDF_ENABLE_AUTOLOAD', false);
        define('DOMPDF_UNICODE_ENABLED', true);
        define("DOMPDF_DEFAULT_PAPER_SIZE", "A4");
        define("DOMPDF_DPI", 300);
        define("DOMPDF_ENABLE_REMOTE", true);
        define("DOMPDF_ENABLE_CSS_FLOAT", true);

        // include DOMPDF's default configuration
        require_once 'vendor/dompdf/dompdf/dompdf_config.inc.php';
        $html = $aData = $this->params()->fromQuery('html');
        $this->addObject('test', $html);
        if( null !== $html){
            $config = $this->getServiceLocator()->get('config');
            $sFilePath = $config['temp'];
            if( !is_dir($sFilePath)){
                if(!mkdir($sFilePath)){
                    $this->sMessage('Could not create the folder....');
                }
            }
            if( is_dir($sFilePath) ){
                $sFilePath .= '/';
                $sFilePath .= hash('sha256', time());
                $sFilePath .= '.pdf';
                $sFilePath = str_replace('//','/', $sFilePath);

                $this->addObject('stored', $sFilePath);
                $dompdf = new \DOMPDF();
                $html = '<html><head></head><body><div style="background: #FFFFCC"><img style="float: right;" src="http://www.zippelmedia.com/wp-content/uploads/2015/01/zm_logo@21.png" />Ein erster DRUCK über DOM PDF</div></body></html>';
                $dompdf->load_html($html);
                $dompdf->render();
                file_put_contents($sFilePath, $dompdf->output());
                //$dompdf->stream('foo.pdf', array('Attachment' => false));*/
                $this->setOK();
            }
        }
        return $this->renderResult();
    }

    private function callNoTypeResponse(){
        $this->setMessage('The get type was not set. Cannot identify which collection has been requested.');
        return $this->renderResult();
    }

    private function addMessage($sMessage){
        array_push($this->aMessages, $sMessage);
    }

    private function setMessage($sMessage=null){
        if( null===$sMessage ){
            $this->sMessage = 'Backend did not specify a message,... ';
        } else {
            $this->sMessage = $sMessage;
        }
    }

    private function setOK(){
        $this->bStatus = true;
    }

    private function addObject($sKey, $vObject=null){
        if( !is_array( $this->aData )){
            $this->aData = array();
        }
        if(null!==$vObject) {
            $this->aData[$sKey] = $vObject;
        } else {
            $this->aData = $sKey;
        }
    }

    private function renderResult(){
        $result = new JsonModel(array(
            'status'    =>  $this->bStatus,
            'message'   =>  $this->sMessage,
            'data'      =>  $this->aData,
            'messages'  =>  $this->aMessages,
        ));
        return $result;
    }


    public function getList()
    {
        # code...
        return null;
    }

    public function get($id)
    {
        # code...
    }

    public function create($data)
    {
        # code...
    }

    public function update($id, $data)
    {
        # code...
    }

    public function delete($id)
    {
        # code...
    }


}