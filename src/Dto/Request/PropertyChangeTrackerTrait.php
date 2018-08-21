<?php
declare(strict_types = 1);

namespace App\Dto\Request;

/**
 * Trait which provides an implementation of {@see \App\Dto\Request\PropertyChangeTrackerInterface}, and
 * the method to register the changes.
 *
 * @author Anton Pelykh <anton.pelykh.dev@gmail.com>
 */
trait PropertyChangeTrackerTrait
{
    /**
     * @var bool
     */
    protected $propertyChangeTrackerEnabled = true;
    /**
     * @var array
     */
    protected $propertyChangeSet = [];

    /**
     * {@inheritdoc}
     */
    public function isPropertyChanged(string $propertyName): bool
    {
        return isset($this->propertyChangeSet[$propertyName]);
    }

    /**
     * {@inheritdoc}
     */
    public function isTrackerEnabled(): bool
    {
        return $this->propertyChangeTrackerEnabled;
    }

    /**
     * {@inheritdoc}
     */
    public function setTrackerEnabled(bool $enabled): void
    {
        $this->propertyChangeTrackerEnabled = $enabled;
    }

    /**
     * Registers the property changes.
     *
     * @param string $propertyName
     */
    protected function registerPropertyChanged(string $propertyName): void
    {
        if ($this->isTrackerEnabled()) {
            $this->propertyChangeSet[$propertyName] = true;
        }
    }
}
