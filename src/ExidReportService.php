<?php

declare(strict_types=1);

namespace Hartenthaler\Webtrees\Module\ExidReportModule;

use Fisharebest\Webtrees\DB;
use Fisharebest\Webtrees\Tree;
use Throwable;

final class ExidReportService
{
    /** @return array{total:int,exid:int,legacy:int,records:int,groups:list<array{uri:string,count:int,contexts:array<string,int>,registered:?bool,label:?string}> ,catalog_available:bool} */
    public function build(Tree $tree): array
    {
        $catalog = $this->catalog();
        $groups = [];
        $total = 0;
        $exid = 0;
        $legacy = 0;
        $records = 0;

        $sources = [
            ['table' => 'individuals', 'file' => 'i_file', 'gedcom' => 'i_gedcom', 'type' => 'INDI'],
            ['table' => 'families', 'file' => 'f_file', 'gedcom' => 'f_gedcom', 'type' => 'FAM'],
            ['table' => 'sources', 'file' => 's_file', 'gedcom' => 's_gedcom', 'type' => 'SOUR'],
            ['table' => 'media', 'file' => 'm_file', 'gedcom' => 'm_gedcom', 'type' => 'OBJE'],
            ['table' => 'other', 'file' => 'o_file', 'gedcom' => 'o_gedcom', 'type' => null],
        ];

        foreach ($sources as $source) {
            $query = DB::table($source['table'])->where($source['file'], '=', $tree->id());

            foreach ($query->select([$source['gedcom'], ...($source['type'] === null ? ['o_type'] : [])])->cursor() as $row) {
                $recordType = $source['type'] ?? strtoupper((string) $row->o_type);

                if (in_array($recordType, ['HEAD', 'TRLR'], true)) {
                    continue;
                }

                $records++;
                foreach ($this->parse((string) $row->{$source['gedcom']}, $recordType) as $item) {
                    $total++;
                    $item['tag'] === 'EXID' ? $exid++ : $legacy++;
                    $uri = $item['uri'] !== '' ? $item['uri'] : '(TYPE missing)';
                    $key = $uri;
                    $catalogUri = rtrim($uri, '/');

                    if (!isset($groups[$key])) {
                        $groups[$key] = [
                            'uri' => $uri,
                            'count' => 0,
                            'contexts' => [],
                            'registered' => $uri === '(TYPE missing)' ? null : array_key_exists($catalogUri, $catalog),
                            'label' => $catalog[$catalogUri] ?? null,
                        ];
                    }

                    $groups[$key]['count']++;
                    $context = $item['context'] !== '' ? $item['context'] : $recordType;
                    $groups[$key]['contexts'][$context] = ($groups[$key]['contexts'][$context] ?? 0) + 1;
                }
            }
        }

        foreach ($groups as &$group) {
            ksort($group['contexts']);
        }
        unset($group);

        usort($groups, static fn (array $a, array $b): int => $b['count'] <=> $a['count'] ?: strcmp($a['uri'], $b['uri']));

        return [
            'total' => $total,
            'exid' => $exid,
            'legacy' => $legacy,
            'records' => $records,
            'groups' => $groups,
            'catalog_available' => $catalog !== [],
        ];
    }

    /** @return list<array{tag:string,value:string,uri:string,context:string}> */
    private function parse(string $gedcom, string $recordType): array
    {
        $lines = preg_split('/\r\n|\r|\n/', $gedcom) ?: [];
        $items = [];
        $current = null;
        $stack = [];

        foreach ($lines as $line) {
            $trimmed = trim($line);

            if (preg_match('/^(\d+)\s+(?:@[^@]+@\s+)?([A-Za-z0-9_]+)/', $trimmed, $tagMatch) === 1) {
                $level = (int) $tagMatch[1];
                $stack[$level] = $tagMatch[2];
                foreach (array_keys($stack) as $stackLevel) {
                    if ($stackLevel > $level) {
                        unset($stack[$stackLevel]);
                    }
                }
            }

            if (preg_match('/^(\d+)\s+(EXID|_EXID)(?:\s+(.*))?$/', trim($line), $match) === 1) {
                if ($current !== null) {
                    $items[] = $current;
                }
                $level = (int) $match[1];
                $contextTags = array_slice($stack, 0, $level);
                $current = [
                    'tag' => $match[2],
                    'value' => trim($match[3] ?? ''),
                    'uri' => '',
                    'level' => $level,
                    'context' => implode(':', $contextTags) ?: $recordType,
                ];
                continue;
            }

            if ($current === null) {
                continue;
            }

            if (preg_match('/^(\d+)\s+TYPE(?:\s+(.*))?$/', trim($line), $match) === 1 && (int) $match[1] === $current['level'] + 1) {
                $current['uri'] = trim($match[2] ?? '');
                continue;
            }

            if (preg_match('/^(\d+)/', trim($line), $match) === 1 && (int) $match[1] <= $current['level']) {
                $items[] = $current;
                $current = null;
            }
        }

        if ($current !== null) {
            $items[] = $current;
        }

        return array_map(static fn (array $item): array => [
            'tag' => $item['tag'],
            'value' => $item['value'],
            'uri' => $item['uri'],
            'context' => $item['context'],
        ], $items);
    }

    /** @return array<string,string> */
    private function catalog(): array
    {
        $class = 'Hartenthaler\\Webtrees\\Module\\ExidModule\\ExidServices';

        if (!class_exists($class)) {
            return [];
        }

        try {
            $catalog = $class::catalog();
            $result = [];

            foreach ($catalog->all() as $definition) {
                $label = (string) ($definition['label'] ?? $definition['key'] ?? '');
                foreach ($definition['type_uris'] ?? [] as $uri) {
                    $result[rtrim((string) $uri, '/')] = $label;
                }
            }

            return $result;
        } catch (Throwable) {
            return [];
        }
    }
}
