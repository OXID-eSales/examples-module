# OXID eShop Examples Module

[![Development](https://github.com/OXID-eSales/examples-module/actions/workflows/trigger.yaml/badge.svg?branch=b-7.3.x)](https://github.com/OXID-eSales/examples-module/actions/workflows/trigger.yaml)
[![Latest Version](https://img.shields.io/packagist/v/OXID-eSales/examples-module?logo=composer&label=latest&include_prereleases&color=orange)](https://packagist.org/packages/oxid-esales/examples-module)
[![PHP Version](https://img.shields.io/packagist/php-v/oxid-esales/examples-module)](https://github.com/oxid-esales/examples-module)

[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=OXID-eSales_examples-module&metric=alert_status)](https://sonarcloud.io/dashboard?id=OXID-eSales_examples-module)
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=OXID-eSales_examples-module&metric=coverage)](https://sonarcloud.io/dashboard?id=OXID-eSales_examples-module)
[![Technical Debt](https://sonarcloud.io/api/project_badges/measure?project=OXID-eSales_examples-module&metric=sqale_index)](https://sonarcloud.io/dashboard?id=OXID-eSales_examples-module)


The examples module contains examples for the most common use cases (see below)
like OXID suggests it could be implemented.

This module also comes with all the quality tools OXID recommends to use.

## Table of contents
1. [Branch compatibility](#branch-compatibility)
2. [The Idea](#the-idea)
3. [Goals](#goals)
4. [Examples](#examples)
5. [Install and try it out](#install-and-try-it-out)
6. [Development installation](#development-installation)
7. [Development installation on OXID eShop SDK](#development-installation-on-oxid-eshop-sdk)
8. [Things to be aware of](#things-to-be-aware-of)
9. [Running tests and quality tools](#running-tests-and-quality-tools)
10. [Additional info](#additional-info)

## Branch compatibility

* b-7.3.x branch - compatible with OXID eShop compilation 7.3.x and the respective branch

## The Idea

OXID eSales would like to provide a lightweight reusable example module incorporating
our best practices recommendations to be used for developing own module solutions.

Story:
- Module will extend a block on shop start page to show a greeting message (visible when module is active).
- Module will have a setting to switch between generic greeting message for a logged in user and a personal custom greeting. The Admin's choice which way it will be.
- A logged in user will be able to set a custom greeting depending on module setting. Press the button on start page and be redirected to a module controller which handles the input.
- User custom greetings are saved via shop model save method. We subscribe to BeforeModelUpdate to track how often a user changed his personal greeting.
- Tracking of this information will be done in a new database table to serve as an example for module's own shop model.
- Module will extend the shop's basket model to add info to module specific log file when an item is added into basket. Logging  can be enabled or disabled depending on module setting.
- Module will have console command `oeexamples:logger:read` to read log file.

```bash
./vendor/bin/oe-console oeexamples:logger:read
```

## Goals

Install and try out the module with simple examples to most common development questions.

## Examples

The repository contains examples of following cases and more:

* [Extending of shop controllers and models](https://github.com/OXID-eSales/examples-module/blob/b-7.3.x/metadata.php#L25)
  * extending a shop model (`OxidEsales\ExamplesModule\Extension\Model\User`) / (`OxidEsales\ExamplesModule\Extension\Model\Basket`)
  * extending a shop controller (`OxidEsales\ExamplesModule\Extension\Controller\StartController`)

* [Controllers as service](https://github.com/OXID-eSales/examples-module/blob/b-7.3.x/src/Greeting/services.yaml#L28)
  * own module controller (`oeem_greeting` with own template and own translations)
  * own module admin controller (`oeem_admin_greeting` with own template and own translations)

* [Using Symfony DI](https://github.com/OXID-eSales/examples-module/blob/b-7.3.x/services.yaml)
  * [Injection of Registry classes with bind](https://github.com/OXID-eSales/examples-module/blob/b-7.3.x/src/Greeting/services.yaml#L5)

* [Migrations](https://github.com/OXID-eSales/examples-module/tree/b-7.3.x/migration)
  * extending a shop database table (`oxuser`)

* Accessing the database
  * model with a database (`OxidEsales\ExamplesModule\Tracker\Model\GreetingTracker`)
  * ``oxNew`` object factory example (`OxidEsales\ExamplesModule\Greeting\Infrastructure\UserModelFactory`)
  * [DAO](src/ProductVote/Dao)

* [Various types of module settings](https://github.com/OXID-eSales/examples-module/blob/b-7.3.x/metadata.php#L38)

* Templates
  * [creating templates for your module](https://github.com/OXID-eSales/examples-module/blob/b-7.3.x/views/twig/templates/greetingtemplate.html.twig)
  * [extending of oxid theme templates or blocks](https://github.com/OXID-eSales/examples-module/tree/b-7.3.x/views/twig/extensions/themes)
    * extending a shop admin template block (`admin_user_main_form` - only an extension of a block, without functionality)
    * extending a shop template block (`start_newest_articles`)

* Using the translations for your module specific phrases
  * [in admin](https://github.com/OXID-eSales/examples-module/tree/b-7.3.x/views/admin_twig)
  * [in frontend](https://github.com/OXID-eSales/examples-module/tree/b-7.3.x/translations)

* Events and listeners
  * [Subscribing to shop events](https://github.com/OXID-eSales/examples-module/blob/b-7.3.x/src/Tracker/Subscriber/BeforeModelUpdate.php)

* Testing your module backend and frontend part
  * [Composer aliases for easy running of tests and quality tools](https://github.com/OXID-eSales/examples-module/blob/b-7.3.x/composer.json#L48)
  * [Using the github actions as CI tool with all recommended tools preconfigured for you.](https://github.com/OXID-eSales/examples-module/tree/b-7.3.x/.github)

* [Using variables from .env file](https://github.com/OXID-eSales/examples-module/tree/b-7.3.x/.env)
  * [Access via `getenv()` function](https://github.com/OXID-eSales/examples-module/tree/b-7.3.x/src/Extension/Controller/StartController.php)
    * Note: Changes to environment variables take effect immediately — no cache clearing is required.
  * [Access via DI container](https://github.com/OXID-eSales/examples-module/tree/b-7.3.x/src/Greeting/services.yaml)
    * Note: After updating environment variables, you must clear the cache for changes to take effect.

**HINTS**:
* Only extend the shop core if there is no other way like listen and handle shop events,
  decorate/replace some DI service.
* Your module might be one of many in the class chain and you should act accordingly (always ensure
  to call the parent method and return the result).
* When extending shop classes with additional methods, best prefix those methods in order to not end
  up with another module picking the same method name and wreacking havoc.
* In case there is no other way than to extend existing shop methods try the minimal invasion principle.
  Put module business logic to a service (which make it easier to test as well) and call the service in the extended shop class.
  If you need to extend the shop class chain by overwriting, try to stick to the public methods.

#### Not yet in here but might come later:
* example for payment gateway extension
* seo url for module controller
* to redirect or not to redirect from inside the shop core
* graphql query/mutation example
* extending the internal part

## Install and try it out

Note: This installation method fits for trying out the module development basics,
its not meant to be used as development base for your own module. Check further
installation/usage methods.

This module is in working state and can be directly installed via composer:
```
composer require oxid-esales/examples-module
./vendor/bin/oe-eshop-doctrine_migration migrations:migrate oe_examples_module
```

and [activate the module](https://docs.oxid-esales.com/developer/en/latest/development/modules_components_themes/module/installation_setup/setup.html#setup-activation).

## Development installation

To be able running the tests and other preconfigured quality tools, please install the module as a [root package](https://getcomposer.org/doc/04-schema.md#root-package).

The next section shows how to install the module as a root package by using the OXID eShop SDK.

In case of different environment usage, please adjust by your own needs.

## Development installation on OXID eShop SDK

The installation instructions below are shown for the current [SDK](https://github.com/OXID-eSales/docker-eshop-sdk)
for shop 7.3. Make sure your system meets the requirements of the SDK.

0. Ensure all docker containers are down to avoid port conflicts

1. Clone the SDK for the new project
```shell
echo MyProject && git clone https://github.com/OXID-eSales/docker-eshop-sdk.git $_ && cd $_
```

2. Clone the repository to the source directory
```shell
git clone --recurse-submodules https://github.com/OXID-eSales/examples-module.git --branch=b-7.3.x ./source
```

3. Run the recipe to setup the development environment, you can decide which shop edition to install. Omitting the flag installs EE.
```shell
./source/recipes/setup-development.sh -s CE
```

You should be able to access the shop with http://localhost.local and the admin panel with http://localhost.local/admin
(credentials: noreply@oxid-esales.com / admin)

## Things to be aware of

The examples module is intended to act as a tutorial module so keep your eyes open for comments in the code.

**NOTES:**
* Acceptance tests are way easier to write if you put an id on relevant fields and buttons in the templates.
* If you can, try to develop on OXID eShop Enterprise Edition to get shop aware stuff right from the start.

### Module migrations

* migrations are intended to bump the database (and eventual existing data) to a new module version (this also goes for first time installation).
* ensure migrations are stable against rerun

Migrations have to be run via console command (`./vendor/bin/oe-eshop-doctrine_migration`)

```bash
./vendor/bin/oe-eshop-doctrine_migration migrations:migrate oe_examples_module
```

NOTE: Existing migrations must not be changed. If the database needs a change, add a new migration file and change to your needs:

```bash
./vendor/bin/oe-eshop-doctrine_migration migrations:generate oe_examples_module
```

For more information, check the [developer documentation](https://docs.oxid-esales.com/developer/en/latest/development/tell_me_about/migrations.html).


### Where the module namespace points to
As already mentioned above, in the 7.x versions of OXID eShop, the module code only resides in the vendor directory so the
namespace needs to point there. In our case this looks like

```bash
    "autoload": {
        "psr-4": {
            "OxidEsales\\ExamplesModule\\": "src/"
        }
    },
    "autoload-dev": {
        "psr-4": {
            "OxidEsales\\ExamplesModule\\Tests\\": "tests/",
            "OxidEsales\\EshopCommunity\\Tests\\": "./vendor/oxid-esales/oxideshop-ce/tests"
        }
    },
```


## Running tests and quality tools

Check the ``scripts`` section in the composer.json of the module to get more insight of
preconfigured quality tools available. Example:

```bash
$ composer phpcs
$ composer phpstan
$ composer phpmd
```

### Integration/Acceptance tests

- install this module into a running OXID eShop
- run `composer update` in module root directory

```bash
$ composer update
```

After this done, check the "scripts" section of module `composer.json` file to see how we run tests.

```bash
$ composer tests-unit
$ composer tests-integration
$ composer tests-codeception
```

NOTE: From OXID eShop 7.0.x on database reset needs to be done with this command (please fill in your credentials)

```bash
$ bin/oe-console oe:database:reset --db-host=mysql --db-port=3306 --db-name=example --db-user=root --db-password=root --force
```

And just in case you need it, admin user can now also be created via commandline
```bash
$ bin/oe-console oe:admin:create-user --admin-email <admin-email> --admin-passowrd <admin-password>
```
for example
```bash
$ bin/oe-console oe:admin:create-user --admin-email admin@oxid-esales.com --admin-password admin
```


### Writing Codeception tests

As a rule of thumb, use codeception tests to ensure the frontend is behaving as expected.
Codeception tests take a while to run, so try to navigate the way between covering the relevant
cases and overtesting.

We definitely need some acceptance tests if the module affects the
frontend like in our example. If the module breaks the frontend, we need to see it asap.

In our case, we cover the reaction of the startpage to the different possibilities
* generic greeting mode (with/without logged in user)
* personal greeting mode (with/without logged in user)
* updating the greeting mode
* ensure module can be activated/deactivated without destroying the shop
* ensure edge case safety like not logged in user directly calling module controller

The great thing about codeception tests is - they can create screenshot and html
output in failure case, so you literally get a picture of the fail (`tests/Coreception/_output/`).

### Github Actions Workflow

The examples-module comes complete with a github actions workflow. No need to rig up some separate continuous integration
infrastructure to run tests, it's all there in [github](https://github.com/OXID-eSales/examples-module/actions).
You will see three files in `.github/workflow` directory. The workflow from
`.github/workflow/trigger.yaml` starts on every `push` and `pull_request` to run the code quality checks and all the module tests.

In our experience it is useful to run the shop tests with the module installed and activated from time to time.
For sure those shop tests have been written with only the shop itself in mind. Your module, depending on what it is doing,
might completely change the shop behaviour. Which means those shop tests with a module might just explode in your face.
Which is totally fine, as long as you can always explain WHY those tests are failing.

Real life example:  There is one shop acceptance test case `OxidEsales\EshopCommunity\Tests\Acceptance\Frontend\ShopSetUpTest:`
which is testing the frontend shop setup. Very good chance this test will fail if a module is around which extends
the class chain. That test is for setting up a shop from scratch so it will simply not expect a module to be around.
And we only need our module to safely work with a working shop. We definitely will decide to skip that `ShopSetUpTest`
as we have a good explanation as to why it will not work. And having this special test case work with our module will give no benefit.

This is only one example, there might be other tests that fail with your module but fail because your module is changing the shop.
In that case the suggestion would be to exclude the original test from the github actions run, copy that test case to your module tests and
update to work with your module. This was for example the strategy used for our reverse proxy modules which are mandatory to not make the shop's
acceptance tests fail. Unless those test cases that somehow bypass reverse proxy cache invalidation. To be on the safe side, we took over those
few test cases to the module and plan to improve the shop tests as soon as possible. We'll gladly also take your PR with improved shop tests ;)

And then there are some few shop tests marked as `@group quarantine` in the doc block. Test in that group have stability issues so they'd better
be excluded as well.

Ps: a failing shop test might also turn up issues in your module, in that case fix the module and let the test live ;)

## Additional info

### Useful links

* Vendor home page - https://www.oxid-esales.com
* Bug tracker - https://bugs.oxid-esales.com
* Developer Documentation - https://docs.oxid-esales.com/developer/en/latest/
* Quality Tools and Requirements - https://docs.oxid-esales.com/developer/en/latest/development/modules_components_themes/quality.html
* Docker SDK - https://github.com/OXID-eSales/docker-eshop-sdk

### Contact us

* In case of issues / bugs, use "Issues" section on github, to report the problem.
* [Join our community forum](https://forum.oxid-esales.com/)
* [Use the contact form](https://www.oxid-esales.com/en/contact/contact-us.html)

In case you have any complaints, suggestions, business cases you'd like an example for
please contact us. Pull request are also welcome.  Every feedback we get will help us improve.
