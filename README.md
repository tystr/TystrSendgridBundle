TystrSendgridBundle
========================

[![Build Status](https://travis-ci.org/tystr/TystrSendgridBundle.svg?branch=master)](https://travis-ci.org/tystr/TystrSendgridBundle)

A simple bundle for integrating the sendgrid php library into the Symfony2 framework.

Installation
----------------------------------

### Via Composer

Simply add the following to your project's composer.json file:

    "tystr/sendgrid-bundle": "*"

Configuration
-------------

Add the following to your configuration file:

    tystr_sendgrid_bundle:
        username: YOUR_SENDGRID_USERNAME
        password: YOUR_SENDGRID_PASSWORD
        
Usage
------

Retrieve the service like so:

    $sendgrid = $this->get('tystr_sendgrid.sendgrid');
    
    
See the [Sendgrid Documentation](https://github.com/sendgrid/sendgrid-php/blob/master/README.md#usage)
for more information.
