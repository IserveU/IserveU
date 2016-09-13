<?php

namespace App\Repositories\Contracts;

interface VisibilityModel
{

    /**
     * Skips the visibility checks on this model by setting whatever fields
     *  are always visible to be visible
     * @return null
     */
    public function skipVisibility();

    /**
     * Detects the correct visibility for the model based on permissions
     * @return null
     */
    public function setVisibility();

    /**
     * Contatined in StatusTrait
     * Gets the statuses considered visible by this model to the general public
     * @return Array gets the statuses that are visible
     */
    public static function visibleStatuses();


    /**
     * Contatined in StatusTrait
     * Gets the statuses considered hidden by this model to the general public
     * @return Array gets the statuses that are visible
     */
    public static function hiddenStatuses();


    
    /**
     * Get the public visibility of this model
     * @return boolean If the model is considered to be publically visible
     */
    public function getPubliclyVisibleAttribute();


    /**
     * Contatined in StatusTrait
     * Filters but the statuses considered visible by this model to the general public
     * @param Builder $query
     * @return  Builder
     */
    public function scopeVisible($query);


    /**
     * Contatined in StatusTrait
     * Gets the statuses considered visible by this model to the general public
     * @param   Builder $query
     * @return  Builder
     */
    public function scopeHidden($query);

}
