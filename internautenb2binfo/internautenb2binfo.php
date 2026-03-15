<?php

/**
 * Internauten B2B Info Module
 *
 * @author    die.internauten.ch
 * @copyright Copyright (c) 2026
 * @license   MIT License
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class InternautenB2BInfo extends Module
{
    public function __construct()
    {
        $this->name = 'internautenb2binfo';
        $this->tab = 'pricing_promotion';
        $this->version = '1.0.7';
        $this->author = 'die.internauten.ch';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.7.0.0',
            'max' => _PS_VERSION_
        ];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Internauten B2B Info');
        $this->description = $this->l('Display custom text on product pages for specific customer groups');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall this module?');
    }

    public function install()
    {
        $defaultMessage = [];
        $languages = Language::getLanguages(false);
        foreach ($languages as $language) {
            $defaultMessage[(int)$language['id_lang']] = $this->l('Special pricing for your group!');
        }

        return parent::install()
            && $this->registerHook('displayProductPriceBlock')
            && Configuration::updateValue('INTERNAUTENB2BINFO_GROUP_ID', 1)
            && Configuration::updateValue('INTERNAUTENB2BINFO_GROUP_MESSAGE', $defaultMessage, true)
            && Configuration::updateValue('INTERNAUTENB2BINFO_ENABLED', 1);
    }

    public function uninstall()
    {
        return parent::uninstall()
            && Configuration::deleteByName('INTERNAUTENB2BINFO_GROUP_ID')
            && Configuration::deleteByName('INTERNAUTENB2BINFO_GROUP_MESSAGE')
            && Configuration::deleteByName('INTERNAUTENB2BINFO_ENABLED');
    }

    public function getContent()
    {
        $output = '';

        if (Tools::isSubmit('submit' . $this->name)) {
            $groupId = (int)Tools::getValue('INTERNAUTENB2BINFO_GROUP_ID');
            $enabled = (int)Tools::getValue('INTERNAUTENB2BINFO_ENABLED');
            $defaultLang = (int)Configuration::get('PS_LANG_DEFAULT');
            $languages = Language::getLanguages(false);
            $groupMessagesByLang = [];

            foreach ($languages as $language) {
                $idLang = (int)$language['id_lang'];
                $groupMessagesByLang[$idLang] = (string)Tools::getValue('INTERNAUTENB2BINFO_GROUP_MESSAGE_' . $idLang, '');
            }

            // Fallback for setups posting only the base field name.
            if (isset($groupMessagesByLang[$defaultLang]) && $groupMessagesByLang[$defaultLang] === '') {
                $baseMessage = Tools::getValue('INTERNAUTENB2BINFO_GROUP_MESSAGE', '');
                if (is_scalar($baseMessage) && $baseMessage !== '') {
                    $groupMessagesByLang[$defaultLang] = (string)$baseMessage;
                }
            }

            if (!$groupId || !Validate::isUnsignedId($groupId)) {
                $output .= $this->displayError($this->l('Invalid group ID'));
            } elseif (empty($groupMessagesByLang)) {
                $output .= $this->displayError($this->l('Invalid group message value'));
            } else {
                foreach ($groupMessagesByLang as $message) {
                    if (!Validate::isCleanHtml((string)$message)) {
                        $output .= $this->displayError($this->l('Invalid group message value'));

                        return $output . $this->displayForm();
                    }
                }

                Configuration::updateValue('INTERNAUTENB2BINFO_GROUP_ID', $groupId);
                Configuration::updateValue('INTERNAUTENB2BINFO_ENABLED', $enabled);
                Configuration::updateValue('INTERNAUTENB2BINFO_GROUP_MESSAGE', $groupMessagesByLang, true);
                $output .= $this->displayConfirmation($this->l('Settings updated successfully'));
            }
        }

        return $output . $this->displayForm();
    }

    public function displayForm()
    {
        // Get default language
        $defaultLang = (int)Configuration::get('PS_LANG_DEFAULT');
        $languages = Language::getLanguages(false);
        foreach ($languages as &$language) {
            $language['is_default'] = ((int)$language['id_lang'] === $defaultLang);
        }
        unset($language);

        // Get all customer groups
        $groups = Group::getGroups($defaultLang);

        $groupOptions = [];
        foreach ($groups as $group) {
            $groupOptions[] = [
                'id' => $group['id_group'],
                'name' => $group['name']
            ];
        }

        $fieldsForm = [
            'form' => [
                'legend' => [
                    'title' => $this->l('Settings'),
                ],
                'input' => [
                    [
                        'type' => 'switch',
                        'label' => $this->l('Enable module'),
                        'name' => 'INTERNAUTENB2BINFO_ENABLED',
                        'is_bool' => true,
                        'desc' => $this->l('Enable or disable the module'),
                        'values' => [
                            [
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ],
                            [
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            ]
                        ],
                    ],
                    [
                        'type' => 'select',
                        'label' => $this->l('Customer Group'),
                        'name' => 'INTERNAUTENB2BINFO_GROUP_ID',
                        'required' => true,
                        'options' => [
                            'query' => $groupOptions,
                            'id' => 'id',
                            'name' => 'name'
                        ],
                        'desc' => $this->l('Select the customer group that will see the message')
                    ],
                    [
                        'type' => 'textarea',
                        'label' => $this->l('Group message'),
                        'name' => 'INTERNAUTENB2BINFO_GROUP_MESSAGE',
                        'lang' => true,
                        'autoload_rte' => false,
                        'rows' => 3,
                        'cols' => 60,
                        'desc' => $this->l('Message shown for the selected customer group. You can define one text per language.')
                    ]
                ],
                'submit' => [
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default pull-right'
                ]
            ],
        ];

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        $helper->default_form_language = $defaultLang;
        $helper->allow_employee_form_lang = (int)Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG');
        $helper->languages = $languages;
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;
        $helper->toolbar_scroll = true;
        $helper->submit_action = 'submit' . $this->name;

        $helper->fields_value['INTERNAUTENB2BINFO_GROUP_ID'] = Configuration::get('INTERNAUTENB2BINFO_GROUP_ID');
        $helper->fields_value['INTERNAUTENB2BINFO_ENABLED'] = Configuration::get('INTERNAUTENB2BINFO_ENABLED');
        foreach ($languages as $language) {
            $idLang = (int)$language['id_lang'];
            $helper->fields_value['INTERNAUTENB2BINFO_GROUP_MESSAGE'][$idLang] = Configuration::get('INTERNAUTENB2BINFO_GROUP_MESSAGE', $idLang);
        }

        return $helper->generateForm([$fieldsForm]);
    }

    public function hookDisplayProductPriceBlock($params)
    {
        // Check if module is enabled
        if (!Configuration::get('INTERNAUTENB2BINFO_ENABLED')) {
            return '';
        }

        if (!isset($params['product']) || !is_object($params['product'])) {
            return '';
        }

        $presentedProduct = $params['product'];

        // Check if we have the 'after_price' type
        if (isset($params['type']) && $params['type'] !== 'after_price') {
            return '';
        }

        // Get current customer
        $context = Context::getContext();

        if (!$context->customer || !$context->customer->isLogged()) {
            return '';
        }

        // Get configured group ID
        $targetGroupId = (int)Configuration::get('INTERNAUTENB2BINFO_GROUP_ID');

        // Check if customer's main (default) group matches target group
        if ((int)$context->customer->id_default_group !== $targetGroupId) {
            return '';
        }

        // Check if product has specific price/discount using multiple methods
        $hasSpecificPrice = false;

        // Method 1: Check reduction_type
        if (isset($presentedProduct->reduction_type) && !empty($presentedProduct->reduction_type)) {
            $hasSpecificPrice = true;
        }

        // Method 2: Check reduction value
        if (isset($presentedProduct->reduction) && $presentedProduct->reduction > 0) {
            $hasSpecificPrice = true;
        }

        // Method 3: Check specific_prices array
        if (isset($presentedProduct->specific_prices) && !empty($presentedProduct->specific_prices)) {
            $hasSpecificPrice = true;
        }

        // Method 4: Check has_discount on product object
        if (isset($presentedProduct->product) && is_object($presentedProduct->product) && !empty($presentedProduct->product->has_discount)) {
            $hasSpecificPrice = true;
        }

        $regularPrice = null;
        $message = null;

        // Do not show text if product has specific price
        if ($hasSpecificPrice) {
            // Prefer regular price data already prepared by PrestaShop presenter.
            if (isset($presentedProduct->regular_price) && is_scalar($presentedProduct->regular_price) && (string)$presentedProduct->regular_price !== '') {
                $regularPrice = (string)$presentedProduct->regular_price;
            } elseif (isset($presentedProduct->price_without_reduction) && is_numeric($presentedProduct->price_without_reduction)) {
                $regularPrice = Tools::displayPrice((float)$presentedProduct->price_without_reduction);
            }
        } else {
            // Get the language-specific message with fallback to default language.
            $message = Configuration::get('INTERNAUTENB2BINFO_GROUP_MESSAGE', (int)$context->language->id);
            if ($message === false || $message === null || $message === '') {
                $message = Configuration::get('INTERNAUTENB2BINFO_GROUP_MESSAGE', (int)Configuration::get('PS_LANG_DEFAULT'));
            }
        }

        // Assign variables to template
        $this->context->smarty->assign([
            'group_message' => $message,
            'regular_price' => $regularPrice,
            'debug_info' => null,
        ]);

        return $this->display(__FILE__, 'views/templates/hook/displayproductpriceblock.tpl');
    }
}
