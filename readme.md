rules:
    customise_accessories_family_images:
        priority: 0
        conditions:
            -
                field: family
                operator: IN
                value:
                    - accessories
        actions:
            -
                attributes: ['image_1']
                field: image_2
                width: 200
                height: 200
                options:
                    scope: null
                    locale: null
                type: imagecustomisation

use above rules engine configuration place it in one file and import that file in akeneo dev box using this import function "ImportToYaml" and rules engine get created.

before that please use below script to be run use it in root folder of project.

sed -i '/new Sitation\\CustomRuleBundle\\SitationCustomRuleBundle(),/d' app/AppKernel.php;
sed -i '/your app bundles should be registered here/a new Sitation\\CustomRuleBundle\\SitationCustomRuleBundle(),' app/AppKernel.php;
composer config repositories.repo-name vcs https://github.com/wissenddevops/sitationrulesenginecustom.git;
composer require "wissenddevops/sitationrulesenginecustom";
bin/console sitation:addrouterulesenginecustom;
bin/console pim:installer:dump-require-paths; 
bin/console pim:install:assets;
yarn run less;
yarn run webpack;
bin/console cache:clear;