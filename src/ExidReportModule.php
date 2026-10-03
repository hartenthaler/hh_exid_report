<?php

declare(strict_types=1);

namespace Hartenthaler\Webtrees\Module\ExidReportModule;

use Fisharebest\Webtrees\Auth;
use Fisharebest\Webtrees\I18N;
use Fisharebest\Webtrees\Individual;
use Fisharebest\Webtrees\Menu;
use Fisharebest\Webtrees\Module\AbstractModule;
use Fisharebest\Webtrees\Module\ModuleCustomInterface;
use Fisharebest\Webtrees\Module\ModuleCustomTrait;
use Fisharebest\Webtrees\Module\ModuleReportInterface;
use Fisharebest\Webtrees\Tree;
use Fisharebest\Webtrees\Validator;
use Fisharebest\Webtrees\View;
use Hartenthaler\Webtrees\Shared\Internationalization\TranslationLoader;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ExidReportModule extends AbstractModule implements ModuleCustomInterface, ModuleReportInterface
{
    use ModuleCustomTrait;

    private readonly ExidReportService $reportService;

    public function __construct()
    {
        $this->reportService = new ExidReportService();
    }

    public function boot(): void
    {
        View::registerNamespace($this->name(), $this->resourcesFolder() . 'views/');
    }

    public function resourcesFolder(): string
    {
        return __DIR__ . '/../resources/';
    }

    public function title(): string
    {
        return I18N::translate('EXID usage report');
    }

    public function description(): string
    {
        return I18N::translate('Read-only report of EXID and _EXID values used in a family tree.');
    }

    public function customModuleAuthorName(): string
    {
        return 'Hermann Hartenthaler';
    }

    public function customModuleVersion(): string
    {
        return trim((string) file_get_contents(__DIR__ . '/../version.txt'));
    }

    public function customModuleLatestVersionUrl(): string
    {
        return 'https://raw.githubusercontent.com/hartenthaler/hh_exid_report/main/version.txt';
    }

    public function customModuleSupportUrl(): string
    {
        return 'https://github.com/hartenthaler/hh_exid_report/issues';
    }

    /**
     * Load module translations on webtrees 2.2 and 2.3.
     *
     * webtrees 2.3 provides Fisharebest\\Webtrees\\I18N\\Translation,
     * while 2.2 used Fisharebest\\Localization\\Translation.
     *
     * @return array<string,string>
     */
    public function customTranslations(string $language): array
    {
        return TranslationLoader::load($this->resourcesFolder() . 'lang/', $language);
    }

    public function getReportMenu(Individual $individual): Menu
    {
        return new Menu(
            $this->title(),
            $this->reportUrl($individual->tree()),
            'menu-report-' . $this->name(),
            ['rel' => 'nofollow'],
        );
    }

    public function xmlFilename(): string
    {
        return 'xml/reports/' . $this->name() . '.xml';
    }

    public function getReportAction(ServerRequestInterface $request): ResponseInterface
    {
        $tree = Validator::attributes($request)->tree();
        $user = Validator::attributes($request)->user();
        Auth::checkComponentAccess($this, ModuleReportInterface::class, $tree, $user);

        return $this->viewResponse($this->name() . '::report', [
            'title' => $this->title(),
            'tree'  => $tree,
            'report' => $this->reportService->build($tree),
        ]);
    }

    private function reportUrl(Tree $tree): string
    {
        return route('module', [
            'module' => $this->name(),
            'action' => 'Report',
            'tree'   => $tree->name(),
        ]);
    }
}
