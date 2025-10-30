<?php

namespace OCA\FramaSpace\Settings;

use OCP\IL10N;
use OCP\IURLGenerator;
use OCP\Settings\IIconSection;

class AdminSection implements IIconSection
{
    public function __construct(
        protected IURLGenerator $url,
        protected IL10N $l
    ) {
    }

    public function getID(): string
    {
        return 'framaspace';
    }

    public function getName(): string
    {
        return $this->l->t('Framaspace');
    }

    public function getPriority(): int
    {
        return 50;
    }

    public function getIcon(): string
    {
        return $this->url->imagePath('framaspace', 'app.svg');
    }
}