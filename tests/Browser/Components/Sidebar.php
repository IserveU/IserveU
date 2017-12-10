<?php

namespace Tests\Browser\Components;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;

class Sidebar extends BaseComponent
{
    /**
     * Get the root selector for the component.
     *
     * @return string
     */
    public function selector()
    {
        return 'sidebar';
    }

    /**
     * Assert that the browser page contains the component.
     *
     * @param Browser $browser
     *
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertVisible($this->selector());
    }

    /**
     * Get the element shortcuts for the component.
     *
     * @return array
     */
    public function elements()
    {
        return [

        ];
    }

    public function clickRandomMenuItem($browser)
    {
        $sidebarItems = $this->getSidebarItems($browser);

        $sidebarMotions = [];
        foreach ($sidebarItems as $sidebarItem) {
            if (!empty($sidebarItem->getText())) {
                $sidebarMotions[] = $sidebarItem;
            }
        }

        $randomMotion = $sidebarMotions[array_rand($sidebarMotions)];

        $randomMotion->click();
        $browser->waitForText($randomMotion->getText());
    }

    private function getSidebarItems($browser)
    {
        $browser->waitFor('md-list-item');

        return $this->sidebarItems = $browser->resolver->all('md-list-item');
    }
}
