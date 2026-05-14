<?php

namespace ME\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use ME\Services\ActivityLoggerService;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ActivityLogger
{
	protected array $ignoredRouteNames = [
		'debugbar.*',
	];

	/**
	 * Handle an incoming request.
	 */
	public function handle(Request $request, Closure $next): Response
	{
		if (! $this->shouldLog($request)) {
			return $next($request);
		}

		$activityType = $this->generateActivityType($request);
		$description = $this->buildDescription($request);

		try {
			$response = $next($request);

			$status = $response->getStatusCode() < 400 ? 'success' : 'failed';
			$this->log($request, $activityType, $status, $description);

			return $response;
		} catch (Throwable $exception) {
			$this->log($request, $activityType, 'failed', $description.' | Exception: '.$exception->getMessage());

			throw $exception;
		}
	}

	protected function shouldLog(Request $request): bool
	{
		if (! $request->route()) {
			return false;
		}

		$routeName = $request->route()->getName();

		foreach ($this->ignoredRouteNames as $pattern) {
			if ($routeName && Str::is($pattern, $routeName)) {
				return false;
			}
		}

		return true;
	}

	protected function log(Request $request, string $activityType, string $status, string $description): void
	{
		$logger = new ActivityLoggerService($request);
		$logger->logActivity(Auth::id(), $activityType, $status, $description);
	}

	protected function generateActivityType(Request $request): string
	{
		$route = $request->route();
		$method = strtolower($request->getMethod());

		$routeName = $route?->getName() ?? '';
		$cleanName = preg_replace('/^[^.]+\./', '', $routeName) ?: '';
		$nameParts = $cleanName !== '' ? explode('.', $cleanName) : [];

		$resource = count($nameParts) > 1 ? $nameParts[count($nameParts) - 2] : null;
		$action = count($nameParts) > 0 ? $nameParts[count($nameParts) - 1] : null;

		if (! $resource) {
			$resource = $this->guessResourceFromUri($request->path());
		}

		$resourceSingular = Str::snake(Str::singular($resource ?? 'resource'));
		$resourcePlural = Str::snake(Str::plural($resourceSingular));

		if ($method === 'get' || $method === 'head') {
			if ($action === 'create' || Str::contains($request->path(), '/create')) {
				return 'visit_create_'.$resourceSingular;
			}

			if ($action === 'edit' || Str::contains($request->path(), '/edit')) {
				return 'visit_edit_'.$resourceSingular;
			}

			return 'visit_'.$resourcePlural;
		}

		if ($method === 'post') {
			if ($action === 'store' || $action === 'create') {
				return 'store_create_'.$resourceSingular;
			}

			return 'post_'.$resourceSingular;
		}

		if ($method === 'put' || $method === 'patch') {
			return 'update_'.$resourceSingular;
		}

		if ($method === 'delete') {
			return 'delete_'.$resourceSingular;
		}

		return $method.'_'.$resourceSingular;
	}

	protected function buildDescription(Request $request): string
	{
		$routeName = $request->route()?->getName() ?? 'unnamed';

		return sprintf(
			'%s %s (route: %s)',
			strtoupper($request->method()),
			'/'.$request->path(),
			$routeName
		);
	}

	protected function guessResourceFromUri(string $path): string
	{
		$segments = array_values(array_filter(explode('/', trim($path, '/'))));

		if (empty($segments)) {
			return 'route';
		}

		$ignored = ['me', 'api'];

		foreach ($segments as $segment) {
			if (! in_array($segment, $ignored, true) && ! Str::startsWith($segment, '{')) {
				return $segment;
			}
		}

		return end($segments) ?: 'route';
	}
}
