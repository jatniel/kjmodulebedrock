<?php
/**
 * Copyright since 2019 Kaudaj
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@kaudaj.com so we can send you a copy immediately.
 *
 * @author    Kaudaj <info@kaudaj.com>
 * @copyright Since 2019 Kaudaj
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 */

declare(strict_types=1);

namespace Kaudaj\Module\ModuleBedrock\Form;

use PrestaShop\PrestaShop\Core\Configuration\AbstractMultistoreConfiguration;
use PrestaShopBundle\Service\Form\MultistoreCheckboxEnabler;

/**
 * Configuration is used to save data to configuration table and retrieve from it
 */
final class ModuleBedrockConfiguration extends AbstractMultistoreConfiguration
{
    /**
     * @var string
     */
    public const EXAMPLE_SETTING_KEY = 'KJ_MODULE_BEDROCK_EXAMPLE_SETTING';

    /**
     * @var string[]
     */
    private $fields = ['example_setting'];

    /**
     * {@inheritdoc}
     */
    public function getConfiguration(): array
    {
        return [
            'example_setting' => $this->configuration->get(self::EXAMPLE_SETTING_KEY),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function updateConfiguration(array $configuration): array
    {
        if (!$this->validateConfiguration($configuration)) {
            return [
                'key' => 'Invalid configuration',
                'parameters' => [],
                'domain' => 'Admin.Notifications.Warning',
            ];
        } else {
            $shopConstraint = $this->getShopConstraint();

            try {
                $this->updateConfigurationValue(self::EXAMPLE_SETTING_KEY, 'example_setting', $configuration, $shopConstraint);
            } catch (\Exception $exception) {
                return [
                    'key' => $exception->getMessage(),
                    'parameters' => [],
                    'domain' => 'Admin.Notifications.Warning',
                ];
            }
        }

        return [];
    }

    /**
     * Ensure the parameters passed are valid.
     *
     * @param array $configuration
     *
     * @return bool Returns true if no exception are thrown
     */
    public function validateConfiguration(array $configuration): bool
    {
        foreach ($this->fields as $value) {
            $this->fields[] = MultistoreCheckboxEnabler::MULTISTORE_FIELD_PREFIX . $value;
        }

        foreach ($configuration as $key => $value) {
            if (!in_array($key, $this->fields)) {
                return false;
            }
        }

        return true;
    }
}
