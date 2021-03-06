# Laravel Forum
A drop-in forum module for Laravel 5.

Code Style: [![Build Status](https://travis-ci.org/taskforcedev/laravel-forum.svg?branch=master)](https://travis-ci.org/taskforcedev/laravel-forum) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/taskforcedev/laravel-forum/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/taskforcedev/laravel-forum/?branch=master)

Integration Testing:

Laravel 5.3: [![Build Status](https://travis-ci.org/taskforcedev-testing/laravel-53-forum.svg?branch=master)](https://travis-ci.org/taskforcedev-testing/laravel-53-forum)

## Requirements
 - Laravel 5.2+
 - jQuery is currently required for forum moderation features (lock/sticky).

## What this package provides
 - Fully Functional Forum (/forum)
 - Admin Interface (/admin/forums)

 - Database Tables
   - forums
   - forum_posts
   - forum_categories
   - forum_post_replies

## Installation

### Step 1: Add the following package to your composer.json require (if not already present).

Laravel 5.2:

    require {
      "taskforcedev/laravel-forum": "1.0.2"
    }
    
Laravel 5.3+:

    require {
        "taskforcedev/laravel-forum": "dev-master"
    }

In development (steps for 5.0-5.2 may work as long as you dont require a package requiring the newer laravel-support package).

### Step 2: Run composer update

    composer update

### Step 3: Add the following service provider(s) to config/app.php (if not already present)

    'providers' => [
        Taskforcedev\LaravelSupport\ServiceProvider::class,
        Taskforcedev\LaravelForum\ServiceProvider::class,
    ]

### Step 4: Migrate

Run the migrations

    php artisan migrate

### Step 5: Publish Config.
If you haven't previously published the config from the LaravelSupport package please do this also with the following command:

    php artisan vendor:publish --tag="taskforce-support"

### Step 6: Edit config.
If you haven't previously edited the config/taskforce-support.php file please add your sites details into this.

    'layout' => 'layouts.master',
    'sitename' => 'Your Site Name',

## Usage

Once you have done the installation steps above in order to setup the forums for public use you must first create at least one category and a forum, you do this by visiting

    /admin/forums

Once you have done this you can add the link to <code>/forum</code> into your sites navigation as you please.

## Administration / Moderation
In order to provide administrators access to add/edit/manage the forums we use laravel policies.

The following must return true for a user to be able to visit the admin section:

    $user->can('create', Forum::class);

We recommended you have a policy which proves a user has administration powers on your application:

see: https://laravel.com/docs/5.3/authorization#creating-policies

Then you can add the following to your app/Providers/AuthServiceProvider.php file

    use Taskforcedev\LaravelForum\Models\Forum;
    use Taskforcedev\LaravelForum\Models\ForumCategory;

Then apply your admin policy to each of the models [example:]

    protected $policies = [
        ...
        
        Forum::class => AdminPolicy::class,
        ForumCategory::class => AdminPolicy::class,
    ];

As it stands any authenticated user is able to post or reply in any forum.

## Events

The following events are fired within the package and can be listened for in your main application.

 - Taskforcedev\LaravelForum\Events\PostCreated
 - Taskforcedev\LaravelForum\Events\PostReply

## Contributing / Feedback
 - We welcome any pull requests, please ensure code is to the PSR-2 Standard.
 - We check github issues frequently so please feel free to raise any comments or feedback there.

## Versioning

Version 1.* follows the semantic version numbering system.

Future versions will use the laravel major and minor version numbers followed by release number.