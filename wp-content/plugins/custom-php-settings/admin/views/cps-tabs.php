<?php
$tabs = array(
    'general' => __('General', self::TEXT_DOMAIN),
    'settings' => __('Settings', self::TEXT_DOMAIN),
    'info' => __('PHP Information', self::TEXT_DOMAIN),
);
$variables_order= ini_get('variables_order');
$sub_tabs = array(
    'php-info' => __('PHP', self::TEXT_DOMAIN),
    'extensions' => __('Extensions', self::TEXT_DOMAIN),
);
if (strchr($variables_order, 'C')) {
    $sub_tabs['cookie-vars'] = __('COOKIE', self::TEXT_DOMAIN);
}
if (strchr($variables_order, 'G')) {
    $sub_tabs['get-vars'] = __('GET', self::TEXT_DOMAIN);
}
if (strchr($variables_order, 'P')) {
    $sub_tabs['post-vars'] = __('POST', self::TEXT_DOMAIN);
}
if (strchr($variables_order, 'S')) {
    $sub_tabs['server-vars'] = __('SERVER', self::TEXT_DOMAIN);
}
if (strchr($variables_order, 'E')) {
    $sub_tabs['env-vars'] = __('ENV', self::TEXT_DOMAIN);
}
?>
<?php
// @todo Update to support more than a single sub tab.
?>
<h2 class="nav-tab-wrapper">
    <?php foreach ($tabs as $key => $label) : ?>
        <?php $active = ($key == $this->getCurrentTab() ? ' nav-tab-active' : ''); ?>
        <a class="nav-tab<?php echo $active; ?>"
           href="<?php echo admin_url('tools.php?page=custom-php-settings&tab=' . $key); ?>"><?php echo $label; ?></a>
        <?php if ($active && $this->getCurrentTab() == 'info') : ?>
            <h3 class="nav-tab-wrapper">
                <?php foreach ($sub_tabs as $sub_key => $sub_label) : ?>
                    <?php $active = ($sub_key == $this->getCurrentSection() ? ' nav-tab-active' : ''); ?>
                    <a class="nav-tab nav-tab-small<?php echo $active; ?>"
                       href="<?php echo admin_url('tools.php?page=custom-php-settings&tab=' . $key . '&section=' . $sub_key); ?>"><?php echo $sub_label; ?></a>
                <?php endforeach; ?>
            </h3>
        <?php endif; ?>
    <?php endforeach; ?>
</h2>
