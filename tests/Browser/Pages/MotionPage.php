<?php

namespace Tests\Browser\Pages;

use Tests\DuskTools\Browser;

class MotionPage extends Page
{
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

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
  '@title'                       => '.motion_header__title',
  '@summary'                     => '.motion_header__summary',
  '@department'                  => '.motion_tiles__department',
  '@departmentIcon'              => '.motion_tiles__department md-icon',
  '@closing'                     => '.motion_tiles__closing',
  '@passingStatus'               => '.motion_tiles__passing-status',
  '@passingStatusIcon'           => '#passing_status_icon',
  '@motionText'                  => '.motion_details__text',
  '@motionFiles'                 => '.motion_files',
  '@buttonAgree'                 => '.motion_vote_buttons__button--agree button',
  '@buttonDisagree'              => '.motion_vote_buttons__button--disagree button',
  '@buttonAbstain'               => '.motion_vote_buttons__button--abstain button',
  '@buttonDisabled'              => '.motion_vote_buttons__button--disabled button',

  '@voteStatusbar'              => 'motion-vote-statusbar',
  '@voteStatusbarAgree'         => 'motion-vote-statusbar .motion_vote_statusbar__bar--agree',
  '@voteStatusbarDisagree'      => 'motion-vote-statusbar .motion_vote_statusbar__bar--disagree',
  '@voteStatusbarAbstain'       => 'motion-vote-statusbar .motion_vote_statusbar__bar--abstain',
  '@userCommentTitle'           => 'h2.motion_usercomment__title',
  '@userComment'                => '.motion_usercomment__input textarea',
  '@userCommentSave'            => '.motion_usercomment__button--save',
  '@commentsAgree'              => 'comment-list-agree',
  '@commentsDisagree'           => 'comment-list-disagree',
  '@commentsAbstain'            => 'comment-list-abstain',
  '@commentListAgreeButton'     => 'md-tab-item:first-of-type',
  '@commentListDisagreeButton'  => 'md-tab-item:last-of-type',
        ];
    }
}
