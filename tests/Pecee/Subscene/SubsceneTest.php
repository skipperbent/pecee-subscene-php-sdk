<?php

namespace Pecee\OpenSubtitles;

use Pecee\Service\Subscene;
use PHPUnit\Framework\TestCase;

class SubsceneTest extends TestCase
{

    public function testSearch()
    {

        $movie = new Subscene();

        // My local Apache client won't work unless this is set
        $movie->getHttpRequest()->setOptions([
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 2,
        ]);

        $subtitles = $movie->searchFull('The Walk', ['dan', 'eng'])->toArray();

        if (isset($subtitles[0]['subtitles'][0]['download_url'], $subtitles[0]['subtitles'][1]['download_url'])) {
            unset($subtitles[0]['subtitles'][0]['download_url'], $subtitles[0]['subtitles'][1]['download_url']);
        }

        $this->assertEquals([
            [
                'id'        => 'the-walk',
                'title'     => 'The Walk',
                'year'      => '2015',
                'count'     => '114',
                'subtitles' =>
                    [
                        [
                            'language'    => 'Danish',
                            'language_id' => 'dan',
                            'filename'    => 'The-Walk-2015-720p-BluRay-x264-YIFY-[YTS.AG]',
                            'author'      => 'Firewalker.dk',
                            'comment'     => 'Retail Rippet og tilpasset af TeamSky uploadet af Firewalker.dk',
                        ],
                        [
                            'language'    => 'English',
                            'language_id' => 'eng',
                            'filename'    => 'Yify / Superchillin',
                            'author'      => 'japangoodtime',
                            'comment'     => 'French speaking parts and some hard to hear English part',
                        ],
                    ],
            ],
        ], $subtitles);

    }

}