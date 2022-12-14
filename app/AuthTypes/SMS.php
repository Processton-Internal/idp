<?php

namespace App\AuthTypes;

use App\AuthChain\ModuleInterface;
use App\AuthChain\State;
use App\AuthChain\Subject;
use App\Exceptions\AuthFailedException;
use Illuminate\Http\Request;

class SMS extends AbstractType
{
    protected $enabled = true;

    public function init(Request $request, State $state, ModuleInterface $module)
    {

        //FIXME: won't remember resut for this module!
        $this->remembered = $state->getRememberedModuleResult($module) != null;

        //var_dump($this->remembered());exit;
        //TODO: set to true if a 2fa phone number is present!
        $this->enabled = true;
    }

    public function remembered()
    {
        return $this->remembered;
    }

    public function isEnabled(?Subject $subject)
    {
        return $this->enabled;
    }

    /**
     * Execute. Returns
     *
     * @return App\AuthChain\ModuleResult
     */
    public function process(Request $request, State $state, ModuleInterface $module)
    {
        if ($state->getSubject() == null) {
            throw new AuthFailedException('no subject!');
        }

        // if($state->getSubject()->getAttribute('mobile') == null){
        //     throw new AuthFailedException('no phone number!');
        // }

        return $module->baseResult()->complete()->setPrompted(true)->setRememberAlways(true);
    }
}
