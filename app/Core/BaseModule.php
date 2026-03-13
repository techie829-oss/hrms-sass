<?php

namespace App\Core;

abstract class BaseModule
{
    /**
     * The module name.
     */
    protected string $name;

    /**
     * The module description.
     */
    protected string $description = '';

    /**
     * Whether the module is enabled by default.
     */
    protected bool $enabledByDefault = false;

    /**
     * Get the module name.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the module description.
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Check if enabled by default.
     */
    public function isEnabledByDefault(): bool
    {
        return $this->enabledByDefault;
    }

    /**
     * Get the module's route file path.
     */
    public function getRoutesPath(): string
    {
        return app_path("Modules/{$this->name}/routes.php");
    }

    /**
     * Get the module's views path.
     */
    public function getViewsPath(): string
    {
        return resource_path('views/modules/'.strtolower($this->name));
    }

    /**
     * Get required permissions for this module.
     */
    abstract public function getPermissions(): array;
}
