<?php
/**
 Admin Page Framework v3.5.6 by Michael Uno
 Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
 <http://en.michaeluno.jp/admin-page-framework>
 Copyright (c) 2013-2015, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT>
 */
abstract class Legull_AdminPageFramework_MetaBox_Page_Model extends Legull_AdminPageFramework_MetaBox_Page_Router {
    static protected $_sFieldsType = 'page_meta_box';
    public function __construct($sMetaBoxID, $sTitle, $asPageSlugs = array(), $sContext = 'normal', $sPriority = 'default', $sCapability = 'manage_options', $sTextDomain = 'admin-page-framework') {
        $this->oProp = new Legull_AdminPageFramework_Property_MetaBox_Page($this, get_class($this), $sCapability, $sTextDomain, self::$_sFieldsType);
        $this->oProp->aPageSlugs = is_string($asPageSlugs) ? array($asPageSlugs) : $asPageSlugs;
        parent::__construct($sMetaBoxID, $sTitle, $asPageSlugs, $sContext, $sPriority, $sCapability, $sTextDomain);
    }
    protected function _setUpValidationHooks($oScreen) {
        foreach ($this->oProp->aPageSlugs as $_sIndexOrPageSlug => $_asTabArrayOrPageSlug) {
            if (is_string($_asTabArrayOrPageSlug)) {
                $_sPageSlug = $_asTabArrayOrPageSlug;
                add_filter("validation_saved_options_without_dynamic_elements_{$_sPageSlug}", array($this, '_replyToFilterPageOptionsWODynamicElements'), 10, 2);
                add_filter("validation_{$_sPageSlug}", array($this, '_replyToValidateOptions'), 10, 4);
                add_filter("options_update_status_{$_sPageSlug}", array($this, '_replyToModifyOptionsUpdateStatus'));
                continue;
            }
            $_sPageSlug = $_sIndexOrPageSlug;
            $_aTabs = $_asTabArrayOrPageSlug;
            foreach ($_aTabs as $_sTabSlug) {
                add_filter("validation_{$_sPageSlug}_{$_sTabSlug}", array($this, '_replyToValidateOptions'), 10, 4);
                add_filter("validation_saved_options_without_dynamic_elements_{$_sPageSlug}_{$_sTabSlug}", array($this, '_replyToFilterPageOptionsWODynamicElements'), 10, 2);
                add_filter("options_update_status_{$_sPageSlug}_{$_sTabSlug}", array($this, '_replyToModifyOptionsUpdateStatus'));
            }
        }
    }
    protected function getFieldOutput($aField) {
        $aField['option_key'] = $this->_getOptionKey();
        $aField['page_slug'] = $this->oProp->getCurrentPageSlug();
        return parent::getFieldOutput($aField);
    }
    private function _getOptionkey() {
        return isset($_GET['page']) ? $this->oProp->getOptionKey($_GET['page']) : null;
    }
    public function _replyToAddMetaBox($sPageHook = '') {
        foreach ($this->oProp->aPageSlugs as $sKey => $asPage) {
            if (is_string($asPage)) {
                $this->_addMetaBox($asPage);
                continue;
            }
            $_sPageSlug = $sKey;
            foreach ($this->oUtil->getAsArray($asPage) as $_sTabSlug) {
                if (!$this->oProp->isCurrentTab($_sTabSlug)) {
                    continue;
                }
                $this->_addMetaBox($_sPageSlug);
            }
        }
    }
    private function _addMetaBox($sPageSlug) {
        add_meta_box($this->oProp->sMetaBoxID, $this->oProp->sTitle, array($this, '_replyToPrintMetaBoxContents'), $this->oProp->_getScreenIDOfPage($sPageSlug), $this->oProp->sContext, $this->oProp->sPriority, null);
    }
    public function _replyToFilterPageOptions($aPageOptions) {
        return $aPageOptions;
    }
    public function _replyToFilterPageOptionsWODynamicElements($aOptionsWODynamicElements, $oFactory) {
        return $this->oForm->dropRepeatableElements($aOptionsWODynamicElements);
    }
    public function _replyToValidateOptions($aNewPageOptions, $aOldPageOptions, $oAdminPage, $aSubmitInfo) {
        $_aFieldsModel = $this->oForm->getFieldsModel();
        $_aNewMetaBoxInput = $this->oUtil->castArrayContents($_aFieldsModel, $_POST);
        $_aOldMetaBoxInput = $this->oUtil->castArrayContents($_aFieldsModel, $aOldPageOptions);
        $_aNewMetaBoxInput = stripslashes_deep($_aNewMetaBoxInput);
        $_aNewMetaBoxInputRaw = $_aNewMetaBoxInput;
        $_aNewMetaBoxInput = call_user_func_array(array($this, 'validate'), array($_aNewMetaBoxInput, $_aOldMetaBoxInput, $this, $aSubmitInfo));
        $_aNewMetaBoxInput = $this->oUtil->addAndApplyFilters($this, "validation_{$this->oProp->sClassName}", $_aNewMetaBoxInput, $_aOldMetaBoxInput, $this, $aSubmitInfo);
        if ($this->hasFieldError()) {
            $this->_setLastInput($_aNewMetaBoxInputRaw);
        }
        return $this->oUtil->uniteArrays($_aNewMetaBoxInput, $aNewPageOptions);
    }
    public function _replyToModifyOptionsUpdateStatus($aStatus) {
        if (!$this->hasFieldError()) {
            return $aStatus;
        }
        return array('field_errors' => true) + $this->oUtil->getAsArray($aStatus);
    }
    public function _registerFormElements($oScreen) {
        if (!$this->_isInThePage()) {
            return;
        }
        $this->_loadFieldTypeDefinitions();
        $this->oForm->format();
        $this->oForm->applyConditions();
        $this->oForm->applyFiltersToFields($this, $this->oProp->sClassName);
        $this->_setOptionArray($_GET['page'], $this->oForm->aConditionedFields);
        $this->oForm->setDynamicElements($this->oProp->aOptions);
        $this->_registerFields($this->oForm->aConditionedFields);
    }
    protected function _setOptionArray($sPageSlug, array $aFields) {
        $_aOptions = $this->_getPageMetaBoxOptionsFromPageOptions($this->oProp->aOptions, $aFields);
        $_aOptions = $this->oUtil->addAndApplyFilter($this, 'options_' . $this->oProp->sClassName, $_aOptions);
        $_aLastInput = isset($_GET['field_errors']) && $_GET['field_errors'] ? $this->oProp->aLastInput : array();
        $this->oProp->aOptions = $_aLastInput + $this->oUtil->getAsArray($_aOptions);
    }
    private function _getPageMetaBoxOptionsFromPageOptions(array $aPageOptions, array $aFields) {
        $_aOptions = array();
        foreach ($aFields as $_sSectionID => $_aFields) {
            if ('_default' === $_sSectionID) {
                foreach ($_aFields as $_aField) {
                    if (array_key_exists($_aField['field_id'], $aPageOptions)) {
                        $_aOptions[$_aField['field_id']] = $aPageOptions[$_aField['field_id']];
                    }
                }
            }
            if (array_key_exists($_sSectionID, $aPageOptions)) {
                $_aOptions[$_sSectionID] = $aPageOptions[$_sSectionID];
            }
        }
        return $_aOptions;
    }
}