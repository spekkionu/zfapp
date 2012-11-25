<?php

/**
 * Admin CMS form.
 *
 * @package    App
 * @subpackage Form
 * @author     spekkionu
 * @license    New BSD http://www.opensource.org/licenses/bsd-license.php
 */
class Form_Content extends App_Form
{

    public function loadDefaultDecorators()
    {
        $this->setDecorators(array(
          'FormElements',
          array('Description', array('tag' => 'p', 'class' => 'form-help')),
          array('Fieldset', array()),
          array('Form', array('id' => 'form-content', 'class' => 'validate form-horizontal', 'accept-charset' => 'utf-8'))
        ));
    }

    public function init()
    {
        parent::init();

        $element = new Zend_Form_Element_Text('url');
        $element->setLabel('URL:');
        $element->setDescription("Enter the URL the page can be accessed at.");
        $element->setRequired(true);
        $element->addValidator('NotEmpty', true, array('messages' => array(
            Zend_Validate_NotEmpty::INVALID => "Invalid type given. String, integer or float expected",
            Zend_Validate_NotEmpty::IS_EMPTY => "URL is required."
          )));
        $element->addValidator('Regex', true, array('pattern' => '/^[a-z0-9\\/-]*$/', 'messages' => array(
            Zend_Validate_Regex::NOT_MATCH => "URL can only contain letters, numbers, slash and dash."
          )));
        $element->addValidator('StringLength', true, array('max' => 255, 'messages' => array(
            Zend_Validate_StringLength::INVALID => "Invalid type given. String, integer or float expected",
            Zend_Validate_StringLength::TOO_SHORT => "Must be at least %min% characters.",
            Zend_Validate_StringLength::TOO_LONG => "Must be no more than %max% characters."
          )));
        $element->setFilters(array('StringTrim', 'StripTags', 'StringToLower'));
        $element->addFilter(new Zend_Filter_Callback(array('callback' => array('Model_Content', 'filterUrl'))));
        $base_url = ($this->getView() && isset($this->getView()->site_info['url'])) ? $this->getView()->site_info['url'] : '/';
        if (mb_substr($base_url, -1) != '/') {
            $base_url .= '/';
        }
        $element->setDecorators(array(
          'ViewHelper',
          array(array('addon' => 'Addon'), array('tag' => 'span', 'class' => 'add-on', 'content' => $base_url)),
          array(array('prepend' => 'HtmlTag'), array('tag' => 'div', 'class' => 'input-prepend')),
          array('Description', array('tag' => 'div', 'class' => 'help-block', 'placement' => 'append', 'escape' => false)),
          array('Errors'),
          array(array('element' => 'HtmlTag'), array('tag' => 'div', 'class' => 'controls')),
          array('Label', array('placement' => 'prepend', 'class' => 'control-label')),
          array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'control-group'))
        ));

        $element->setAttribs(array(
          'maxlength' => 255,
          'data' => Zend_Json::encode(array('validate' => array(
              'required' => true,
              'slug' => true,
              'messages' => array(
                'required' => 'URL is required.',
                'slug' => 'URL can only contain letters, numbers, slash and dash.'
              )
            )))
        ));
        $this->addElement($element);

        $element = new Zend_Form_Element_Text('title');
        $element->setLabel('Page Title:');
        $element->setDescription("Enter the title of the page.");
        $element->setRequired(true);
        $element->setFilters(array('StringTrim', 'StripTags'));
        $element->addValidator('NotEmpty', true, array('messages' => array(
            Zend_Validate_NotEmpty::INVALID => "Invalid type given. String, integer or float expected",
            Zend_Validate_NotEmpty::IS_EMPTY => "Title is required."
          )));
        $element->addValidator('StringLength', true, array('max' => 255, 'messages' => array(
            Zend_Validate_StringLength::INVALID => "Invalid type given. String, integer or float expected",
            Zend_Validate_StringLength::TOO_LONG => "Must be no more than %max% characters."
          )));
        $element->setAttribs(array(
          'maxlength' => 255,
          'data' => Zend_Json::encode(array('validate' => array(
              'required' => true,
              'messages' => array(
                'required' => 'Title is required.',
                'maxlength' => 'Must be no more than 255 characters.'
              )
            )))
        ));
        $element->setDecorators($this->field);
        $this->addElement($element);

        $element = new Zend_Form_Element_Radio('active');
        $element->setLabel('Account Status:');
        $element->setMultiOptions(array(
          '1' => 'Active',
          '0' => 'Inactive'
        ));
        $element->setSeparator(PHP_EOL);
        $element->setDescription("Only active pages may be accessed.");
        $element->setFilters(array('StringTrim', 'StripTags'));
        $element->setDecorators($this->option_list);
        $element->setAttrib('label_class', 'radio');
        $element->setValue('0');
        $this->addElement($element);

        $element = new Zend_Form_Element_Textarea('content');
        $element->setLabel('Content:');
        $element->setDescription("Enter the content of the page.");
        $element->setFilters(array('StringTrim'));
        $element->addFilter(new Zend_Filter_Callback(array('callback' => array('Model_Content', 'filterContent'))));
        $element->setAttribs(array(
          'rows' => 20,
          'class' => 'span10'
        ));
        $element->setDecorators($this->field);
        $this->addElement($element);

        $element = new Zend_Form_Element_Submit('save');
        $element->setLabel('Save');
        $element->setDecorators($this->buttonOpen);
        $element->setIgnore(true);
        $element->setAttrib('class', 'btn btn-primary');
        $this->addElement($element);

        $element = new App_Form_Element_LinkButton('cancel');
        $element->setLabel('Cancel');
        $element->setDecorators($this->buttonClose);
        $element->setAttrib('class', 'btn cancel');
        $element->setIgnore(true);
        $this->addElement($element);

        $this->addElement('hash', 'csrf', array('ignore' => true, 'decorators' => $this->hidden));
    }

    /**
     * Adds database validators
     * @param int $id Account to exclude from unique requirements
     * @return Form_AdminProfile
     */
    public function addDbValidators($id = null)
    {
        if ($this->getElement('url')) {
            $validator = new Zend_Validate_Db_NoRecordExists(array(
                'table' => 'content',
                'field' => 'url'
              ));
            if ($id) {
                $validator->setExclude(array('field' => 'id', 'value' => $id));
            }
            $validator->setMessages(array(
              Zend_Validate_Db_NoRecordExists::ERROR_RECORD_FOUND => "Another page exists with this url."
            ));
            $this->getElement('url')->addValidator($validator, true);
        }
        return $this;
    }

}
