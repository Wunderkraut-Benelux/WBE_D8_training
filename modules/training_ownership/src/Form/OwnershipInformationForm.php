<?php

namespace Drupal\training_ownership\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Egulias\EmailValidator\EmailValidatorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Ownership information form.
 */
class OwnershipInformationForm extends ConfigFormBase {

  /**
   * @var EmailValidatorInterface
   */
  private $emailValidator;

  /**
   * OwnershipInformationForm constructor.
   *
   * @param ConfigFactoryInterface $config_factory
   * @param EmailValidatorInterface $emailValidator
   */
  public function __construct(ConfigFactoryInterface $config_factory, EmailValidatorInterface $emailValidator) {
    parent::__construct($config_factory);
    $this->emailValidator = $emailValidator;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('email.validator')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ownership_information_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['training_ownership.ownership'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $ownership_settings = $this->config('training_ownership.ownership');

    $form['first_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('First name'),
      '#default_value' => $ownership_settings->get('first_name'),
      '#required' => TRUE,
    ];

    $form['last_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Last name'),
      '#default_value' => $ownership_settings->get('last_name'),
      '#required' => TRUE,
    ];

    $form['email'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Email address'),
      '#default_value' => $ownership_settings->get('email'),
      '#required' => TRUE,
    ];

    $form['company_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Company name'),
      '#default_value' => $ownership_settings->get('company_name'),
    ];

    $form['company_vat'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Company VAT'),
      '#default_value' => $ownership_settings->get('company_vat'),
      '#states' => [
        'invisible' => [
          ':input[name="company_name"]' => ['value' => ''],
        ],
      ],
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
    if (!$this->emailValidator->isValid($form_state->getValue('email'))) {
      $form_state->setErrorByName('email', $this->t('The email provided is invalid.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('training_ownership.ownership')
      ->set('first_name', $form_state->getValue('first_name'))
      ->set('last_name', $form_state->getValue('last_name'))
      ->set('email', $form_state->getValue('email'))
      ->set('company_name', $form_state->getValue('company_name'))
      ->set('company_vat', $form_state->getValue('company_vat'))
      ->save();

    parent::submitForm($form, $form_state);
  }
}