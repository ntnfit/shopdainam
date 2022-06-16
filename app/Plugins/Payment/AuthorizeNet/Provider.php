<?php
/**
 * Provides everything needed for the Plugin
 */
    $this->loadTranslationsFrom(__DIR__.'/Lang', 'Plugins/Payment/AuthorizeNet');
    $this->loadViewsFrom(__DIR__.'/Views', 'Plugins/Payment/AuthorizeNet');

    if (sc_config('AuthorizeNet')) {
    $this->mergeConfigFrom(
        __DIR__.'/config.php', 'authorize'
    );
    }

