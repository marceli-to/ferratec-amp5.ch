<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Statamic\Facades\Asset;
use Statamic\Facades\Collection as Collections;
use Statamic\Facades\Entry;
use Statamic\Facades\GlobalSet;
use Statamic\Facades\Site;

class SeoController extends Controller
{
    /**
     * Collections that have public URLs and belong in the sitemap.
     */
    protected const COLLECTIONS = ['pages', 'posts'];

    public function sitemap(): Response
    {
        $siteOrder = Site::all()->keys()->flip();

        $groups = $this->publishedEntries()
            ->sortBy(fn ($entry) => $siteOrder[$entry->locale()])
            ->groupBy(fn ($entry) => $entry->root()->id());

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">'."\n";

        foreach ($groups as $localizations) {
            foreach ($localizations as $entry) {
                $xml .= "  <url>\n";
                $xml .= '    <loc>'.e($entry->absoluteUrl())."</loc>\n";
                $xml .= '    <lastmod>'.$entry->lastModified()->toAtomString()."</lastmod>\n";

                if ($localizations->count() > 1) {
                    foreach ($localizations as $alternate) {
                        $xml .= '    <xhtml:link rel="alternate" hreflang="'.$alternate->site()->shortLocale().'" href="'.e($alternate->absoluteUrl()).'"/>'."\n";
                    }

                    if ($default = $localizations->first(fn ($alternate) => $alternate->site()->isDefault())) {
                        $xml .= '    <xhtml:link rel="alternate" hreflang="x-default" href="'.e($default->absoluteUrl()).'"/>'."\n";
                    }
                }

                $xml .= "  </url>\n";
            }
        }

        $xml .= "</urlset>\n";

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    /**
     * Markdown summary of the site for LLMs (https://llmstxt.org), in German with links to the other languages.
     */
    public function llms(): Response
    {
        $site = Site::default();
        $home = rtrim($site->absoluteUrl(), '/').'/';

        $lines = [
            '# AMP5 – Ferratec',
            '',
            '> AMP5 ist ein modulares Ladesystem für Elektrofahrzeuge in Tiefgaragen und auf Parkplätzen im Freien. Zentral gesteuert, kabelgebunden statt funkbasiert, ausgelegt auf über 20 Jahre Betrieb. In der Schweiz exklusiv vertrieben, geschult und betreut durch Ferratec Technics AG.',
            '',
            '- Parkplätze im Freien (Gewerbe & Areale): eine Zuleitung genügt, oberirdische Verteilung, Schraubfundamente ohne Beton, Erweiterung ohne erneuten Tiefbau.',
            '- Tiefgaragen (Mehrfamilienhäuser & STWEG): Datenverbindung über die Systemkabel statt Funk, keine zusätzliche WLAN-Infrastruktur, zentrale Steuerung aller Ladepunkte.',
            '- Günstiger als Einzel-Wallboxen, besonders beim Ausbau: weniger Tiefbau, keine Betonfundamente, keine neue Zuleitung für weitere Ladepunkte.',
            '- Installation durch geschulte Schweizer Partner, Abrechnung mit eponet, 5 Jahre Garantie, 20+ Jahre Lebensdauer.',
            '',
            '## Website',
            '',
            "- [Startseite]({$home})",
            "- [Sechs Gründe für AMP5]({$home}#vorteile)",
            "- [Schulung]({$home}#schulung)",
            "- [Planner]({$home}#planner)",
            "- [Kontakt]({$home}#kontakt): Ferratec Technics AG, Grossmattstrasse 19, 8964 Rudolfstetten, +41 56 649 21 21, info@ferratec.ch",
        ];

        $downloads = $this->downloads($site->handle());

        if ($downloads->isNotEmpty()) {
            array_push($lines, '', '## Downloads', '', ...$downloads);
        }

        $posts = $this->publishedEntries()
            ->filter(fn ($entry) => $entry->collectionHandle() === 'posts' && $entry->locale() === $site->handle())
            ->sortByDesc(fn ($entry) => $entry->date())
            ->map(fn ($entry) => '- ['.$entry->get('title').']('.$entry->absoluteUrl().')'.($entry->get('teaser') ? ': '.$this->oneLine($entry->get('teaser')) : ''));

        if ($posts->isNotEmpty()) {
            array_push($lines, '', '## News', '', ...$posts);
        }

        $languages = Site::all()
            ->reject(fn ($other) => $other->handle() === $site->handle())
            ->map(fn ($other) => "- [{$other->name()}]({$other->absoluteUrl()})")
            ->push('- [Sitemap]('.url('sitemap.xml').')');

        $lines = [...$lines, '', '## Optional', '', ...$languages, ''];

        return $this->text(implode("\n", $lines), 'text/markdown');
    }

    /**
     * Published entries with a URL, across all sites.
     */
    protected function publishedEntries(): Collection
    {
        $collections = array_filter(self::COLLECTIONS, fn ($handle) => Collections::handleExists($handle));

        if (! $collections) {
            return collect();
        }

        return Entry::query()
            ->whereIn('collection', $collections)
            ->whereStatus('published')
            ->get()
            ->filter(fn ($entry) => $entry->absoluteUrl())
            ->values();
    }

    protected function downloads(string $site): Collection
    {
        $set = GlobalSet::findByHandle('downloads')?->in($site);

        return collect($set?->value('files') ?? [])
            ->map(function ($row) {
                $url = ! empty($row['file'])
                    ? Asset::find('assets::'.$row['file'])?->absoluteUrl()
                    : ($row['url'] ?? null);

                if (! $url) {
                    return null;
                }

                $label = $row['label'] ?? basename($url);

                return "- [{$label}]({$url})";
            })
            ->filter()
            ->values();
    }

    protected function oneLine(string $text): string
    {
        return trim(preg_replace('/\s+/', ' ', strip_tags($text)));
    }

    protected function text(string $body, string $type = 'text/plain'): Response
    {
        return response($body, 200, ['Content-Type' => "{$type}; charset=UTF-8"]);
    }
}
