<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class VotingTest extends TestCase 
{

    /*****************************************************************
    *
    *   Tests to run complex voting trees. For example: 
    *	- checking that overall votes represents the amount in the database.
    *	- motions that cannot be voted on cannot (closed by status or by time)
    *	- users that cannot vote cannot; under complex operations:
    *		- user is verified, votes and then is unverified and cannot vote
    *		- votes are removed once unverified
    *		- make sure user is notified (email system)
    *	- deferred votes get cast at the right time with the right percentage
    *	- deferred votes are represented correctly
    *
    ******************************************************************/




}