<?php

/*
 * This file is part of the ApoutchikaMediaBundle package.
 *
 * @author Julien Philippon <juphilippon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Apoutchika\MediaBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class ApoutchikaMediaExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        if (!isset ($config['context']['default'])) {
            // set default extensions for 
            // default context and trusted extensions
            // TODO: manage it in configuration file !
            $config['contexts']['default'] = array(
                // Documents
                'doc', 'xls', 'txt', 'pdf', 'rtf', 'docx', 'xlsx', 'ppt', 'pptx', 'odt', 'odg', 'odp', 'ods', 'odc', 'odf', 'odb', 'csv', 'xml',

                // Images
                'gif', 'jpg', 'jpeg', 'png', 'svg',

                // Audio
                'mp3', 'ogg',

                // Video
                'mp4', 'avi', 'mpg', 'mpeg', 'ogv', 'webm',

                // Archive
                'zip', 'tar', 'gz', '7z', 'rar',
            );
        }

        $this->addParameters($container, array(
            'include' => $config['include'],
            'filesystems' => $config['filesystems'],
            'original_dir' => $config['original_dir'],
            'media_class' => $config['media_class'],
            'limit' => $config['limit'],
            'css' => $config['css'],
            'alias' => $config['alias'],
            'contexts' => $config['contexts'],
            'driver' => $config['driver'],
            'trusted_extensions' => $config['trusted_extensions'],
        ));

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    /**
     * Add parameter in container.
     *
     * @param ContainerBuilder $container
     * @param array            $parameter List of parameters with keys / values (without base parameter name)
     */
    private function addParameters(ContainerBuilder $container, array $parameters)
    {
        foreach ($parameters as $key => $value) {
            $container->setParameter('apoutchika_media.'.$key, $value);
        }
    }
}
