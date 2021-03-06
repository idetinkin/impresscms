<?php


namespace ImpressCMS\Core\Controllers;

use Exception;
use GuzzleHttp\Psr7\Response;
use icms;
use ImpressCMS\Core\Models\Module;
use ImpressCMS\Core\Models\ModuleHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use function GuzzleHttp\Psr7\mimetype_from_filename;

/**
 * This controller is used when dealing with legacy code
 *
 * @package ImpressCMS\Core\Controllers
 */
class LegacyController
{

	/**
	 * Proxy legacy file
	 *
	 * @param ServerRequestInterface $request Request
	 *
	 * @return ResponseInterface
	 */
	public function proxy(ServerRequestInterface $request): ResponseInterface
	{
		$prefixOfRoute = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
		$filePath = $prefixOfRoute ? mb_substr($request->getUri()->getPath(), mb_strlen($prefixOfRoute)) : $request->getUri()->getPath();
		if (substr($filePath, -1) === '/') {
			$filePath = substr($prefixOfRoute, 0, -1);
		}
		$path = ICMS_ROOT_PATH . DIRECTORY_SEPARATOR . $filePath;
		if (pathinfo($path, PATHINFO_EXTENSION) === 'php') {
			$inAdmin = (defined('ICMS_IN_ADMIN') && (int)ICMS_IN_ADMIN);
			$module = $request->getAttribute('module');

			if (!ModuleHandler::checkModuleAccess($module, $inAdmin)) {
				return redirect_header(ICMS_URL . "/user.php", 3, _NOPERM, FALSE);
			}

			$module_handler = icms::handler('icms_module');
			try {
				$modules = $module_handler->getObjects();
				/**
				 * @var Module $module
				 */
				foreach ($modules as $module) {
					$module->registerClassPath(TRUE);
				}
			} catch (Exception $exception) {

			}

			global $icmsTpl, $xoopsTpl, $xoopsOption, $icmsAdminTpl, $icms_admin_handler;
			ob_start();
			require $path;
			return new Response(
				200,
				[],
				ob_get_clean()
			);
		}

		return new Response(
			200,
			[
				'Content-Type' => mimetype_from_filename($path),
				'Content-Length' => filesize($path),
				'E-Tag' => sprintf('"%s"', sha1_file($path)),
				'Last-Modified' => gmdate('D, d M Y H:i:s ', filemtime($path)) . 'GMT'
			],
			fopen($path, 'rb')
		);
	}

}