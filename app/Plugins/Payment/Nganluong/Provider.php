<?php
/**
 * Provides everything needed for the Plugin
 */
    $this->loadTranslationsFrom(__DIR__.'/Lang', 'Plugins/Payment/Nganluong');
    $this->loadViewsFrom(__DIR__.'/Views', 'Plugins/Payment/Nganluong');

    if (sc_config('Nganluong')) {
      $this->mergeConfigFrom(
         __DIR__.'/config.php', 'Nganluong'
      );
    }
