<?php

declare(strict_types=1);

namespace Drupal\yse_libraryitem_override\Plugin\paragraphs\Behavior;

use Drupal\Core\Entity\Display\EntityViewDisplayInterface;
use Drupal\Core\Entity\EntityFieldManager;
use Drupal\Core\Form\FormStateInterface;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\paragraphs\Entity\ParagraphsType;
use Drupal\paragraphs\ParagraphInterface;
use Drupal\paragraphs\ParagraphsBehaviorBase;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Behavior to allow from_library paragraphs to locally override stored behavior.
 *
 * @ParagraphsBehavior(
 *   id="yse_libraryitem_override",
 *   label=@Translation("YSE LibraryItem Override"),
 *   description=@Translation("A Plugin to allow overriding a paragraph view mode and behavior settings for from_library paragraphs"),
 *   weight=-1
 * )
 */



class YseLibraryitemOverride extends ParagraphsBehaviorBase {

  /**
   * Constructs a new YseLibraryitemOverride object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityFieldManager $entity_field_manager
   *   Entity Field Manager for base.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityFieldManager $entity_field_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $entity_field_manager);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static($configuration, $plugin_id, $plugin_definition, $container->get('entity_field.manager'));
  }

  public static function isApplicable(ParagraphsType $paragraphs_type) {
    if ($paragraphs_type->id() == 'from_library') {
      return TRUE;
    }
    return FALSE;
  }


  /**
   * {@inheritdoc}
   */
  public function buildBehaviorForm(ParagraphInterface $paragraph, array &$form, FormStateInterface $form_state) {
    // Only display if this plugin is enabled and the user has the permissions.

    $form['override_behavior'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Override Stored Behaviors?'),
      '#description' => $this->t('If selected this from_library paragraph will use local settings instead of stored behaviors.'),
      '#default_value' => $paragraph->getBehaviorSetting($this->getPluginId(), 'override_behavior'),
    ];
    return $form;
  }


  /**
   * {@inheritdoc}
   */
  public function view(array &$build, Paragraph $paragraph, EntityViewDisplayInterface $display, $view_mode) {
    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary(Paragraph $paragraph) {
    $locked = $paragraph->getBehaviorSetting($this->getPluginId(), 'override_behavior');
    $summary = [
      [
        'value' => $this->t('override_behavior'),
      ]
    ];
    return $locked ? $summary : [];
  }


  public function defaultConfiguration() {
    return parent::defaultConfiguration() + ['override_behavior' => FALSE];
  }

}
