// -----------------------------
// Config Symfony2 Console
// https://www.npmjs.org/package/grunt-symfony2
// Grunt plugin for running Symfony2 commands
// -----------------------------

module.exports = {
        options: {
            bin: 'app/console'
        },
        cache_clear_prod: {
            cmd: 'cache:clear',
            args: {
                env: 'prod'
            }
        },
        cache_clear_dev: {
            cmd: 'cache:clear',
            args: {
                env: 'dev'
            }
        },
        cache_warmup_prod: {
            cmd: 'cache:warmup',
            args: {
                env: 'prod'
            }
        },
        cache_warmup_dev: {
            cmd: 'cache:warmup',
            args: {
                env: 'dev'
            }
        },
        doctrine_schema_drop: {
            cmd: 'doctrine:schema:drop',
            args: {
                env: 'dev',
                force: true
            }
        },
        doctrine_schema_create: {
            cmd: 'doctrine:schema:create',
            args: {
                env: 'dev'
            }
        },
        doctrine_fixtures_load: {
            cmd: 'doctrine:fixtures:load',
            args: {
                env: 'dev',
                append: true
            }
        },
        doctrine_schema_update: {
            cmd: 'doctrine:schema:update',
            args: {
                force: true
            }
        },
        doctrine_schema_validate: {
            cmd: 'doctrine:schema:validate',
            args: {
            }
        },
        sonata_media_sync_default: {
            cmd: 'sonata:media:sync sonata.media.provider.image default'
        },
        sonata_media_sync_intro: {
            cmd: 'sonata:media:sync sonata.media.provider.image intro'
        },
        sonata_media_sync_bgimage: {
            cmd: 'sonata:media:sync sonata.media.provider.image bgimage'
        },
        sonata_media_sync_icon: {
            cmd: 'sonata:media:sync sonata.media.provider.image icon'
        },
        sonata_media_sync_admin: {
            cmd: 'sonata:media:sync sonata.media.provider.image admin'
        },
        assetic_dump_dev: {
            cmd: 'assetic:dump',
            args: {
                env: 'dev'
            }
        },
        assetic_dump_prod: {
            cmd: 'assetic:dump',
            args: {
                env: 'prod'
            }
        },
        twig_lint: {
            cmd: 'twig:lint',
            args: {
            }
        }
};
