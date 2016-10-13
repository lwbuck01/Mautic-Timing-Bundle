<?php
/**
 * @package     ThirdSetMauticTimingBundle
 * @copyright   2016 Third Set Productions. All rights reserved.
 * @author      Third Set Productions
 * @link        http://www.thirdset.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\ThirdSetMauticTimingBundle;

use Mautic\PluginBundle\Bundle\PluginBundleBase;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

use MauticPlugin\ThirdSetMauticTimingBundle\DependencyInjection\Compiler\OverrideServiceCompilerPass;

/**
 * Class ThirdSetMauticTimingBundle
 *
 * @package MauticPlugin\ThirdSetMauticTimingBundle
 */
class ThirdSetMauticTimingBundle extends PluginBundleBase
{
    
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        
        //NOTE: this is declared here (instead of in config.php) so that we
        //can inject it into the services defined below in this file.
        $container
            ->register(
                'plugin.thirdset.timing.campaign_event_manager',
                'MauticPlugin\ThirdSetMauticTimingBundle\Model\CampaignEventManager'
            )
            ->addArgument(new Reference('doctrine.orm.entity_manager'));
        
        /**
         * Type Extensions
         * Note: these are registered here because Mautic's config system
         * doesn't seem to be able to handle complex tags.
         */
        $container
            ->register(
                'plugin.thirdset.timing.event_type_extension',
                'MauticPlugin\ThirdSetMauticTimingBundle\Form\Extension\EventTypeExtension'
            )
            ->addArgument(new Reference('session'))
            ->addArgument(new Reference('plugin.thirdset.timing.campaign_event_manager'))
            ->addTag('form.type_extension', array('extended_type' => 'Mautic\CampaignBundle\Form\Type\EventType'));
        
        //Add a compiler pass for overriding mautic services
        $container->addCompilerPass(new OverrideServiceCompilerPass());
    }
}