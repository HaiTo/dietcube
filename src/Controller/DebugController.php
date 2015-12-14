<?php
/**
 *
 */

namespace Dietcube\Controller;

class DebugController extends InternalControllerAbstract
{
    public function dumpErrors(\Exception $errors)
    {
        $app = $this->get('app');

        $app_root = $app->getAppRoot();
        $vendor_dir = $app->getVendorDir();

        return $this->render('debug', [
            'controller_name' => __CLASS__,
            'action_name' => __METHOD__,
            'dirs' => [
                'app_root' => $app_root,
                'vendor_dir' => $vendor_dir,
                'config_dir' => $app->getConfigDir(),
                'webroot_dir' => $app->getWebrootDir(),
                'resource_dir' => $app->getResourceDir(),
                'template_dir' => $app->getTemplateDir(),
                'tmp_dir' => $app->getTmpDir(),
            ],
            'error_class_name' => get_class($errors),
            'errors' => $errors,
            'error_trace' => preg_replace(
                ['!' . $app_root . '!', '!' . $vendor_dir . '!' , ],
                ['#root ', '#vendor ', ],
                $errors->getTraceAsString()
            ),
            'get_params'    => $this->get('global.get')->get(),
            'post_params'   => $this->get('global.post')->get(),
            'cookie_params' => $this->get('global.cookie')->get(),
            'server_params' => $this->get('global.server')->get(),
        ]);
    }
}