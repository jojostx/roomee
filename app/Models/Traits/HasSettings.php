<?php

namespace App\Models\Traits;

trait HasSettings
{
    public function settings(array $revisions): self
    {
        $this->settings = array_merge($this->settings ?? [], $revisions);
        $this->save();

        return $this;
    }

    public function setting(string $name, $default = null)
    {
        if (array_key_exists($name, $this->settings ?? [])) {
            return $this->settings[$name];
        }

        return $default;
    }
}
