<?php

namespace Pecee\Service;

use Pecee\Http\HttpRequest;
use Pecee\Service\Subscene\Exception;
use Pecee\Service\Subscene\Response;

class Subscene
{

    /**
     * Endpoint url
     */
    const SERVICE_URL = 'https://subscene.com';

    /**
     * HttpRequest object
     * @var HttpRequest
     */
    protected $httpRequest;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->httpRequest = new HttpRequest();

        $this->httpRequest->setHeaders([
            'referer: https://subscene.com',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.108 Safari/537.36',
            'accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
            'accept-language:da-DK,da;q=0.9,en-DK;q=0.8,en;q=0.7,en-US;q=0.6',
            'cache-control:max-age=0',
            'cookie:LanguageFilter=; HearingImpaired=0; ForeignOnly=False',
        ]);
    }

    /**
     * Execute api
     *
     * @param string $url
     * @return string
     * @throws Exception
     */
    protected function api($url)
    {
        $this->httpRequest->setUrl(static::SERVICE_URL . $url);
        $httpResponse = $this->httpRequest->execute();

        if ($httpResponse->getStatusCode() !== 200) {
            throw new Exception(sprintf('Request failed with status-code %s', $httpResponse->getStatusCode()), $httpResponse->getStatusCode());
        }

        if ($httpResponse->getResponse() === '') {
            throw new Exception('Invalid response');
        }

        return $httpResponse->getResponse();

    }

    /**
     * Get subtitles
     *
     * @param string $id
     * @param array|null $languageIds Filter by language ids (eng, nor, pol etc).
     * @param bool $multiple Fetch multiple subtitles per language (slower).
     * @return Response
     * @throws Exception
     */
    public function getSubtitles($id, array $languageIds = null, $multiple = false)
    {
        $response = $this->api('/subtitles/' . $id);

        preg_match('/<div class="content.+?<tbody>(.*)<\/tbody>/is', $response, $response);

        preg_match_all('/<tr[^>]*?>\s*(.*?)\s*<\/tr>/ism', $response[1], $matches);

        $subtitles = [];
        $downloadedLanguages = [];

        foreach ((array)$matches[1] as $details) {

            if (stripos($details, 'banner') !== false) {
                continue;
            }

            preg_match_all('/.+?>([^<>]+)<\/.+?/', $details, $info);
            $info = $info[1];

            $language = trim($info[0]);
            $languageId = strtolower(substr($language, 0, 3));

            if (($multiple === false && in_array($languageId, $downloadedLanguages, true) === true) || ($languageIds !== null && in_array($languageId, $languageIds, true) === false)) {
                continue;
            }

            if ($multiple === false) {
                $downloadedLanguages[] = $languageId;
            }

            preg_match('/<a href="(.+)">/i', $details, $downloadLink);

            $response = $this->api($downloadLink[1]);

            preg_match('/.+?="download">.+?<a href="([^"]+)".+?/s', $response, $downloadLink);
            $downloadLink = $downloadLink[1];

            $subtitles[] = [
                'language'     => $language,
                'language_id'  => $languageId,
                'filename'     => trim($info[1]),
                'author'       => trim($info[5]),
                'comment'      => trim(trim(html_entity_decode($info[6]), chr(0xC2) . chr(0xA0) . "\s\t\n")),
                'download_url' => static::SERVICE_URL . trim($downloadLink),
            ];
        }

        return new Response($subtitles);
    }

    /**
     * Search subtitles matching a given title
     *
     * @param string $title Title of the movie you wish to find subtitles for.
     * @return Response
     * @throws Exception
     */
    public function search($title)
    {
        $output = [];

        $response = $this->api(sprintf('/subtitles/title?q=%s&l=&r=true', rawurlencode($title)));

        preg_match_all('/<div class="search-result">.+?<ul>.+?<li>(.*?)<\/li>.+?<\/ul>.+?<\/div>/is', $response, $matches);

        if (count($matches) > 0) {

            foreach ((array)$matches[1] as $match) {

                preg_match('/<a.+?>(.+)<\/a>/i', $match, $titles);
                $title = $titles[1];

                preg_match('/<a href="(.+)".+/i', $match, $link);
                $link = $link[1];

                preg_match('/<div class="subtle count">[\D]+([\d]+)[\D]+<\/div>/i', $match, $count);
                $count = $count[1];

                preg_match('/(.+)\(([\d]+)\)/i', $title, $titles);
                array_shift($titles);

                $year = null;

                if (isset($title[0], $title[1]) === true) {
                    list($title, $year) = $titles;
                }

                $id = str_replace('/subtitles/', '', $link);

                $output[] = [
                    'id'    => $id,
                    'title' => trim($title),
                    'year'  => $year,
                    'count' => $count,
                ];

            }

        }

        return new Response($output);

    }

    /**
     * Search for subtitles and fetch download links
     *
     * @param string $title
     * @param array $languageIds
     * @return Response
     * @throws Exception
     */
    public function searchFull($title, array $languageIds = null)
    {
        $subtitles = $this->search($title)->toArray();

        foreach ($subtitles as $key => $subtitle) {
            $subtitles[$key]['subtitles'] = $this->getSubtitles($subtitle['id'], $languageIds)->toArray();
        }

        return new Response($subtitles);

    }

    /**
     * Get http request
     *
     * @return HttpRequest
     */
    public function getHttpRequest()
    {
        return $this->httpRequest;
    }

}
