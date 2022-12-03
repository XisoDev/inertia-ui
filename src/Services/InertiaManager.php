<?php
namespace Xiso\InertiaUI\Services;
use Illuminate\Http\Request;
use \Laravel\Jetstream\InertiaManager as BaseInertiaManager;
use Xiso\InertiaUI\Handlers\ThemeHandler;

class InertiaManager extends BaseInertiaManager{
    //@override
    public function render(Request $request, string $page, array $data = []): \Inertia\Response
    {
        if (isset($this->renderingCallbacks[$page])) {
            foreach ($this->renderingCallbacks[$page] as $callback) {
                $data = $callback($request, $data);
            }
        }

        $themeHandler = new \Xiso\InertiaUI\Handlers\ThemeHandler();
        return $themeHandler->render($page,$data);
    }
}
