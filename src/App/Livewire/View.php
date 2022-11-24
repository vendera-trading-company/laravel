<?php

namespace VenderaTradingCompany\App\Livewire;

use VenderaTradingCompany\App\Livewire\View\Components\Collection as ComponentsCollection;
use VenderaTradingCompany\App\Livewire\View\Components\Component as ViewComponent;
use VenderaTradingCompany\App\Livewire\View\Components\Input;
use VenderaTradingCompany\App\Support\Collection;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

abstract class View extends Component
{
    use WithFileUploads;

    public array $mountContext;

    public array $data = [];

    public function boot()
    {
        $this->data['session_context'] = $this->getSessionContext();
    }

    /**
     * This function will be called once.
     * Setup view and parse data from onMount into data['data']
     */
    public function mount(array $context = [])
    {
        $this->setMountContext($context);

        $this->data['session_context'] = $this->getSessionContext();
        $tmpContext = $this->getSessionContext();

        if (is_array($tmpContext)) {
            foreach ($tmpContext as $key => $value) {
                $this->data['context'][$key] = $value;
            }
        }

        $this->data['context'] = $this->getMountContext();

        $tmpContext = $this->onMount() ?? [];

        if (is_array($tmpContext)) {
            foreach ($tmpContext as $key => $value) {
                $this->data['context'][$key] = $value;
            }
        }
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
    {
        return $this->onRender();
    }

    public function setMountContext(mixed $value): void
    {
        $this->data['mount_context'] = $value;
    }

    public function getMountContext(): array
    {
        return $this->data['mount_context'] ?? [];
    }

    public function getViewId()
    {
        $tmp = explode('.', explode('Http.Livewire.', str_replace('\\', '.', $this::class))[1]);

        $path = '';

        foreach ($tmp as $key => $value) {
            if ($key != count($tmp) - 1) {
                $path .= Str::snake($value, '-') . '.';
            } else {
                $path .= Str::snake($value, '-');
            }
        }

        return $path;
    }

    public function getSessionId()
    {
        return $this->getViewId() . '_session';
    }

    public function setContext(string $key, mixed $value = null): void
    {
        $collection = Collection::create($this->data['context'])->set($key, $value);

        $this->data['context'] = $collection->get();
    }

    public function getContext(string $key = '', mixed $default = null): mixed
    {
        $collection = Collection::create($this->data['context']);

        return $collection->get($key, $default);
    }

    public function setSessionContext(string $key, mixed $value = null): void
    {
        $sessionCollection = Collection::create($this->data['session_context'])->set($key, $value);
        $collection = Collection::create($this->data['context'])->set($key, $value);

        $this->data['context'] = $collection->get();
        $this->data['session_context'] = $sessionCollection->get();

        session([
            $this->getSessionId() => $sessionCollection,
        ]);
    }

    private function getSessionContext()
    {
        $sessionContext = $this->data['session_context'] ?? null;

        if (empty($sessionContext)) {
            $sessionContext = session($this->getSessionId(), null);
        }

        if (empty($sessionContext)) {
            $sessionContext = [];
        }

        return $sessionContext;
    }

    public function updatedData($value, $key)
    {
        $this->setContext($key, $value);

        $exploded = explode('.', $key);

        if ($exploded[0] == 'components') {
            $this->getComponent($exploded[1])->update()->notifyListener();
        }

        $components = $this->getAllComponents();

        $urlParameters = '';

        foreach ($components as $componentKey => $componentValue) {
            if ($componentValue->isQueryable() &&  $componentValue->getValue() != '') {
                $urlParameters .= ($componentKey . '=' . $componentValue->getValue() . '&');
            }
        }

        $urlParameters = substr($urlParameters, 0, strlen($urlParameters) - 1);

        $this->emit('vendera_trading_company_laravel_url_changes', $urlParameters);
    }

    public function onMount()
    {
    }

    /**
     * This function is called in render method and must return a view.
     */
    public function onRender(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
    {
        return view($this->getViewId());
    }

    public function createComponent(ViewComponent $component, mixed $class): mixed
    {
        if (empty($class)) {
            $class = ViewComponent::class;
        }

        $this->data['components'][$component->getId()] = $component->toArray();

        return $this->getComponent($component->getId(), $class);
    }

    public function updateComponent(ViewComponent $component, mixed $class = null): mixed
    {
        if (empty($class)) {
            $class = ViewComponent::class;
        }

        $this->data['components'][$component->getId()] = $component->toArray();

        return $this->getComponent($component->getId(), $class);
    }

    public function getComponent(string $id, mixed $class = null): mixed
    {
        $component = $this->data['components'][$id];

        if (empty($class)) {
            $class = $component['class'];
        }

        return new $class($this, $component['id'], $component['data']);
    }

    public function getAllComponents(): array
    {
        $components = $this->data['components'];

        $result = [];

        foreach ($components as $key => $value) {
            $result[$key] = $this->getComponent($key);
        }

        return $result;
    }

    public function getCollection(string $id): ComponentsCollection
    {
        return $this->getComponent($id, ComponentsCollection::class);
    }

    public function getInput(string $id): Input
    {
        return $this->getComponent($id, Input::class);
    }
}
