<?php
namespace CustomPhpSettings;

require_once CUSTOM_PHP_SETTINGS_PLUGIN_DIR . 'src/Common/Singleton.php';
require_once CUSTOM_PHP_SETTINGS_PLUGIN_DIR . 'src/Config/Settings.php';

use CustomPhpSettings\Common\Singleton;
use CustomPhpSettings\Config\Settings;

class CustomPhpSettings extends Singleton
{
    const VERSION = '1.2.2';
    const SETTINGS_NAME = 'custom_php_settings';
    const TEXT_DOMAIN = 'custom-php-settings';

    /**
     *
     * @var \src\config\Settings
     */
    private $settings;

    /**
     * Default settings.
     *
     * @var array
     */
    public static $default_settings = array(
        'version' => self::VERSION,
        'php_settings' => array(),
        'restore_config' => true,
    );

    /**
     * @var string $capability
     */
    private $capability = 'manage_options';

    /**
     * @var string $currentTab
     */
    private $currentTab = '';

    /**
     * @var string $currentSection
     */
    private $currentSection = '';

    /**
     * @var string $userIniFileName
     */
    private $userIniFileName = '';

    /**
     * @var int $userIniTTL
     */
    private $userIniTTL = 0;

    /**
     * Constructor.
     */
    protected function __construct()
    {
    }

    /**
     *
     */
    public function initialize()
    {
        // Allow people to change what capability is required to use this plugin.
        $this->capability = apply_filters('custom_php_settings_cap', $this->capability);

        $this->setTabs();
        $this->getIniDefaults();
        $this->addActions();
        $this->addFilters();
        $this->settings = new Settings(self::SETTINGS_NAME);
        $this->localize();
    }

    /**
     * Localize plugin.
     */
    protected function localize()
    {
        load_plugin_textdomain(self::TEXT_DOMAIN, false, dirname(plugin_basename(__FILE__)) . '/languages');
    }

    /**
     * Add actions.
     */
    public function addActions()
    {
        // add_action('admin_notices', array($this, 'addQuestion' ), 10);
        add_action('admin_menu', array($this, 'addMenu'));
        add_action('admin_init', array($this, 'handleFormSubmission'));
        add_action('admin_enqueue_scripts', array($this, 'addScripts'));
    }

    /**
     * Add filters.
     */
    public function addFilters()
    {
        add_filter('admin_footer_text', array($this, 'adminFooter'));
        add_filter('plugin_action_links', array($this, 'addActionLinks'), 10, 2);
    }

    /**
     * Adds admin notification
     */
    public function addQuestion()
    {
        echo '<div class="notice notice-info is-dismissible" style="position: relative;">';
        echo '</div>';
    }

    /**
     * Add action link on plugins page.
     *
     * @param array $links
     * @param string $file
     *
     * @return mixed
     */
    public function addActionLinks($links, $file)
    {
        $settings_link = '<a href="' . admin_url('tools.php?page=custom-php-settings') . '">' .
            __('Settings', self::TEXT_DOMAIN) .
            '</a>';
        if ($file == 'custom-php-settings/bootstrap.php') {
            array_unshift($links, $settings_link);
        }

        return $links;
    }

    /**
     * Add scripts.
     */
    public function addScripts()
    {
        // Added in wordpress 4.1.
        if (function_exists('wp_enqueue_code_editor')) {
            wp_enqueue_code_editor(array());
            wp_enqueue_script(
                'js-code-editor',
                plugin_dir_url(__FILE__) . 'js/code-editor.js',
                array('jquery'),
                '',
                true
            );
        }
        wp_enqueue_style('custom-php-settings', plugin_dir_url(__FILE__) . 'css/admin.css');
    }

    /**
     * Get settings for user INI filename and ttl.
     */
    protected function getIniDefaults()
    {
        $this->userIniFileName = ini_get('user_ini.filename');
        $this->userIniTTL = ini_get('user_ini.cache_ttl');
    }

    /**
     * Set active tab and section.
     */
    protected function setTabs()
    {
        $this->currentTab = isset($_GET['tab']) ? $_GET['tab'] : 'general';
        $this->currentSection = isset($_GET['section']) ? $_GET['section'] : 'php-info';
    }

    /**
     * Returns the active tab.
     *
     * @return string
     */
    protected function getCurrentTab()
    {
        return $this->currentTab;
    }

    /**
     * Returns the active section.
     *
     * @return string
     */
    protected function getCurrentSection()
    {
        return $this->currentSection;
    }

    /**
     * Check if any updates needs to be performed.
     * @todo verify so this really works as expected
     *   the update should only be done if not already done.
     *   The current setting will *ALWAYS* be < then the new version?
     */
    public static function activate()
    {
        // Load settings.
        $settings = new Settings(self::SETTINGS_NAME);

        if (version_compare($settings->get('version'), self::VERSION, '<')) {
            // Set defaults.
            foreach (self::$default_settings as $key => $value) {
                $settings->add($key, $value);
            }

            $settings->set('version', self::VERSION);

            // Store updated settings.
            $settings->save();
        }
    }

    /**
     * Triggered when plugin is deactivated.
     * Removes any changes in the .htaccess file made by the plugin.
     */
    public static function deActivate()
    {
        $settings = new Settings(self::SETTINGS_NAME);
        if ($settings->get('restore_config')) {
            $config_file = get_home_path() . '.htaccess';
            if (self::getCGIMode()) {
                $userIniFile = ini_get('user_ini.filename');
                $config_file = get_home_path() . 'wp-admin/' . $userIniFile;
            }
            insert_with_markers($config_file, 'CUSTOM PHP SETTINGS', array());
        }
    }

    /**
     * Uninstalls the plugin.
     */
    public function delete()
    {
        if ($this->settings->get('restore_config')) {
            $config_file = get_home_path() . '.htaccess';
            if (self::getCGIMode()) {
                $config_file = get_home_path() . 'wp-admin/' . $this->userIniFileName;
            }
            insert_with_markers($config_file, 'CUSTOM PHP SETTINGS', array());
        }
        $this->settings->delete();
    }

    /**
     * Adds customized text to footer in admin dashboard.
     *
     * @param string $footer_text
     *
     * @return string
     */
    public function adminFooter($footer_text)
    {
        $screen = get_current_screen();
        if ($screen->id == 'tools_page_custom-php-settings') {
            $rate_text = sprintf(
                __('Thank you for using <a href="%1$s" target="_blank">Custom PHP Settings</a>! Please <a href="%2$s" target="_blank">rate us on WordPress.org</a>', self::TEXT_DOMAIN),
                'https://wordpress.org/plugins/custom-php-settings',
                'https://wordpress.org/support/plugin/custom-php-settings/reviews/?rate=5#new-post'
            );

            return '<span>' . $rate_text . '</span>';
        } else {
            return $footer_text;
        }
    }

    /**
     * Add menu item for plugin.
     */
    public function addMenu()
    {
        add_submenu_page(
            'tools.php',
            __('Custom PHP Settings', self::TEXT_DOMAIN),
            __('Custom PHP Settings', self::TEXT_DOMAIN),
            $this->capability,
            'custom-php-settings',
            array($this, 'displaySettingsPage')
        );
    }

    /**
     * Add message to be displayed in settings form.
     *
     * @param string $message
     * @param string $type
     */
    protected function addSettingsMessage($message, $type = 'error')
    {
        add_settings_error(
            'custom-php-settings',
            esc_attr('custom-php-settings-updated'),
            $message,
            $type
        );
    }

    /**
     * Check if PHP is running in CGI/Fast-CGI mode or not.
     *
     * @return bool
     */
    protected static function getCGIMode()
    {
        $sapi = php_sapi_name();
        return substr($sapi, -3) === 'cgi';
    }

    /**
     * Gets an array of settings to insert into configuration file.
     *
     * @return array
     */
    protected function getSettingsAsArray()
    {
        $cgiMode = $this->getCGIMode();
        $section = array();
        $errors = '';
        foreach ($this->settings->php_settings as $key => $value) {
            if (!empty($value)) {
                $setting = explode('=', trim($value));
                if (count($setting) == 2 && strlen($setting[0]) && strlen($setting[1])) {
                    if ($cgiMode) {
                        $section[] = $setting[0] . '=' . $setting[1];
                    } else {
                        $section[] = 'php_value ' . $setting[0] . ' ' . $setting[1];
                    }
                } else {
                    if (!strlen($setting[0])) {
                        $errors .= __('All settings must be in the format', self::TEXT_DOMAIN) . ' key=value<br/>';
                    } elseif (strpos($setting[0], '#')) {
                        $errors .= sprintf(__('Setting %s is not in a valid format', self::TEXT_DOMAIN), $setting[0]) . '<br />';
                    }
                }
            } else {
                $section[] = '';
            }
        }
        if (!empty($errors)) {
            $this->addSettingsMessage($errors);
        }
        return $section;
    }

    /**
     * Adds custom php settings to .htaccess file.
     */
    protected function updateHtAccessFile()
    {
        $htaccess_file = get_home_path() . '.htaccess';
        if ($this->createIfNotExist($htaccess_file) === false) {
            $this->addSettingsMessage(sprintf(__('%s does not exists or is not writable.', self::TEXT_DOMAIN), $htaccess_file));
            return;
        }
        $section = $this->getSettingsAsArray();
        $this->addSettingsMessage(__('Settings updated and stored in .htaccess', self::TEXT_DOMAIN), 'updated');
        insert_with_markers($htaccess_file, 'CUSTOM PHP SETTINGS', $section);
    }

    /**
     * Adds custom php settings to user INI file.
     */
    protected function updateIniFile()
    {
        $ini_file = get_home_path() . 'wp-admin/' . $this->userIniFileName;
        if ($this->createIfNotExist($ini_file) === false) {
            $this->addSettingsMessage(sprintf(__('%s does not exists or is not writable.', self::TEXT_DOMAIN), $ini_file));
            return;
        }
        $section = $this->getSettingsAsArray();
        $message = sprintf(__('Settings updated and stored in wp-admin/%s.', self::TEXT_DOMAIN), $this->userIniFileName);
        $this->addSettingsMessage($message, 'updated');
        insert_with_markers($ini_file, 'CUSTOM PHP SETTINGS', $section);
    }
    /**
     *
     */
    protected function updateConfigFile()
    {
        if (self::getCGIMode()) {
            $this->updateIniFile();
        } else {
            $this->updateHtAccessFile();
        }
    }

    /**
     * Check so file exists and is writable.
     *
     * @param string $filename
     *
     * @return bool
     */
    protected function createIfNotExist($filename)
    {
        if (!file_exists($filename)) {
            if (!is_writable(dirname($filename))) {
                return false;
            }
            if (!touch($filename)) {
                return false;
            }
        } elseif (!is_writeable($filename)) {
            return false;
        }
    }

    /**
     * Validates so a line is either a comment, blank line or a valid setting.
     *
     * @param string $setting
     *
     * @return int
     */
    protected function validSetting($setting)
    {
        $iniSettings = array_keys($this->getIniSettings());
        $setting = explode('=', $setting);
        if (count($setting) == 1 && strpos($setting[0], '#') !== false) {
            // This is a comment.
            return 1;
        }
        if (count($setting) == 1 && strlen($setting[0]) === 0) {
            // This is a blank line.
            return 1;
        }
        if (count($setting) == 2 && in_array($setting[0], $iniSettings)) {
            return 1;
        }
        return 0;
    }

    /**
     * Handle form data for configuration page.
     * @todo Better handling of settings during save.
     *
     * @return bool
     */
    public function handleFormSubmission()
    {
        // Check if settings form is submitted.
        if (filter_input(INPUT_POST, 'custom-php-settings', FILTER_SANITIZE_STRING)) {
            // Validate so user has correct privileges.
            if (!current_user_can($this->capability)) {
                die(__('You are not allowed to perform this action.', self::TEXT_DOMAIN));
            }

            // Verify nonce and referer.
            if (check_admin_referer('custom-php-settings-action', 'custom-php-settings-nonce')) {
                $errors = '';
                // Filter and sanitize form values.
                $raw_settings = filter_input(
                    INPUT_POST,
                    'php-settings',
                    FILTER_SANITIZE_STRING
                );
                $raw_settings = rtrim($raw_settings);
                $raw_settings = explode(PHP_EOL, $raw_settings);
                $raw_settings = array_map('trim', $raw_settings);
                $settings = array();
                foreach ($raw_settings as $key => $value) {
                    if ($this->validSetting($value)) {
                        $settings[$key] = str_replace(';', '', $value);
                    } else {
                        $setting = explode('=', $value);
                        $errors .= sprintf(__('%s is not a valid setting.', self::TEXT_DOMAIN), $setting[0]) . '<br />';
                    }
                }
                if (!empty($errors)) {
                    $this->addSettingsMessage($errors);
                }
                $this->settings->set('php_settings', $settings);

                $this->updateConfigFile();

                $this->settings->restore_config = filter_input(
                    INPUT_POST,
                    'restore-config',
                    FILTER_VALIDATE_BOOLEAN
                );

                return $this->settings->save();
            }
        }
    }

    /**
     * Get all non-system settings.
     *
     * @return array
     */
    protected function getIniSettings()
    {
        return array_filter(ini_get_all(), function ($item) {
            return ($item['access'] !== INI_SYSTEM);
        });
    }

    /**
     * Display the settings page.
     */
    public function displaySettingsPage()
    {
        if ($this->getCurrentTab() === 'settings') {
            require_once __DIR__ . '/views/cps-settings-table.php';
        }
        if ($this->getCurrentTab() === 'info' && $this->getCurrentSection()) {
            $template = __DIR__ . '/views/cps-' . $this->currentSection . '.php';
            if (file_exists($template)) {
                require_once $template;
            }
        }
        if ($this->getCurrentTab() === 'general') {
            require_once __DIR__ . '/views/cps-general.php';
        }
    }
}
