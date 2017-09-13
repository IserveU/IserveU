<?php

namespace Tests\Browser\Pages;

use Tests\DuskTools\Page as BasePage;

abstract class Page extends BasePage
{
    /**
     * Get the global element shortcuts for the site.
     *
     * @return array
     */
    public static function siteElements()
    {
        return [
            '@termsAndConditions' => 'md-dialog.terms-and-conditions',
            '@sidebarLinks'       => 'sidebar md-list-item',
            '@fab'                => 'md-fab-trigger',
        ];
    }
}
