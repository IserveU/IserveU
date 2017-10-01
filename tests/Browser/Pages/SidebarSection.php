<?php

namespace Tests\Browser\Pages;

use Tests\DuskTools\Browser;

class SidebarSection extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/#/home';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param Browser $browser
     *
     * @return void
     */
    public function assert(Browser $browser)
    {
        parent::assert($browser);

    }

    //md-select.select_input__communities

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
          '@filterButton'                 => '.motion-filters__button',
          '@motionStatusFilterSelect'     => '.md-clickable .motion-filters__filter--status',
          '@motionDepartmentFilterSelect' => '.md-clickable .motion-filters__filter--department',
          '@motionOrderFilterSelect'      => '.md-clickable .motion-filters__filter--order',
        ];
    }
}
